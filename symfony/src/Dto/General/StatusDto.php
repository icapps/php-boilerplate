<?php

namespace App\Dto\General;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class StatusDto
 *
 * All custom ApiResource responses should be included using a proper DTO.
 *
 * @package App\Dto
 */
class StatusDto
{
    /**
     * @var int
     *
     * @Groups({"api-get"})
     */
    public int $code = 200;

    /**
     * @var string
     *
     * @Groups({"api-get"})
     *
     * @Assert\NotBlank
     */
    public string $message = '';
}
