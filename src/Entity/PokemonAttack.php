<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PokemonAttackRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=PokemonAttackRepository::class)
 */
class PokemonAttack
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $lvlApprentissage;

    /**
     * @ORM\ManyToOne(targetEntity=Pokemon::class, inversedBy="pokemonAttacks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pokemon;

    /**
     * @ORM\ManyToOne(targetEntity=Attack::class, inversedBy="pokemonAttacks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Attack;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLvlApprentissage(): ?int
    {
        return $this->lvlApprentissage;
    }

    public function setLvlApprentissage(int $lvlApprentissage): self
    {
        $this->lvlApprentissage = $lvlApprentissage;

        return $this;
    }

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function setPokemon(?Pokemon $pokemon): self
    {
        $this->pokemon = $pokemon;

        return $this;
    }

    public function getAttack(): ?Attack
    {
        return $this->Attack;
    }

    public function setAttack(?Attack $Attack): self
    {
        $this->Attack = $Attack;

        return $this;
    }
}
