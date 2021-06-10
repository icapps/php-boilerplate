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
}
