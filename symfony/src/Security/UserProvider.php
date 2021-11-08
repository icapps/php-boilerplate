<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Exception\ApiException;
use App\Exception\UserNotActivatedException;
use App\Exception\UserNotFoundException;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\PayloadAwareUserProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    public function __construct(
        private UserRepository $userRepository,
        private RequestStack $requestStack,
        private ValidatorInterface $validator
    ) {
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
     *
     * @return mixed
     */
    public function loadUserByPayload($email, array $payload): mixed
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

            // Validate email.
            $errors = $this->validator->validate($email, new Assert\Email());
            if ($errors->count()) {
                throw new ApiException(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    sprintf('The provided email "%s" is invalid.', $email)
                );
            }

            // Validate device.
            // @TODO:: validate deviceSid and deviceToken?
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
        if (!$user) {
            throw new UserNotFoundException('User not found');
        }

        // Check if enabled.
        if (!$user->isEnabled()) {
            throw new UserNotActivatedException('User not yet activated');
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
