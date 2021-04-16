<?php

declare(strict_types=1);

namespace App\Service\Api\Swagger;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model;
use App\Dto\Auth\AuthAccessDto;

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
            if (str_contains($url, AuthAccessDto::AUTH_ROUTE_PREFIX)) {
                $operation = $pathItem->getPost();
                $openApi->getPaths()->addPath($url, $pathItem->withPost(
                    $operation->withTags([AuthAccessDto::AUTH_BUNDLE_TAG])
                ));
            }
        }

        // Include user info endpoint.
        $pathItem = new Model\PathItem(
            'User info',
            'Get active user info',
            'Get active user info and relations',
            new Model\Operation(
                'getActiveUserInformation',
                ['User'],
                [
                    '200' => [
                        'description' => 'Get active user info',
                        'content' => [
                            'application/json' => [
                                'schema'  => [
                                    'type' => 'object',
                                    'required' => ['userId', 'email', 'profileId'],
                                    'properties' =>
                                        [
                                            'userId' => ['type' => 'integer'],
                                            'email' => ['type' => 'string'],
                                            'profileId' => ['type' => 'integer'],
                                        ],
                                ],
                            ],
                        ],
                    ],
                    '400' => [
                        'description' => 'Validation error',
                    ],
                    '401' => [
                        'description' => 'Unauthorized',
                    ],
                    '403' => [
                        'description' => 'User not activated',
                    ],
                    '404' => [
                        'description' => 'User not found',
                    ],
                ]
            )
        );

        // @TODO:: dynamic routing?
        $openApi->getPaths()->addPath('/api/users/info', $pathItem);

        return $openApi;
    }
}
