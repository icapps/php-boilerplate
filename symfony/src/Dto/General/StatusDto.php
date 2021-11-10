<?php

namespace App\Dto\General;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class StatusDto
 *
 * All custom ApiResource responses should be included using a proper DTO.
 */
class StatusDto
{
    private const TYPE_HTTP_STATUS_CODE = 'https://datatracker.ietf.org/doc/html/rfc2616#section-10';

    /**
     * The status response type: by default response including HTTP status code definition.
     *
     * @var string
     *
     * @Groups({"api-get"})
     *
     * @Assert\NotBlank
     */
    public string $type = self::TYPE_HTTP_STATUS_CODE;

    /**
     * The status response title.
     *
     * @var string
     *
     * @Groups({"api-get"})
     *
     * @Assert\NotBlank
     */
    public string $title = '';

    /**
     * The status response HTTP status code.
     *
     * @var int
     *
     * @Groups({"api-get"})
     *
     * @Assert\NotBlank
     */
    public int $status = 200;

    /**
     * The status response detailed message.
     *
     * @var string
     *
     * @Groups({"api-get"})
     *
     * @Assert\NotBlank
     */
    public string $detail = '';

    /**
     * @param int $statusCode
     * @param string $title
     * @param string $message
     */
    public function __construct(int $statusCode, string $title, string $message)
    {
        $this->title = $title;
        $this->status = $statusCode;
        $this->detail = $message;
    }
}
