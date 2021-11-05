<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Exception\ApiException;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\PayloadAwareUserProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method UserInterface loadUserByIdentifierAndPayload(string $identifier, array $payload)
 * @method UserInterface loadUserByIdentifier(string $identifier)
 */
class UserProvider implements PayloadAwareUserProviderInterface
{

    /**
     * @var array
     */
    private array $cache = [];

    public function __construct(private UserRepository $userRepository, private RequestStack $requestStack)
    {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsernameAndPayload($email, array $payload)
    {
        return $this->loadUserByPayload($email, $payload);
    }

    /**
     * Load user by given payload.
     *
     * @param $email
     * @param array $payload
     * @return User|mixed
     */
    public function loadUserByPayload($email, array $payload)
    {
        // Check cache.
        if (isset($this->cache[$email])) {
            return $this->cache[$email];
        }

        // Get request.
        $request = $this->requestStack->getCurrentRequest();

        // Get route.
        $route = $request->get('_route');

        // Extra checks on login.
        if (str_contains($route, 'api_login')) {
            $requestParams = json_decode($request->getContent(), true);

            if (!isset($requestParams['deviceSid']) || !isset($requestParams['deviceToken'])) {
                throw new ApiException(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    sprintf('Both "%s" and "%s" must be provided.', 'deviceSid', 'deviceToken')
                );
            }
        }

        /** @var User $user */
        $user = $this->userRepository->findOneBy([
            'email' => $email,
        ]);

        // Check if exists.
        if (null === $user) {
            throw new UserNotFoundException('User not found');
        }

        // Check if enabled.
        if (!$user->isEnabled()) {
            throw new AuthenticationException('User not yet activated');
        }

        return $this->cache[$email] = $user;
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($email)
    {
        return $this->loadUserByUsernameAndPayload($email, []);
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class): bool
    {
        return User::class === $class;
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method UserInterface loadUserByIdentifierAndPayload(string $identifier, array $payload)
        // TODO: Implement @method UserInterface loadUserByIdentifier(string $identifier)
    }
}
