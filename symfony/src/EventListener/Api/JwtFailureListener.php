<?php

namespace App\EventListener\Api;

use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Symfony\Component\HttpFoundation\Response;

class JwtFailureListener implements EventSubscriberInterface
{
    /**
     * @param AuthenticationFailureEvent $event
     */
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event)
    {
        // Get exception.
        $exception = $event->getException();

        // Pass our exception message to output.
        $message = $exception->getMessage();

        // Retrieve correct status code, default 401.
        $statusCode = $exception->getCode() !== 0 ? $exception->getCode() : Response::HTTP_UNAUTHORIZED;

        // Set failure response.
        $response = new JWTAuthenticationFailureResponse($message, $statusCode);

        $event->setResponse($response);
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
