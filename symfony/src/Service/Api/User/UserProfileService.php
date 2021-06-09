<?php

namespace App\Service\Api\User;

use App\Component\Model\ProfileInterface;
use App\Entity\Profile;
use App\Entity\User;
use App\Service\Api\General\ApiService;
use App\Utils\ProfileHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserProfileService
 */
class UserProfileService extends ApiService
{

    public function __construct(
        private TranslatorInterface $translator,
        private ProfileHelper $profileHelper
    ) {
    }

    /**
     * Profile access.
     *
     * @param User $user
     *
     * @return null|ProfileInterface
     */
    private function checkProfileAccess(User $user)
    {
        // Check active.
        if (!$user->isEnabled()) {
            return null;
        }

        $profile = $this->profileHelper->getProfile($user);

        // Check profile.
        if (!$profile || $user->getProfileType() !== Profile::PROFILE_TYPE) {
            return null;
        }

        return $profile;
    }


    /**
     * GET user profile.
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function getProfile(User $user)
    {
        $profile = $this->checkProfileAccess($user);

        if (!$profile) {
            $errors = $this->translator->trans('icapps.profile.unauthorized', [], 'validators');
            return $this->respondUnauthorized($errors);
        }

        return $this->response([
            'firstName' => $profile->getFirstName(),
            'lastName' => $profile->getLastName(),
            'email' => $user->getEmail(),
            'language' => $user->getLanguage(),
        ]);
    }

    /**
     * Update user profile.
     *
     * @param User $user
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateProfile(User $user, Request $request)
    {
        $profile = $this->checkProfileAccess($user);

        if (!$profile) {
            $errors = $this->translator->trans('icapps.profile.unauthorized', [], 'validators');
            return $this->respondUnauthorized($errors);
        }

        // Retrieve PATCH data.
        $request = $this->transformJsonBody($request);
        return $this->profileHelper->updateProfileFields($request, $profile, $user);
    }

    /**
     * Update user password.
     *
     * @param User $user
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updatePassword(User $user, Request $request)
    {
        $profile = $this->checkProfileAccess($user);

        if (!$profile) {
            $errors = $this->translator->trans('icapps.profile.unauthorized', [], 'validators');
            return $this->respondUnauthorized($errors);
        }

        // Retrieve POST data.
        $request = $this->transformJsonBody($request);
        return $this->profileHelper->updatePassword($request, $profile, $user);
    }
}
