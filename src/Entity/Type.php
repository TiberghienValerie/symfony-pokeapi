<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\Filter\OrderFilter;

/**
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"},
 *     normalizationContext={
 *          "groups"={"type:get"}
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"name"="partial", "pokemons.pokemon.name"="partial", "attacks.attack.name"="partial"})
 * @ApiFilter(OrderFilter::class, properties={"id"="asc"})
 *
 * @ORM\Entity(repositoryClass=TypeRepository::class)
 */
class Type
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"type:get","pokemon:get", "attack:get"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $pokeapiId;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"type:get","pokemon:get", "attack:get"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Pokemon::class, mappedBy="types")
     */
    private $pokemons;

    /**
     * @ORM\OneToMany(targetEntity=Attack::class, mappedBy="type")
     */
    private $attacks;

    public function __construct()
    {
        $this->pokemons = new ArrayCollection();
        $this->attacks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPokeapiId(): ?int
    {
        return $this->pokeapiId;
    }

    public function setPokeapiId(int $pokeapiId): self
    {
        $this->pokeapiId = $pokeapiId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Pokemon[]
     */
    public function getPokemons(): Collection
    {
        return $this->pokemons;
    }

    public function addPokemon(Pokemon $pokemon): self
    {
        if (!$this->pokemons->contains($pokemon)) {
            $this->pokemons[] = $pokemon;
            $pokemon->addType($this);
        }

        return $this;
    }

    public function removePokemon(Pokemon $pokemon): self
    {
        if ($this->pokemons->removeElement($pokemon)) {
            $pokemon->removeType($this);
        }

        return $this;
    }

    /**
     * @return Collection|Attack[]
     */
    public function getAttacks(): Collection
    {
        return $this->attacks;
    }

    public function addAttack(Attack $attack): self
    {
        if (!$this->attacks->contains($attack)) {
            $this->attacks[] = $attack;
            $attack->setType($this);
        }

        return $this;
    }

    public function removeAttack(Attack $attack): self
    {
        if ($this->attacks->removeElement($attack)) {
            // set the owning side to null (unless already changed)
            if ($attack->getType() === $this) {
                $attack->setType(null);
            }
        }

        return $this;
    }
}
