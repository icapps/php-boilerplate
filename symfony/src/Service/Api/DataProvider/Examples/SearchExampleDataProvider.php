<?php

namespace App\Service\Api\DataProvider\Examples;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\ApiResource\Examples\SearchExample;
use App\Dto\SearchExampleDto;

/**
 * Class SearchExampleDataProvider
 *
 * This is a custom DataProvider for which getItem and getCollection can be customized to retrieve data.
 * More information: https://api-platform.com/docs/core/data-providers.
 *
 * @package App\Service\Api\DataProvider\Examples
 */
final class SearchExampleDataProvider implements ItemDataProviderInterface, ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        // Construct.
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === SearchExample::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?SearchExampleDto
    {
        $result = new SearchExampleDto();
        $result->title = 'Test item #1';
        $result->description = 'Test detail item';

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $result = new SearchExampleDto();
        $result->title = 'Test item #1';
        $result->description = 'Test collection items';

        return [$result];
    }
}
