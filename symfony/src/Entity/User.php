<?php

namespace App\Entity;

use App\Component\Model\Traits\EnableTrait;
use App\Component\Model\EnableInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="icapps_users",
 *    uniqueConstraints={
 *        @UniqueConstraint(name="user_unique",
 *            columns={"email", "profile_type"})
 *    }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, EnableInterface
{
    use EnableTrait;

    const LANGUAGES = ['nl', 'en', 'fr'];
    const DEFAULT_LOCALE = 'nl';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="icapps.registration.email.required", groups={"registration"})
     * @Assert\Email(message="icapps.registration.email.invalid", groups={"registration"})
     * @Assert\Length(
     *     min = 5,
     *     max = 50,
     *     minMessage="icapps.registration.email.min_length",
     *     maxMessage="icapps.registration.email.max_length",
     *     allowEmptyString = false,
     *     groups={"registration"}
     * )
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     */
    private ?string $pendingEmail;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="icapps.registration.password.required", groups={"registration"})
     * @Assert\Length(
     *     min = 8,
     *     minMessage="icapps.registration.password.min_length",
     *     allowEmptyString = false,
     *     groups={"registration", "update-password"}
     * )
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $activation_token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $reset_token;

    /**
     * @ORM\Column(type="string", length=2, options={"default":User::DEFAULT_LOCALE})
     * @Assert\Choice(message="icapps.registration.language.invalid", choices=User::LANGUAGES, groups={"registration"})
     */
    private string $language;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private string $profileType;

    /**
     * @ORM\Column(type="integer")
     */
    private int $profileId;

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
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    /**
     * @param string|null $activation_token
     * @return $this
     */
    public function setActivationToken(?string $activation_token): self
    {
        $this->activation_token = $activation_token;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    /**
     * @param string|null $reset_token
     * @return $this
     */
    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

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
}
