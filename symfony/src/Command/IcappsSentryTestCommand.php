<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class IcappsSentryTestCommand extends Command
{
    protected static $defaultName = 'icapps:sentry-test';
    protected static $defaultDescription = 'Test the sentry DSN connection, sends a test log.';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * IcappsSentryTestCommand constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        parent::__construct();
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('message', InputArgument::OPTIONAL, 'Log message')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $message = $input->getArgument('message');

        if (empty($message)) {
            $message = "Hello Sentry from Icapps <3";
        }

        $io->note(sprintf('Sending log with message: %s', $message));
        $this->testLog($message);

        $io->success("Sentry log has been sent, check sentry.io platform. Good job! Now let's have coffee ;)");

        return Command::SUCCESS;
    }

    public function testLog(string $message)
    {
        // the following code will test if monolog integration logs to sentry
        $this->logger->error($message);

        // the following code will test if an uncaught exception exception logs to sentry
        throw new \RuntimeException($message);
    }
}
