<?php

namespace App\Pokedex;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PokemonApi
{
    private HttpClientInterface $client;

    private PokemonRepository $pokemonRepository;

    private EntityManagerInterface $em;

    public function __construct(PokemonRepository $pokemonRepository, EntityManagerInterface $em)
    {
        $this->client = HttpClient::createForBaseUri('https://pokeapi.co/api/v2/');
        $this->pokemonRepository = $pokemonRepository;
        $this->em = $em;

    }

    public function getPokemons(int $offset=0, int $limit=50): array
    {
        $response = $this->client->request('GET', 'pokemon', [
            'query' => [
                'offset' => $offset,
                'limit' => $limit,
            ],
        ]);

        if(200 !==$response->getStatusCode()) {
            throw new \RuntimeExceptio('Error from PokeApi.co');
        }

        $data = $response->toArray();
        $pokemons = [];

        foreach($data['results'] as $pokemon) {
            if (!preg_match('/([0-9]+)\/?$/', $pokemon['url'], $matches)) {
                throw new \RuntimeExceptio('Cannot match given url for pokemon ' . $pokemon['name']);
            }
            $id=$matches[1];

            $response = $this->client->request('GET', 'pokemon/'.$id);
            $datasous = $response->toArray();


            $pokemon = [
                'id' => $id,
                'name' => $pokemon['name'],
                'order' => $datasous['order'],
                'weight' => $datasous['weight'],
                'height' => $datasous['height'],
                'base_experience' => $datasous['base_experience'],
            ];
            $pokemons[] = $this->convertPokeApiToPokemon($pokemon);
        }

        //next page
        if ($data['next']) {
            if (!preg_match('/\?.*offset=([0-9]+)/', $data['next'], $matches)) {
                throw new \RuntimeException('Cannot match offset on next page.');
            }
            $nextOffset = $matches[1];

            $nextPokemon = $this->getPokemons($nextOffset, $limit);
            $pokemons = array_merge($pokemons, $nextPokemon);
        }

        return $pokemons;
    }

    public function convertPokeApiToPokemon(array $pokemon):Pokemon {

        $pokemonEntity = $this->pokemonRepository->findOneBy([
            'id' => $pokemon['id'],
        ]);

        if(null === $pokemonEntity) {
            $pokemonEntity = new Pokemon($pokemon['id']);
            $pokemonEntity->setName($pokemon['name']);
            $pokemonEntity->setHeight($pokemon['height']);
            $pokemonEntity->setWeight($pokemon['weight']);
            $pokemonEntity->setBaseExperience($pokemon['base_experience']);
            $pokemonEntity->setPokedexOrder($pokemon['order']);
            $this->em->persist($pokemonEntity);
            $this->em->flush();

        }
        return $pokemonEntity;

    }



}