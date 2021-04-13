<?php

declare(strict_types=1);

namespace App\Service\Api\Swagger;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use App\Dto\AuthAccessOutput;

final class SwaggerDecorator implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated)
    {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        // Bundle resources in Swagger.
        foreach ($openApi->getPaths()->getPaths() as $url => $pathItem) {
            if (str_contains($url, AuthAccessOutput::AUTH_ROUTE_PREFIX)) {
                $operation = $pathItem->getPost();
                $openApi->getPaths()->addPath($url, $pathItem->withPost(
                    $operation->withTags([AuthAccessOutput::AUTH_BUNDLE_TAG])
                ));
            }
        }

        return $openApi;
    }
}
