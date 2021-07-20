<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Command;

use App\Shared\Infrastructure\Bus\MessageBusExceptionTrait;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class CommandBus implements CommandBusInterface
{
    use MessageBusExceptionTrait;

    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messengerCommandBus)
    {
        $this->messageBus = $messengerCommandBus;
    }

    public function handle(CommandInterface $command): void
    {
        try {
            $this->messageBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            $this->throwException($e);
        }
    }

    public function handleAndReturnResult(CommandInterface $command)
    {
        try {
            $envelope = $this->messageBus->dispatch($command);

            /** @var HandledStamp $stamp */
            $stamp = $envelope->last(HandledStamp::class);

            return $stamp->getResult();
        } catch (HandlerFailedException $e) {
            $this->throwException($e);
        }
    }
}
