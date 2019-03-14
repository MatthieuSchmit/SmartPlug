<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ApiResource(
 *     routePrefix="/v0",
 *     itemOperations={
 *         "updateUser"={
 *             "route_name"="v0_update_user",
 *             "normalization_context"={"groups"={"user"}},
 *         },
 *         "getUser"={
 *             "route_name"="v0_get_user",
 *             "normalization_context"={"groups"={"user"}},
 *         },
 *     },
 *     collectionOperations={}
 * )
 */
class User implements UserInterface {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    private $currentPassword;
    private $plainPassword;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Groups({"user"})
     */
    private $apiToken;

    /**
     * @ORM\OneToMany(targetEntity="PowerStrip", mappedBy="user")
     * @var PowerStrip[]
     */
    private $powerStrip;

    /**
     * @ORM\OneToMany(targetEntity="AccessLog", mappedBy="user")
     * @var AccessLog[]
     */
    private $logs;

    /**
     * @var string le token qui servira lors de l'oubli de mot de passe
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $resetToken;

    /**
     * @return string
     */
    public function getResetToken(): string {
        return $this->resetToken;
    }

    /**
     * @param string $resetToken
     */
    public function setResetToken(?string $resetToken): void {
        $this->resetToken = $resetToken;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(string $email): self {
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string {
        return (string) $this->password;
    }

    public function setPassword(string $password): self {
        $this->password = $password;
        return $this;
    }

    public function getPlainPassword(): string {
        return (string) $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function getCurrentPassword() {
        return $this->currentPassword;
    }

    public function setCurrentPassword($currentPassword) {
        $this->currentPassword = $currentPassword;
        return $this;
    }

    public function getApiToken() {
        return $this->apiToken;
    }

    public function setApiToken($token) {
        $this->apiToken = $token;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt() {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials() {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function countPowerStrip() {
        return count($this->powerStrip);
    }

    public function getLogs() {
        return $this->logs;
    }

}