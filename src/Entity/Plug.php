<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlugRepository")
 * @ApiResource(
 *     routePrefix="/v0",
 *     itemOperations={
 *         "putActualState"={
 *             "route_name"="v0_update_actual_state",
 *             "normalization_context"={"groups"={"plug"}},
 *         },
 *         "putWantedState"={
 *             "route_name"="v0_update_wanted_state",
 *             "normalization_context"={"groups"={"plug"}},
 *         },
 *         "putPlug"={
 *             "route_name"="v0_update_name_plug",
 *             "normalization_context"={"groups"={"plug"}},
 *         },
 *         "getPlug"={
 *             "route_name"="v0_get_one_plug",
 *             "normalization_context"={"groups"={"plug"}},
 *         },
 *         "getPlugStates"={
 *             "route_name"="v0_get_states_plug",
 *             "normalization_context"={"groups"={"plug"}},
 *         }
 *     },
 *     collectionOperations={
 *         "getPlugs"={
 *             "route_name"="v0_get_all_plugs",
 *             "normalization_context"={"groups"={"plug"}},
 *         },
 *     },
 *     denormalizationContext={"groups"={"powerstrip","plug"}}
 * )
 */
class Plug {

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
     * @var String The plug's name
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"powerstrip","plug"})
     * @var Boolean The last known state, sent by the power strip (true/false => on/off)
     */
    private $state = false;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"powerstrip","plug"})
     * @var Boolean The desired state (true/false => on/off)
     */
    private $tempState = false;

    /**
     * @ORM\ManyToOne(targetEntity="PowerStrip")
     * @var PowerStrip
     * @Groups({"plug"})
     */
    protected $power_strip;

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

    public function getState(): ?bool {
        return $this->state;
    }

    public function setState(bool $state): self {
        $this->state = $state;
        return $this;
    }

    public function getTempState(): ?bool {
        return $this->tempState;
    }

    public function setTempState(bool $tempState): self {
        $this->tempState = $tempState;
        return $this;
    }

    public function getPowerStrip() {
        return $this->power_strip;
    }

    public function setPowerStrip($power_strip) {
        $this->power_strip = $power_strip;
        return $this;
    }

}
