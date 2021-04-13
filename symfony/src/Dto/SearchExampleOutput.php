<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SearchExampleOutput
 *
 * All custom ApiResource responses should be included using a proper DTO.
 *
 * @package App\Dto
 */
class SearchExampleOutput
{
    /**
     * @var string
     *
     * @Groups({"search-examples:api-get"})
     *
     * @Assert\NotBlank
     */
    public string $title;

    /**
     * @var string
     *
     * @Groups({"search-examples:api-get"})
     *
     * @Assert\NotBlank
     */
    public string $description;
}
