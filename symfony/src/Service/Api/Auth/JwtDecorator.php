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
        $pathItem = new Model\PathItem(
            ref: 'JWT Token',
            post: new Model\Operation(
                operationId: 'postCredentialsItem',
                tags: ['Authentication'],
                responses: [
                    '200' => [
                        'description' => 'Get JWT tokens',
                        'content' => [
                            'application/json' => [
                                'schema'  => [
                                    'type' => 'object',
                                    'required' => ['accessToken', 'refreshToken'],
                                    'properties' =>
                                        [
                                            'accessToken' => ['type' => 'string'],
                                            'refreshToken' => ['type' => 'string'],
                                        ],
                                ],
                                'example' => [
                                    'accessToken' => '97f1796cee6a319cbf42623d168ec7d030e1cc6658f01da884e8d59b368deda0e9f977b80cf19aedb3d6b43d8a4',
                                    'refreshToken' => '01645a2f86313ffc55332747054a7ad3ce6f497f1796cee6a319cbf42623d1',
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
                            'schema'  => [
                                'type' => 'object',
                                'required' => ['email', 'password', 'deviceId', 'deviceToken'],
                                'properties' =>
                                    [
                                        'email' => ['type' => 'string'],
                                        'password' => ['type' => 'string'],
                                        'deviceId' => ['type' => 'string'],
                                        'deviceToken' => ['type' => 'string'],
                                    ],
                            ],
                            'example' => [
                                'email' => 'info@example.com',
                                'password' => '123456789',
                                'deviceId' => '1234567890',
                                'deviceToken' => '1234567890',
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
