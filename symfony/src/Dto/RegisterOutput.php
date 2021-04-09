<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RegisterOutput
 *
 * All custom ApiResource responses should be included using a proper DTO.
 *
 * @package App\Dto
 */
class RegisterOutput
{
    /**
     * @var string
     *
     * @Groups({"register:api-get"})
     *
     * @Assert\NotBlank
     */
    public string $firstName;

    /**
     * @var string
     *
     * @Groups({"register:api-get"})
     *
     * @Assert\NotBlank
     */
    public string $lastName;

    /**
     * @var string
     *
     * @Groups({"register:api-get"})
     *
     * @Assert\NotBlank
     */
    public string $email;

    /**
     * @var string
     *
     * @Groups({"register:api-get"})
     *
     * @Assert\NotBlank
     */
    public string $language;
}
