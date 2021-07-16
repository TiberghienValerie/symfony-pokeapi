<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PokemonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=PokemonRepository::class)
 */
class Pokemon
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $pokeapiId;

    /**
     * @ORM\Column(type="integer")
     *
     */
    private $height;

    /**
     * @ORM\Column(type="integer")
     */
    private $weight;

    /**
     * @ORM\Column(type="integer")
     */
    private $baseExperience;

    /**
     * @ORM\Column(type="integer")
     */
    private $orderPoke;

    /**
     * @ORM\ManyToMany(targetEntity=type::class, inversedBy="pokemon")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=PokemonAttack::class, mappedBy="pokemon")
     */
    private $pokemonAttacks;

    public function __construct()
    {
        $this->type = new ArrayCollection();
        $this->pokemonAttacks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPokeapiId(): ?int
    {
        return $this->pokeapiId;
    }

    public function setPokeapiId(int $pokeapiId): self
    {
        $this->pokeapiId = $pokeapiId;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getBaseExperience(): ?int
    {
        return $this->baseExperience;
    }

    public function setBaseExperience(int $baseExperience): self
    {
        $this->baseExperience = $baseExperience;

        return $this;
    }

    public function getOrderPoke(): ?int
    {
        return $this->orderPoke;
    }

    public function setOrderPoke(int $orderPoke): self
    {
        $this->orderPoke = $orderPoke;

        return $this;
    }

    /**
     * @return Collection|type[]
     */
    public function getType(): Collection
    {
        return $this->type;
    }

    public function addType(type $type): self
    {
        if (!$this->type->contains($type)) {
            $this->type[] = $type;
        }

        return $this;
    }

    public function removeType(type $type): self
    {
        $this->type->removeElement($type);

        return $this;
    }

    /**
     * @return Collection|PokemonAttack[]
     */
    public function getPokemonAttacks(): Collection
    {
        return $this->pokemonAttacks;
    }

    public function addPokemonAttack(PokemonAttack $pokemonAttack): self
    {
        if (!$this->pokemonAttacks->contains($pokemonAttack)) {
            $this->pokemonAttacks[] = $pokemonAttack;
            $pokemonAttack->setPokemon($this);
        }

        return $this;
    }

    public function removePokemonAttack(PokemonAttack $pokemonAttack): self
    {
        if ($this->pokemonAttacks->removeElement($pokemonAttack)) {
            // set the owning side to null (unless already changed)
            if ($pokemonAttack->getPokemon() === $this) {
                $pokemonAttack->setPokemon(null);
            }
        }

        return $this;
    }
}
