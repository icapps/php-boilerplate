<?php

namespace App\EventListener\Api;

use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class JwtFailureListener implements EventSubscriberInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param RequestStack $requestStack
     * @param UserRepository $userRepository
     */
    public function __construct(RequestStack $requestStack, UserRepository $userRepository)
    {
        $this->requestStack = $requestStack;
        $this->userRepository = $userRepository;
    }

    /**
     * @param AuthenticationFailureEvent $event
     */
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event)
    {
        // Default info code.
        $code = null;

        // Default status: unauthorized.
        $statusCode = 401;

        // Get exception.
        $exception = $event->getException();
        if ($exception->getPrevious() instanceof BadRequestHttpException) {
            $statusCode = 400;
        }

        if ($exception->getPrevious() instanceof UsernameNotFoundException) {
            $statusCode = 404;
        }

        if ($exception->getPrevious() instanceof AuthenticationException) {
            $statusCode = 403;
        }

        // Set failure response.
        $data = [
            'status' => $statusCode,
            'message' => $exception->getMessage(),
        ];

        $event->setResponse(new JsonResponse($data, $statusCode));
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'lexik_jwt_authentication.on_authentication_failure' => 'onAuthenticationFailureResponse',
        ];
    }
}
