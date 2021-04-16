<?php

namespace App\Service\Api\User;

use App\Repository\UserRepository;
use App\Service\Api\General\ApiService;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $user = $this->userRepository->find($this->security->getUser());
        return $this->response([
            'userId' => $user->getId(),
            'email' => $user->getEmail(),
        ]);
    }
}
