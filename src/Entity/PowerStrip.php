<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PowerStripRepository")
 * @ApiResource(
 *     routePrefix="/v0",
 *     itemOperations={
 *         "getPowerStrips"={
 *             "route_name"="v0_get_one_ps",
 *             "normalization_context"={"groups"={"powerstrip"}},
 *         },
 *         "putPowerStrips"={
 *             "route_name"="v0_update_name_ps",
 *             "normalization_context"={"groups"={"powerstrip"}},
 *         },
 *         "deletePowerStrips"={
 *             "route_name"="v0_delete_ps",
 *             "normalization_context"={"groups"={"powerstrip"}},
 *         },
 *         "addPlugPowerStrips"={
 *             "route_name"="v0_add_plug_ps",
 *             "normalization_context"={"groups"={"plug"}},
 *         },
 *     },
 *     collectionOperations={
 *         "getPowerStrips"={
 *             "route_name"="v0_get_all_ps",
 *             "normalization_context"={"groups"={"powerstrip"}},
 *         },
 *         "storePowerStrip"={
 *             "route_name"="v0_store_ps",
 *             "normalization_context"={"groups"={"powerstrip"}},
 *         },
 *     },
 *     denormalizationContext={"groups"={"powerstrip"}}
 * )
 */
class PowerStrip {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"powerstrip","plug"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"powerstrip","plug"})
     * @var String The power strip's name
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @Groups({"a"})
     * @var User The power strip's owner
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Plug", mappedBy="power_strip", cascade={"persist", "remove"})
     * @var Plug[] The power strip's plugs
     * @Groups({"powerstrip"})
     */
    private $plugs;

    /**
     * @ORM\OneToMany(targetEntity="AccessLog", mappedBy="powerstrip")
     * @var AccessLog[] The power strip's logs
     */
    private $accessLog;

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser(User $user) {
        $this->user = $user;
    }

    public function getPlugs() {
        return $this->plugs;
    }

    public function setPlugs($plugs) {
        $this->plugs = $plugs;
        return $this;
    }

    public function getAccessLog() {
        return $this->accessLog;
    }

}
