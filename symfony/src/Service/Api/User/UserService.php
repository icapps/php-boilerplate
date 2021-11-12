<?php

namespace App\Service\Api\User;

use App\Dto\User\UserInfoDto;
use App\Repository\UserRepository;
use App\Service\Api\General\ApiService;
use App\Utils\UuidEncoder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use Symfony\Component\Security\Core\Security;

/**
 * Class UserService
 */
class UserService extends ApiService
{
    public function __construct(
        private UserRepository $userRepository,
        private Security $security
    ) {
        //
    }

    /**
     * GET active user info.
     *
     * @return JsonResponse
     */
    public function getActiveUserInfo(): JsonResponse
    {
        // Not authenticated.
        if (!$this->security->getUser()) {
            throw new InsufficientAuthenticationException();
        }

        // Retrieve user.
        $user = $this->userRepository->find($this->security->getUser());

        // Not activated.
        if (!$user || !$user->isEnabled()) {
            throw new InsufficientAuthenticationException();
        }

        // Output.
        $output = new UserInfoDto();
        $output->userSid = UuidEncoder::encode($user->getUuid());
        $output->email = $user->getEmail();

        return $this->response($output);
    }
}
