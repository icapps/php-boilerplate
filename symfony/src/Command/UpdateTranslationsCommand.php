<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UpdateTranslationsCommand extends Command
{
    protected static $defaultName = 'icapps:update-translations';

    public const PROJECT_LANGUAGES = ['nl', 'en', 'fr'];

    /**
     * {@inheritDoc}
     */
    public function __construct(
        private LoggerInterface $logger,
        private HttpClientInterface $httpClient
    ) {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->setDescription('Update translations from icapps translation tool.');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->note('Update translations started');

        // Update translations.
        $this->updateTranslations();

        $io->success('Update translations finished');
        return Command::SUCCESS;
    }

    /**
     * Update translations from icapps translation tool.
     */
    private function updateTranslations(): void
    {
        foreach (self::PROJECT_LANGUAGES as $language) {
            $endpoint = $_ENV['ICAPPS_TRANSLATIONS_TOOL'] . $language . '.json';

            try {
                // Retrieve translations from translation tool endpoint.
                $response = $this->httpClient->request('GET', $endpoint, [
                    'headers' => [
                        'Authorization' => 'Token ' . $_ENV['ICAPPS_TRANSLATIONS_TOKEN'],
                    ],
                ]);

                // Decode results.
                $content = json_decode($response->getContent(), true);

                // Update translation files if necessary.
                if (isset($content['translations']) && !empty($content['translations'])) {
                    file_put_contents('translations/icapps/messages.' . $language . '.json', json_encode($content['translations']));
                }
            } catch (TransportExceptionInterface $e) {
                $this->logger->critical('Could not download translations from icapps translation tool');
            }
        }
    }
}
