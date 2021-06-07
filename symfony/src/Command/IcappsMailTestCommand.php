<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Mail\MailHelper;

class IcappsMailTestCommand extends Command
{
    protected static $defaultName = 'icapps:mail-test';
    protected static $defaultDescription = 'Send a test E-mail';

    /**
     * IcappsMailTestCommand constructor.
     */
    public function __construct(
        private MailHelper $mailerHelper
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('to', InputArgument::REQUIRED, 'Delivery Address')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $to = $input->getArgument('to');

        if ($to) {
            $io->note(sprintf('Trying to send a test E-mail to: %s', $to));
            $this->mailerHelper->sendTestMail($to);
            $io->success(sprintf('You have send a test mail to %s! Wooow 🙌', $to));

            return Command::SUCCESS;
        }
        
        return Command::FAILURE;
    }
}
