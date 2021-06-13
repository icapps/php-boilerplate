<?php

namespace App\Entity;

use App\Component\Model\Traits\EnableTrait;
use App\Component\Model\EnableInterface;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="icapps_users",
 *    uniqueConstraints={
 *        @UniqueConstraint(
 *            name="user_unique",
 *            columns={"email"},
 *        )
 *    }
 * )
 * @UniqueEntity(
 *     fields={"email"},
 *     message="icapps.registration.email.unique",
 *     groups={"orm-registration", "orm-user-update", "orm-email-validation"}
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, EnableInterface
{
    use EnableTrait;

    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const LANGUAGES = ['nl', 'en', 'fr'];
    public const DEFAULT_LOCALE = 'nl';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Email(message="icapps.registration.email.invalid", groups={"orm-email-validation", "orm-registration", "orm-user-update"})
     * @Assert\NotBlank(
     *     message="icapps.registration.email.required",
     *     groups={"orm-registration", "orm-user-update", "orm-email-validation"}
     * )
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $pendingEmail;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(
     *     message="icapps.registration.password.required",
     *     groups={"orm-registration", "orm-user-update", "orm-update-password"}
     * )
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $activationToken;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $resetToken;

    /**
     * @ORM\Column(type="string", length=2, options={"default":User::DEFAULT_LOCALE})
     *
     * @Assert\NotBlank(
     *     message="icapps.registration.language.required",
     *     groups={"orm-registration", "orm-user-update"}
     * )
     */
    private string $language = self::DEFAULT_LOCALE;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(
     *     message="icapps.registration.profile.required",
     *     groups={"orm-registration"}
     * )
     */
    private string $profileType;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(
     *     groups={"orm-registration"}
     * )
     */
    private int $profileId;

    /**
     * @ORM\OneToMany(targetEntity="\App\Entity\Device", mappedBy="user")
     */
    private $devices = [];

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     *
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string|null
     */
    public function getActivationToken(): ?string
    {
        return $this->activationToken;
    }

    /**
     * @param string|null $activationToken
     *
     * @return $this
     */
    public function setActivationToken(?string $activationToken): self
    {
        $this->activationToken = $activationToken;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    /**
     * @param string|null $resetToken
     *
     * @return $this
     */
    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string $language
     * @return $this
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @param string|null $pendingEmail
     * @return $this
     */
    public function setPendingEmail(?string $pendingEmail): self
    {
        $this->pendingEmail = $pendingEmail;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPendingEmail(): ?string
    {
        return $this->pendingEmail;
    }

    /**
     * @return string[]
     */
    public static function getAvailableLanguages(): array
    {
        return self::LANGUAGES;
    }

    /**
     * @return Collection|Device[]
     */
    public function getDevices(): Collection
    {
        return $this->devices;
    }

    /**
     * @return string
     */
    public function getProfileType(): string
    {
        return $this->profileType;
    }

    /**
     * @param string $profileType
     * @return $this
     */
    public function setProfileType(string $profileType): self
    {
        $this->profileType = $profileType;

        return $this;
    }

    /**
     * @return int
     */
    public function getProfileId(): int
    {
        return $this->profileId;
    }

    /**
     * @param int $profileId
     * @return $this
     */
    public function setProfileId(int $profileId): self
    {
        $this->profileId = $profileId;

        return $this;
    }
}
