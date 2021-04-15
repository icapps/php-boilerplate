<?php

namespace App\Service\Api\DataPersister\Examples;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\ApiResource\Examples\SearchExample;
use App\Dto\SearchExampleDto;

/**
 * Class SearchExampleDataPersister
 *
 * This is a custom DataPersister for which incoming data can be handled, persisted and customized in any way.
 * More information: https://api-platform.com/docs/core/data-persisters.
 *
 * @package App\Service\Api\DataPersister\Examples
 */
final class SearchExampleDataPersister implements DataPersisterInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports($data): bool
    {
        return $data instanceof SearchExample;
    }

    /**
     * {@inheritDoc}
     */
    public function persist($data)
    {
        // Create response.
        $output = new SearchExampleDto();

        /** @var SearchExample $data */
        $output->title = 'Search result for: ' . $data->getKeyword();
        $output->description = 'This is a search result description.';

        return $output;
    }

    /**
     * {@inheritDoc}
     */
    public function remove($data)
    {
        // this method just need to be presented
    }
}
