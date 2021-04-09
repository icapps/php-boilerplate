<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\PayloadAwareUserProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProvider implements PayloadAwareUserProviderInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var array
     */
    private $cache = [];

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(UserRepository $userRepository, RequestStack $requestStack)
    {
        $this->userRepository = $userRepository;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsernameAndPayload($email, array $payload)
    {
        // @TODO:: provide profile type.
        return $this->loadUserByPayload($email, $payload, 'default');
    }

    /**
     * Load user by given payload.
     *
     * @param $email
     * @param array $payload
     * @param string $profileType
     *
     * @return User|mixed
     */
    public function loadUserByPayload($email, array $payload, string $profileType)
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
        if (strpos($route, 'api_login') !== false) {
            $requestParams = json_decode($request->getContent(), true);

            if (!isset($requestParams['deviceId']) || !isset($requestParams['deviceToken'])) {
                throw new BadRequestHttpException(
                    sprintf('The "%s" and "%s" must be provided.', 'deviceId', 'deviceToken')
                );
            }
        }

        /** @var User $user */
        // @TODO:: include profile type.
        $user = $this->userRepository->findOneBy([
            'email' => $email,
            //'profileType' => $profileType,
        ]);

        // Check if exists.
        if (null === $user) {
            throw new UsernameNotFoundException();
        }

        // Check if enabled.
        if (!$user->isEnabled()) {
            throw new AuthenticationException();
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
    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
