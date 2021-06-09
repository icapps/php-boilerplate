<?php

namespace App\Service\Api\General;

use App\Entity\Profile;
use App\Entity\User;
use App\Mail\MailHelper;
use App\Repository\DeviceRepository;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Gesdinet\JWTRefreshTokenBundle\Service\RefreshToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @package App\Service\Api\General
 */
class AuthService extends ApiService
{

    /**
     * AuthService constructor.
     * @param UserRepository $userRepository
     * @param ProfileRepository $profileRepository
     * @param DeviceRepository $deviceRepository
     * @param RefreshToken $refreshTokenService
     * @param ValidatorInterface $validator
     * @param TranslatorInterface $translator
     * @param MailHelper $mailHelper
     * @param LoggerInterface $logger
     */
    public function __construct(
        private UserRepository $userRepository,
        private ProfileRepository $profileRepository,
        private DeviceRepository $deviceRepository,
        private RefreshToken $refreshTokenService,
        private ValidatorInterface $validator,
        private TranslatorInterface $translator,
        private MailHelper $mailHelper,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * Register new user.
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return JsonResponse
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder): JsonResponse
    {
        // Get language.
        $response_language = $request->headers->get('app-language-code') ?? 'nl';

        // Retrieve request body parameters.
        $request = $this->transformJsonBody($request);
        $firstName = $request->get('firstName') ?? '';
        $lastName = $request->get('lastName') ?? '';
        $email = $request->get('email') ?? '';
        $language = $request->get('language') ?? User::DEFAULT_LOCALE;
        $password = $request->get('password') ?? '';

        // Create profile.
        $profile = $this->profileRepository->create();
        $profile->setFirstName($firstName);
        $profile->setLastName($lastName);

        // Registration validation.
        if ($errors = $this->handleValidation($profile, ['orm-registration'], $response_language)) {
            return $errors;
        }

        // Check user existence.
        if ($this->userRepository->findOneBy(['email' => $email, 'profileType' => Profile::PROFILE_TYPE])) {
            $errors = $this->translator->trans('icapps.registration.email.not_available', [], 'validators', $response_language);
            return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)->respondWithErrors($errors);
        }

        // Create user.
        $user = $this->userRepository->create();
        $user->setEmail($email);
        $user->setLanguage($language);
        $user->setPassword($password);
        $user->setProfileType(Profile::PROFILE_TYPE);
        $user->setActivationToken(self::getUniqueToken());
        $user->disable();

        // User validation.
        if ($errors = $this->handleValidation($user, ['orm-registration'], $response_language)) {
            return $errors;
        }

        $this->profileRepository->beginTransaction();
        $this->userRepository->beginTransaction();

        try {
            $this->profileRepository->save($profile);

            // Save user.
            $user->setPassword($encoder->encodePassword($user, $password));
            $user->setProfileId($profile->getId());
            $this->userRepository->save($user);

            $this->profileRepository->commit();
            $this->userRepository->commit();
        } catch (Exception $e) {
            $this->profileRepository->rollback();
            $this->userRepository->rollback();

            $errors = $this->translator->trans('icapps.registration.failed', [], 'validators', $response_language);
            $this->logger->critical($errors . ' :' . $e->getMessage());
            return $this->setStatusCode(Response::HTTP_BAD_REQUEST)->respondWithErrors($errors);
        }

        // Send mail.
        $this->mailHelper->sendRegistrationActivationMail($user, $profile);

        // Log it.
        $this->logger->info(sprintf('New user registration [id: "%s", e-mail: "%s"]', $user->getId(), $user->getEmail()), ['user' => $user]);

        // Success.
        $success = $this->translator->trans('icapps.registration.completed', ['%user' => $profile->getFirstName()], 'validators');
        return $this->respondWithSuccess($success);
    }

    /**
     * Requests a user password reset.
     *
     * @param Request $request
     * @param string $profileType
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function requestUserPasswordReset(Request $request, string $profileType): JsonResponse
    {
        // Retrieve request body parameters.
        $request = $this->transformJsonBody($request);
        $email = $request->get('email');

        //success message
        $success = $this->translator->trans('icapps.mail.reset_password.sent', [], 'messages');

        $user = $this->userRepository->findOneBy(['email' => $email, 'profileType' => $profileType]);

        if (!$user) {
            //silent error
            $errors = $this->translator->trans('icapps.reset.email.not_found', [], 'validators');
            $this->logger->warning($errors);
            return $this->respondWithSuccess($success);
        }

        // Set reset token for user.
        $user->setResetToken(self::getUniqueToken());
        $this->userRepository->save($user);

        // Get profile.
        $profile = match ($user->getProfileType()) {
            Profile::PROFILE_TYPE => $this->profileRepository->findById($user->getProfileId()),
        };

        // Send reset mail.
        $this->mailHelper->sendUserPasswordResetMail($user, $profile);

        // Log it.
        $this->logger->info(sprintf('User password reset requested [id: "%s", e-mail: "%s"]', $user->getId(), $user->getEmail()), ['user' => $user]);

        return $this->respondWithSuccess($success);
    }

    /**
     * E-mail validation.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function emailValidation(Request $request): JsonResponse
    {
        // Retrieve request body parameters.
        $request = $this->transformJsonBody($request);
        $email = $request->get('email');

        $user = $this->userRepository->create();
        $user->setEmail($email);

        // Validation.
        if ($errors = $this->handleValidation($user, ['orm-email-validation'])) {
            return $errors;
        }

        // Success.
        $success = $this->translator->trans('icapps.registration.email.available', [], 'validators');
        return $this->respondWithSuccess($success);
    }

    /**
     * Refresh user token.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function refreshToken(Request $request): JsonResponse
    {
        return $this->refreshTokenService->refresh($request);
    }

    /**
     * Handle validation.
     *
     * @param $entity
     * @param array|string[] $groups
     * @param string $language
     * @return JsonResponse|null
     */
    public function handleValidation($entity, array $groups = ['orm-registration'], string $language = 'nl'): ?JsonResponse
    {
        $errors = $this->validator->validate($entity, null, $groups);

        if (count($errors) > 0) {
            foreach ($errors as $violation) {
                $message = $this->translator->trans(
                    $violation->getMessageTemplate(),
                    $violation->getParameters(),
                    'validators',
                    $language
                );
                return $this->respondValidationError($message);
            }
        }

        return null;
    }

    /**
     * Get an unique token, ea: activation/reset.
     */
    public static function getUniqueToken(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    /**
     * @param User $user
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logout(User $user, Request $request): JsonResponse
    {
        $request = $this->transformJsonBody($request);
        $deviceId = $request->get('deviceId');
        if (!$deviceId || empty($deviceId)) {
            return $this->setStatusCode(Response::HTTP_BAD_REQUEST)->respondWithErrors(
                $this->translator->trans('icapps.general.missing_field', ['%field%' => 'deviceId'], 'messages')
            );
        }

        try {
            if ($device = $this->deviceRepository->findOneBy(['deviceId' => $deviceId])) {
                $this->deviceRepository->remove($device->getId());
            }
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
            return $this->setStatusCode(Response::HTTP_BAD_REQUEST)->respondWithErrors(
                $this->translator->trans('icapps.general.error', [], 'messages')
            );
        }

        return $this->setStatusCode(Response::HTTP_OK)->respondWithSuccess(
            $this->translator->trans('icapps.user.logout_success', [], 'messages')
        );
    }

    /**
     * @param User $user
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateDeviceToken(User $user, Request $request): JsonResponse
    {
        $request = $this->transformJsonBody($request);
        $deviceId = $request->get('deviceId');
        $deviceToken = $request->get('deviceToken');
        if (!$deviceId || empty($deviceId) || !is_string($deviceId)) {
            return $this->setStatusCode(Response::HTTP_BAD_REQUEST)->respondWithErrors(
                $this->translator->trans('icapps.general.missing_field', ['%field%' => 'deviceId'], 'messages')
            );
        }
        if (!$deviceToken || empty($deviceToken) || !is_string($deviceToken)) {
            return $this->setStatusCode(Response::HTTP_BAD_REQUEST)->respondWithErrors(
                $this->translator->trans('icapps.general.missing_field', ['%field%' => 'deviceToken'], 'messages')
            );
        }

        $device = $this->deviceRepository->findOneBy(['user' => $user, 'deviceId' => $deviceId]);
        if (!$device) {
            return $this->setStatusCode(Response::HTTP_BAD_REQUEST)->respondWithErrors(
                $this->translator->trans('icapps.user.token_update.not_found', [], 'messages')
            );
        }

        $device->setDeviceToken($deviceToken);
        try {
            $this->deviceRepository->save($device);
        } catch (OptimisticLockException | ORMException $e) {
            $this->logger->critical($e->getMessage());
            return $this->setStatusCode(Response::HTTP_BAD_REQUEST)->respondWithErrors(
                $this->translator->trans('icapps.user.token_update.failed', [], 'messages')
            );
        }

        return $this->setStatusCode(Response::HTTP_OK)->respondWithSuccess(
            $this->translator->trans('icapps.user.token_update.success', [], 'messages')
        );
    }

    /**
     * Resend registration email.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function resendRegistrationEmail(Request $request): JsonResponse
    {
        // Retrieve request body parameters.
        $request = $this->transformJsonBody($request);
        $email = $request->get('email');

        // success message
        $success = $this->translator->trans('icapps.mail.registration.resend', [], 'messages');

        $user = $this->userRepository->findOneBy(['email' => $email, 'profileType' => Profile::PROFILE_TYPE, 'enabled' => 0]);

        if (!$user) {
            //silent error
            $errors = $this->translator->trans('icapps.reset.email.not_found', [], 'validators');
            $this->logger->warning($errors);
            return $this->respondWithSuccess($success);
        }

        // Get profile.
        $profile = $this->profileRepository->findById($user->getProfileId());

        if (!$profile) {
            //silent error
            $errors = $this->translator->trans('icapps.general.no_profile_found', [], 'messages');
            $this->logger->warning($errors);
            return $this->respondWithSuccess($success);
        }

        $this->mailHelper->sendRegistrationActivationMail($user, $profile);
        // Log it.
        $this->logger->info(sprintf('Resend registration email requested [id: "%s", e-mail: "%s"]', $user->getId(), $user->getEmail()), ['user' => $user]);
        return $this->respondWithSuccess($success);
    }
}
