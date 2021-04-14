<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class StatusOutput
 *
 * All custom ApiResource responses should be included using a proper DTO.
 *
 * @package App\Dto
 */
class StatusOutput
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
