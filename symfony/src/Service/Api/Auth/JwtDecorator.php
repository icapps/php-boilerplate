<?php

declare(strict_types=1);

namespace App\Service\Api\Auth;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model;
use ApiPlatform\Core\OpenApi\OpenApi;

final class JwtDecorator implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated)
    {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $schemas = $openApi->getComponents()->getSchemas();

        $schemas['Token'] = new \ArrayObject(
            [
                'type' => 'object',
                'properties' => [
                    'token' => [
                        'type' => 'string',
                        'readOnly' => true,
                    ],
                ],
            ]
        );
        $schemas['Credentials'] = new \ArrayObject(
            [
                'type' => 'object',
                'required' => ['email', 'password', 'deviceId', 'deviceToken'],
                'properties' => [
                    'email' => [
                        'type' => 'string',
                        'example' => 'john@doe.com',
                    ],
                    'password' => [
                        'type' => 'string',
                        'example' => 'test123',
                    ],
                    'deviceId' => [
                        'type' => 'string',
                        'example' => '123456789',
                    ],
                    'deviceToken' => [
                        'type' => 'string',
                        'example' => '123456789',
                    ],
                ],
            ]
        );

        $pathItem = new Model\PathItem(
            ref: 'JWT Token',
            post: new Model\Operation(
                operationId: 'postCredentialsItem',
                tags: ['Authentication'],
                responses: [
                    '200' => [
                        'description' => 'Get JWT token',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token',
                                ],
                            ],
                        ],
                    ],
                    '400' => [
                        'description' => 'Validation error',
                    ],
                ],
                summary: 'Get JWT login token',
                requestBody: new Model\RequestBody(
                    description: 'Generate new JWT Token',
                    content: new \ArrayObject(
                        [
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials',
                            ],
                        ],
                        ]
                    ),
                ),
            ),
        );
        $openApi->getPaths()->addPath('/api/auth/login', $pathItem);

        return $openApi;
    }
}
