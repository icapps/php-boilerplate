<?php

namespace App\Entity;

use App\Component\Model\EnableInterface;
use App\Component\Model\EntityIdInterface;
use App\Component\Model\Traits\EnableTrait;
use App\Component\Model\Traits\EntityIdTrait;
use App\Repository\UserRepository;
use DateTime;
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
 *     groups={"orm-registration", "orm-user-update"},
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, EnableInterface, EntityIdInterface
{
    use EnableTrait;
    use EntityIdTrait;

    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public const LANGUAGES = ['nl', 'en', 'fr'];
    public const DEFAULT_LOCALE = 'nl';

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     *
     * @Assert\NotBlank(
     *     message="icapps.registration.email.required",
     *     groups={"orm-registration", "orm-user-update"}
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
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @Assert\NotBlank(
     *     message="icapps.registration.password.required",
     *     groups={"orm-registration", "orm-user-update"}
     * )
     */
    private string $password;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected ?DateTime $lastLogin;

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
     * @ORM\OneToOne(targetEntity=Profile::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Profile $profile;

    /**
     * @ORM\OneToMany(targetEntity="\App\Entity\Device", mappedBy="user")
     */
    private Collection|array $devices = [];

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
     * @return string
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // Guarantee every user at least has ROLE_USER.
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
     * @return string
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
     * @return null|string
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Get lastLogin.
     *
     * @return null|DateTime
     */
    public function getLastLogin(): ?DateTime
    {
        return $this->lastLogin;
    }

    /**
     * Set lastLogin.
     *
     * @param DateTime|null $lastLogin
     *
     * @return $this
     */
    public function setLastLogin(?DateTime $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * @return null|string
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
     * @return null|string
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
     *
     * @return $this
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPendingEmail(): ?string
    {
        return $this->pendingEmail;
    }

    /**
     * @param string|null $pendingEmail
     */
    public function setPendingEmail(?string $pendingEmail): void
    {
        $this->pendingEmail = $pendingEmail;
    }

    /**
     * @return string[]
     */
    public static function getAvailableLanguages(): array
    {
        return self::LANGUAGES;
    }

    /**
     * @return array|Collection
     */
    public function getDevices(): array|Collection
    {
        return $this->devices;
    }

    /**
     * @return null|Profile
     */
    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    /**
     * @param Profile $profile
     *
     * @return $this
     */
    public function setProfile(Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }
}
