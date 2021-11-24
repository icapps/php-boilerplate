<?php

namespace App\Dto\User;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserDeviceDto
 *
 * All custom ApiResource responses should be included using a proper DTO.
 */
final class UserDeviceDto
{

    /**
     * @var string
     *
     * @Groups({"api-get"})
     *
     * @Assert\NotBlank()
     */
    public string $deviceSid = '';

    /**
     * @var string
     *
     * @Groups({"api-get", "api-post"})
     *
     * @Assert\NotBlank()
     */
    public string $deviceToken = '';

    public function __construct(string $deviceSid, string $deviceToken)
    {
        $this->deviceSid = $deviceSid;
        $this->deviceToken = $deviceToken;
    }
}
