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
