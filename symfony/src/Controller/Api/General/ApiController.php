<?php

namespace App\Controller\Api\General;

use App\Service\Api\General\ApiService;
use App\Service\Api\User\UserProfileService;
use App\Service\Api\User\UserService;
use App\Utils\ProfileHelper;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\View\ViewHandlerInterface;
use Sulu\Component\Rest\AbstractRestController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ApiController extends AbstractRestController
{

    public function __construct(
        protected ApiService $apiService,
        protected ViewHandlerInterface $viewHandler,
        protected EntityManagerInterface $entityManager,
        protected TranslatorInterface $translator,
        protected ?TokenStorageInterface $tokenStorage = null,
        protected ProfileHelper $profileHelper,
        protected UserProfileService $userProfileService,
        protected UserService $userService,
    ) {
        parent::__construct($viewHandler, $tokenStorage);
    }
}
