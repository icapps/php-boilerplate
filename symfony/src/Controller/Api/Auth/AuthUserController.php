<?php

namespace App\Controller\Api\Auth;

use App\Service\Api\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AuthUserController
 *
 * @Route("/api/auth", name="icapps_api.auth")
 */
class AuthUserController extends AbstractController
{
    public function __construct(
        private UserService $userService
    ) {
        //
    }

    /**
     * Following REST philosophy all requests for retrieving data should have unique URL's, ea: /user/{userSid}
     * We embrace this philosophy and provide following endpoint to retrieve active user info,
     * including necessary relationships.
     *
     * @Route("/me", name=".me")
     *
     * @return Response
     */
    public function getAuthenticatedUserInfo(): Response
    {
        return $this->userService->getActiveUserInfo();
    }
}
