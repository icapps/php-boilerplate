<?php

namespace App\Dto\Examples;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SearchExampleDto
 *
 * All custom ApiResource responses should be included using a proper DTO.
 *
 * @package App\Dto
 */
class SearchExampleDto
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
