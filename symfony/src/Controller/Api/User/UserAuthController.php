<?php

namespace App\Controller\Api\User;

use App\Controller\Api\General\ApiController;
use App\Entity\Profile;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as SWG;

/**
 * @package App\Controller\Api\Client
 * @Route("/api/v1/customers/auth", name="wac_api.client.auth")
 */
class UserAuthController extends ApiController
{

    /**
     * Check e-mail availability.
     *
     * @Route("/email-available", name=".email-validation", methods={"POST"})
     *
     * @SWG\Response(response=200, description="Email available", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusSuccess::class)))
     * @SWG\Response(response=422, description="Email is not available", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusMessage::class)))
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     type="json",
     *     required=true,
     *     @SWG\Property(
     *      type="object",
     *      required={"email"},
     *      properties={
     *          @SWG\Property(property="email", type="string", maxLength=50),
     *     }
     *   )
     * )
     * @SWG\Tag(name="Authentication")
     * @Security(name="")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function emailValidation(Request $request)
    {
        return $this->authService->emailValidation($request);
    }

    /**
     * Register new App user.
     *
     * @Route("/register", name=".register", methods={"POST"})
     *
     * @SWG\Response(response=200, description="Successfully registered", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusSuccess::class)))
     * @SWG\Response(response=422, description="Validation error", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusMessage::class)))
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     type="json",
     *     required=true,
     *     @SWG\Property(
     *      type="object",
     *      required={"firstName", "lastName", "email", "telephone", "password"},
     *      properties={
     *          @SWG\Property(property="firstName", type="string", maxLength=50),
     *          @SWG\Property(property="lastName", type="string", maxLength=50),
     *          @SWG\Property(property="email", type="string", maxLength=50),
     *          @SWG\Property(property="password", type="string", maxLength=255, minLength=8),
     *          @SWG\Property(property="language", type="string", enum=User::LANGUAGES, maxLength=2),
     *     }
     *   )
     * )
     * @SWG\Tag(name="Authentication")
     * @Security(name="")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     *
     * @return JsonResponse
     */
    public function registerUser(Request $request, UserPasswordEncoderInterface $encoder)
    {
        return $this->authService->register($request, $encoder);
    }

    /**
     * Login App user.
     *
     * @Route("/login", name=".login", methods={"POST"})
     *
     * @SWG\Response(response=200, description="Successful login", @SWG\Schema(ref=@Model(type=App\JsonInputs\UserLoginSuccessful::class)))
     * @SWG\Response(response=400, description="Bad request", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusMessage::class)))
     * @SWG\Response(response=401, description="Invalid credentials", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusMessage::class)))
     * @SWG\Response(response=403, description="User not activated", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusMessage::class)))
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     type="json",
     *     required=true,
     *     @SWG\Property(
     *      type="object",
     *      required={"email", "password", "deviceId", "deviceToken"},
     *      properties={
     *          @SWG\Property(property="email", type="string", maxLength=50),
     *          @SWG\Property(property="password", type="string", maxLength=255, minLength=8),
     *          @SWG\Property(property="deviceId", type="string"),
     *          @SWG\Property(property="deviceToken", type="string"),
     *     }
     *   )
     * )
     * @SWG\Tag(name="Authentication")
     * @Security(name="")
     *
     */
    public function loginUser()
    {
        // Triggers the JWT authenticator.
    }

    /**
     * Refresh user token.
     *
     * @Route("/refresh", name=".refresh", methods={"POST"})
     *
     * @SWG\Response(response=200, description="Successfully refreshed token", @SWG\Schema(ref=@Model(type=App\JsonInputs\UserLoginSuccessful::class)))
     * @SWG\Response(response=401, description="Invalid credentials", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusMessage::class)))
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     type="json",
     *     required=true,
     *     @SWG\Property(
     *      type="object",
     *      required={"refreshToken"},
     *      properties={
     *          @SWG\Property(property="refreshToken", type="string"),
     *     }
     *   )
     * )
     * )
     * @SWG\Tag(name="Authentication")
     * @Security(name="")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function refreshUserToken(Request $request)
    {
        return $this->authService->refreshToken($request);
    }

    /**
     * Password reset user.
     *
     * @Route("/password-reset", name=".password_reset", methods={"POST"})
     *
     * @SWG\Response(response=200, description="User password reset", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusSuccess::class)))
     * @SWG\Response(response=400, description="User not found", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusMessage::class)))
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     type="json",
     *     required=true,
     *     @SWG\Property(
     *      type="object",
     *      required={"email"},
     *      properties={
     *          @SWG\Property(property="email", type="string", maxLength=50),
     *     }
     *   )
     * )
     * @SWG\Tag(name="Authentication")
     * @Security(name="")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function userPasswordReset(Request $request)
    {
        return $this->authService->requestUserPasswordReset($request, Profile::PROFILE_TYPE);
    }

    /**
     * Logout App user.
     *
     * @Route("/logout", name=".logout", methods={"POST"})
     *
     * @SWG\Response(response=200, description="Successful logout", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusMessage::class)))
     * @SWG\Response(response=400, description="Bad request", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusMessage::class)))
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     type="json",
     *     required=true,
     *     @SWG\Property(
     *      type="object",
     *      required={"deviceId"},
     *      properties={
     *          @SWG\Property(property="deviceId", type="string"),
     *     }
     *   )
     * )
     * @SWG\Tag(name="Authentication")
     * @Security(name="Bearer")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logoutUser(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->authService->logout($user, $request);
    }

    /**
     * Update device token App user.
     *
     * @Route("/devicetoken", name=".devicetoken", methods={"PATCH"})
     *
     * @SWG\Response(response=200, description="Token successful updated", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusMessage::class)))
     * @SWG\Response(response=400, description="Bad request", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusMessage::class)))
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     type="json",
     *     required=true,
     *     @SWG\Property(
     *      type="object",
     *      required={"deviceId", "deviceToken"},
     *      properties={
     *          @SWG\Property(property="deviceId", type="string"),
     *          @SWG\Property(property="deviceToken", type="string"),
     *     }
     *   )
     * )
     * @SWG\Tag(name="Authentication")
     * @Security(name="Bearer")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateDeviceToken(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->authService->updateDeviceToken($user, $request);
    }

    /**
     * Resend registration email
     *
     * @Route("/resend-email", name=".resend-email", methods={"POST"})
     *
     * @SWG\Response(response=200, description="Email sent", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusSuccess::class)))
     * @SWG\Response(response=422, description="Email is not available", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusMessage::class)))
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     type="json",
     *     required=true,
     *     @SWG\Property(
     *      type="object",
     *      required={"email"},
     *      properties={
     *          @SWG\Property(property="email", type="string", maxLength=50),
     *     }
     *   )
     * )
     * @SWG\Tag(name="Authentication")
     * @Security(name="")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function resendRegistrationEmail(Request $request)
    {
        return $this->authService->resendRegistrationEmail($request);
    }
}
