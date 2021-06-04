<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class IcappsFirebaseTestCommand extends Command
{
    protected static $defaultName = 'icapps:firebase-test';
    protected static $defaultDescription = 'Test Firebase Notifications';

    /**
     * @var ChatterInterface
     */
//    protected $notifier;

    /**
     * IcappsFirebaseTestCommand constructor.
     * @param ChatterInterface $notifier
     */
    /*public function __construct(ChatterInterface $notifier)
    {
        parent::__construct();
        $this->notifier = $notifier;
    }*/

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('message', InputArgument::OPTIONAL, 'Notification message')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $message = $input->getArgument('message');

        if (empty($message)) {
            $message = 'Sending a Firebase Notification from Icapps <3';
        }

        $io->note(sprintf('You are sending a notification to Firebase : %s', $message));
        // TODO: figure out how we can test this?
        //$chat = new ChatMessage($message);
        //$this->notifier->send($chat);

        $io->success("Notification sent, please check Firebase! Good job! Now it's time for coffee ;)");

        return Command::SUCCESS;
    }
}
