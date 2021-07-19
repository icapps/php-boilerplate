<?php

namespace App\User\Infrastructure\Doctrine;

use App\User\Domain\ValueObject\Auth\HashedPassword;
use App\User\Domain\ValueObject\Email;
use App\User\Infrastructure\Model\EnableInterface;
use App\User\Infrastructure\Model\Traits\EnableTrait;
use DateTime;
use App\User\Infrastructure\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Ramsey\Uuid\Uuid;
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
 *     groups={"orm-registration", "orm-user-update"}
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 *
 * @method string getUserIdentifier()
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
    private int $id;

    /**
     * @ORM\Column(type="string", length=50)
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
     * @ORM\Column(type="string", length=255)
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
    protected $lastLogin;

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
    private Profile $profile;

    /**
     * @ORM\OneToMany(targetEntity=Device::class, mappedBy="user")
     */
    private $devices = [];

    /**
     * Create user object.
     *
     * @param Email $email
     * @param string $language
     * @param HashedPassword $password
     *
     * @return static
     */
    public static function create(Email $email, string $language, HashedPassword $password): self
    {
        $user = new self();
        $user->setEmail($email);
        $user->setLanguage($language);
        $user->setPassword($password);
        $user->disable();
        $user->setActivationToken(Uuid::uuid4()->toString());
        $user->setRoles([User::ROLE_USER]);

        return $user;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
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
     * Set lastLogin.
     *
     * @param DateTime|null $lastLogin
     *
     * @return User
     */
    public function setLastLogin(?DateTime $lastLogin): User
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin.
     *
     * @return DateTime|null
     */
    public function getLastLogin(): ?DateTime
    {
        return $this->lastLogin;
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
     */
    public function setPendingEmail(?string $pendingEmail): void
    {
        $this->pendingEmail = $pendingEmail;
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
     * @return Profile|null
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

    /**
     * @return Collection|Device[]
     */
    public function getDevices(): Collection
    {
        return $this->devices;
    }
}
