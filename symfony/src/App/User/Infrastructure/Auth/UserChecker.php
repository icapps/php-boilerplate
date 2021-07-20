<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Auth;

use App\Shared\Infrastructure\Bus\Command\CommandBus;
use App\User\Application\Command\SignIn\SignInCommand;
use App\User\Domain\Exception\InvalidPayloadException;
use App\User\Infrastructure\Doctrine\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private RequestStack $requestStack,
        private CommandBus $commandBus,
    ) {
        //
    }

    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        // Check authentication payload.
        // @TODO:: validate deviceId & deviceToken.
        $requestData = $this->getCurrentRequestPayload();
        if (!$requestData || !isset($requestData['deviceId']) || !isset($requestData['deviceToken'])) {
            throw new InvalidPayloadException(\sprintf(
                'User %s, %s, %s and %s are required for login.',
                'email',
                'password',
                'deviceId',
                'deviceToken'
            ));
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        // Check user enabled.
        if ($user->isEnabled()) {
            throw new AccountExpiredException('Account not yet activated');
        }

        // Dispatch SignInCommand.
        $this->commandBus->handle(new SignInCommand($user, $this->getCurrentRequestPayload()));
    }

    // @TODO:: private class/interface.
    private function getCurrentRequestPayload(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request && !empty($request->getContent())) {
            return json_decode($request->getContent(), true);
        } else {
            return [];
        }
    }
}
