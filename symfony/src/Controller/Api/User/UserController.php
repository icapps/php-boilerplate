<?php

namespace App\Controller\Api\User;

use App\Service\Api\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserController
 *
 * @package App\Controller\Api\User
 *
 * @Route("/api/users", name="icapps_api.user")
 */
class UserController extends AbstractController
{
    public function __construct(
        private TranslatorInterface $translator,
        private UserService $userService
    ) {
        //
    }

    /**
     * Following REST philosophy all requests for retrieving data should have unique URL's, ea: /user/{id}
     * We embrace this philosophy and provide following endpoint to retrieve active user info,
     * including necessary relationships.
     *
     * // @TODO:: dynamic routing?
     * @Route("/info", name=".info")
     *
     * @return Response
     */
    public function confirmationPendingEmail(): Response
    {
        return $this->userService->getActiveUserInfo();
    }
}
