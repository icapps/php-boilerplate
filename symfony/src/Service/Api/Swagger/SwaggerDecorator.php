<?php

declare(strict_types=1);

namespace App\Service\Api\Swagger;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model;
use App\Dto\Auth\AuthAccessDto;
use App\Utils\AuthUtils;
use Ramsey\Uuid\Uuid;

final class SwaggerDecorator implements OpenApiFactoryInterface
{
    public function __construct(
        private OpenApiFactoryInterface $decorated
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);
        $schemas = $openApi->getComponents()->getSchemas();

        // Include Auth Me DTO.
        // @TODO:: can't we include \DTO\User\UserInfoDto()?
        $schemas['Authentication.UserInfoDto-GET'] = new \ArrayObject([
            'type' => 'object',
            'required' => ['userSid', 'email'],
            'properties' => [
                'userSid' => [
                    'type' => 'string',
                ],
                'email' => [
                    'type' => 'string',
                    'format' => 'email',
                ],
            ],
        ]);

        $openApi->getPaths()->addPath(
            path: AuthAccessDto::AUTH_ME_URL,
            pathItem: new Model\PathItem(
                ref: 'Get user info',
                get: new Model\Operation(
                    operationId: 'getActiveUserInformation',
                    tags: [AuthAccessDto::AUTH_BUNDLE_TAG],
                    responses: [
                        '200' => [
                            'description' => 'Authenticated user info',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/Authentication.UserInfoDto-GET',
                                    ],
                                ],
                            ],
                        ],
                        '401' => [
                            'description' => 'User not authenticated',
                        ]
                    ],
                    summary: 'Get authenticated user info',
                )
            )
        );

        // Include JWT DTOs.
        // @TODO:: can't we include \DTO\Auth\AuthAccessDto()?
        $schemas['Authentication.UserLoginDto-GET'] = new \ArrayObject([
            'type' => 'object',
            'required' => ['token', 'refreshToken'],
            'properties' => [
                'token' => [
                    'type' => 'string',
                ],
                'refreshToken' => [
                    'type' => 'string',
                ],
            ],
        ]);

        // @TODO:: can't we include \DTO\Auth\UserLoginDto()?
        $schemas['Authentication.UserLoginDto-POST'] = new \ArrayObject([
            'type' => 'object',
            'required' => ['email', 'password', 'deviceSid', 'deviceToken'],
            'properties' => [
                'email' => [
                    'type' => 'string',
                    'format' => 'email',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => AuthUtils::getUniqueToken(10),
                ],
                'deviceSid' => [
                    'type' => 'string',
                    'example' => Uuid::uuid4()->toString(),
                ],
                'deviceToken' => [
                    'type' => 'string',
                    'example' => AuthUtils::getUniqueToken(),
                ],
            ],
        ]);

        $openApi->getPaths()->addPath(
            path: AuthAccessDto::AUTH_LOGIN_URL,
            pathItem: new Model\PathItem(
                ref: 'JWT Token',
                post: new Model\Operation(
                    operationId: 'postCredentialsItem',
                    tags: ['Authentication'],
                    responses: [
                        '200' => [
                            'description' => 'Get JWT tokens',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/Authentication.UserLoginDto-GET',
                                    ],
                                ],
                            ],
                        ],
                        '400' => [
                            'description' => 'Invalid input',
                        ],
                        '401' => [
                            'description' => 'Authentication error',
                        ],
                        '403' => [
                            'description' => 'User not activated',
                        ],
                        '404' => [
                            'description' => 'User not found',
                        ],
                        '422' => [
                            'description' => 'Unprocessable entity',
                        ]
                    ],
                    summary: 'Get JWT login token',
                    requestBody: new Model\RequestBody(
                        description: 'Generate new JWT Token',
                        content: new \ArrayObject(
                            [
                                'application/json' => [
                                    'schema'  => [
                                        '$ref' => '#/components/schemas/Authentication.UserLoginDto-POST',
                                    ],
                                ],
                            ]
                        ),
                    ),
                ),
            )
        );

        // Bundle resources in Swagger.
        foreach ($openApi->getPaths()->getPaths() as $url => $pathItem) {
            // Bundle authentication requests.
            if (str_contains($url, AuthAccessDto::AUTH_ROUTE_PREFIX)) {
                if ($operation = $pathItem->getPost()) {
                    $openApi->getPaths()->addPath($url, $pathItem->withPost(
                        $operation->withTags([AuthAccessDto::AUTH_BUNDLE_TAG])
                    ));
                }
            }
        }

        return $openApi;
    }
}
