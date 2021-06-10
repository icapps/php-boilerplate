<?php

namespace App\Controller\Api\User;

use App\Controller\Api\General\ApiController;
use App\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as SWG;

/**
 * Class ClientProfileController
 * @package App\Controller\Api\User
 * @Route("/api/customers/profile", name="wac_api.profile.user")
 */
class UserProfileController extends ApiController
{
// TODO: convert them to Api Resource with DTO
    /**
     * Get user profile.
     *
     * @SWG\Response(response=200, description="Contains user profile", @SWG\Schema(ref=@Model(type=App\JsonInputs\ClientProfile::class)))
     * @SWG\Response(response=401, description="Invalid credentials", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusMessage::class)))
     *
     * @SWG\Tag(name="Profile")
     * @Security(name="Bearer")
     *
     * @return JsonResponse
     * @Route("", name="", methods={"GET"})
     */
    public function getProfile()
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->userProfileService->getProfile($user);
    }

    /**
     * Update user profile.
     *
     * @SWG\Response(response=200, description="Update successfull", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusMessage::class)))
     * @SWG\Response(response=400, description="Validation error", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusMessage::class)))
     * @SWG\Response(response=401, description="Invalid credentials", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusMessage::class)))
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     type="json",
     *     required=true,
     *     @SWG\Property(
     *      type="object",
     *      properties={
     *          @SWG\Property(property="picture", type="string", description="A base64 encoded image, formatted like: data:image/jpeg;base64,..."),
     *          @SWG\Property(property="firstName", type="string", maxLength=50),
     *          @SWG\Property(property="lastName", type="string", maxLength=50),
     *          @SWG\Property(property="email", type="string", maxLength=50),
     *          @SWG\Property(property="language", type="string", enum=User::LANGUAGES, maxLength=2),
     *          @SWG\Property(
     *              property="telephone",
     *              type="object",
     *              required={"countryCode", "phoneNumber"},
     *              properties={
     *                  @SWG\Property(property="countryCode", type="string"),
     *                  @SWG\Property(property="phoneNumber", type="string", maxLength=30),
     *              }
     *          )
     *     }
     *   )
     * )
     *
     * @SWG\Tag(name="Profile")
     * @Security(name="Bearer")
     *
     * @param Request $request
     * @return JsonResponse
     * @Route("", name=".update", methods={"PATCH"})
     */
    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->userProfileService->updateProfile($user, $request);
    }

    /**
     * Update user password.
     *
     * @SWG\Response(response=200, description="Update successfull", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusMessage::class)))
     * @SWG\Response(response=400, description="Validation error", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusMessage::class)))
     * @SWG\Response(response=422, description="Unmet password requirements", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusMessage::class)))
     * @SWG\Response(response=401, description="Invalid credentials", @SWG\Schema(ref=@Model(type=App\JsonInputs\StatusMessage::class)))
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     type="json",
     *     required=true,
     *     @SWG\Property(
     *      type="object",
     *      required={"oldPassword", "newPassword"},
     *      properties={
     *          @SWG\Property(property="oldPassword", type="string", maxLength=255, minLength=8),
     *          @SWG\Property(property="newPassword", type="string", maxLength=255, minLength=8),
     *     }
     *   )
     * )
     *
     * @SWG\Tag(name="Profile")
     * @Security(name="Bearer")
     *
     * @param Request $request
     * @return JsonResponse
     * @Route("/change-password", name=".update-password", methods={"POST"})
     */
    public function updatePassword(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->userProfileService->updatePassword($user, $request);
    }
}
