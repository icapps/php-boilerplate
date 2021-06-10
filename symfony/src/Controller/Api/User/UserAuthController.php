<?php

namespace App\Controller\Api\User;

use App\Controller\Api\General\ApiController;
use App\Entity\Profile;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @package App\Controller\Api\Client
 * @Route("/api/v1/customers/auth", name="wac_api.client.auth")
 */
class UserAuthController extends ApiController
{
// TODO: convert them to Api Resource with DTO
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
