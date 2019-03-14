<?php

namespace App\Entity;


use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccessLogRepository")
 */
class AccessLog {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $method;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $IP;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @Groups({"a"})
     * @var User owner
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="PowerStrip")
     * @Groups({"a"})
     * @var PowerStrip ps
     */
    protected $powerstrip;

    public function getId(): ?int {
        return $this->id;
    }

    public function getMethod(): ?string {
        return $this->method;
    }

    public function setMethod(string $method): self {
        $this->method = $method;
        return $this;
    }

    public function getUrl(): ?string {
        return $this->url;
    }

    public function setUrl(string $url): self {
        $this->url = $url;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self {
        $this->date = $date;
        return $this;
    }

    public function getIP(): ?string {
        return $this->IP;
    }

    public function setIP(string $IP): self {
        $this->IP = $IP;
        return $this;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser(User $user) {
        $this->user = $user;
    }

    public function getPowerstrip() {
        return $this->powerstrip;
    }

    public function setPowerstrip($ps) {
        $this->powerstrip = $ps;
        return $this;
    }

}
