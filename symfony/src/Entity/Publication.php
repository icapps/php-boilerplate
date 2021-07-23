<?php

namespace App\Entity;

use App\Repository\PublicationRepository;
use Doctrine\ORM\Mapping as ORM;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

/**
 * @ORM\Entity(repositoryClass=PublicationRepository::class)
 */
class Publication implements AuditableInterface
{
    use AuditableTrait;

    public const RESOURCE_KEY = 'publications';
    public const FORM_KEY = 'publication_details';
    public const LIST_KEY = 'publications';
    public const SECURITY_CONTEXT = 'admin.publications';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $contentBlocks = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return array
     */
    public function getContentBlocks(): ?array
    {
        return $this->contentBlocks;
    }

    /**
     * @param array $contentBlocks
     */
    public function setContentBlocks($contentBlocks): void
    {
        $this->contentBlocks = $contentBlocks;
    }
}
