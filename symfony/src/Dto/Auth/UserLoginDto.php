<?php

namespace App\Dto\Auth;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserLoginDto
 *
 * All custom ApiResource responses should be included using a proper DTO.
 */
final class UserLoginDto
{
    /**
     * @var null|string
     *
     * @Groups({"auth:api-post"})
     *
     * @Assert\NotBlank()
     *
     * @Assert\Email(
     *     message="icapps.registration.email.invalid",
     * )
     * @Assert\Length(
     *     min = 1,
     *     max = 50,
     *     minMessage="icapps.registration.email.min_length",
     *     maxMessage="icapps.registration.email.max_length"
     * )
     */
    public ?string $email;

    /**
     * @var null|string
     *
     * @Groups({"auth:api-post"})
     *
     * @Assert\NotBlank()
     *
     * @Assert\Length(
     *     min = 1,
     *     max = 50,
     *     minMessage="icapps.registration.password.min_length",
     *     maxMessage="icapps.registration.password.max_length"
     * )
     */
    public ?string $password;

    /**
     * @var null|string
     *
     * @Groups({"auth:api-post"})
     *
     * @Assert\NotBlank()
     */
    public ?string $deviceSid;

    /**
     * @var null|string
     *
     * @Groups({"auth:api-post"})
     *
     * @Assert\NotBlank()
     */
    public ?string $deviceToken;

    /**
     * Create UserLoginDto from given parameters.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->email = $parameters['email'] ?? null;
        $this->password = $parameters['password'] ?? null;
        $this->deviceSid = $parameters['deviceSid'] ?? null;
        $this->deviceToken = $parameters['deviceToken'] ?? null;
    }
}
