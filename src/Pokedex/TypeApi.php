<?php

namespace App\Pokedex;

use App\Entity\Type;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TypeApi
{
    private HttpClientInterface $client;

    private TypeRepository $typeRepository;

    private EntityManagerInterface $em;

    public function __construct(TypeRepository $typeRepository, EntityManagerInterface $em)
    {
        $this->client = HttpClient::createForBaseUri('https://pokeapi.co/api/v2/');
        $this->typeRepository = $typeRepository;
        $this->em = $em;

    }

    public function getTypes(int $offset=0, int $limit=20): array
    {
        $response = $this->client->request('GET', 'type', [
            'query' => [
                'offset' => $offset,
                'limit' => $limit,
            ],
        ]);

        if(200 !==$response->getStatusCode()) {
            throw new \RuntimeExceptio('Error from PokeApi.co');
        }

        $data = $response->toArray();
        $types = [];

        foreach($data['results'] as $type) {
            if (!preg_match('/([0-9]+)\/?$/', $type['url'], $matches)) {
                throw new \RuntimeExceptio('Cannot match given url for type ' . $type['name']);
            }
            $id=$matches[1];

            $type = [
                'id' => $id,
                'name' => $type['name'],
            ];
            $types[] = $this->convertPokeApiToType($type);
        }

        //next page
        if ($data['next']) {
            if (!preg_match('/\?.*offset=([0-9]+)/', $data['next'], $matches)) {
                throw new \RuntimeException('Cannot match offset on next page.');
            }
            $nextOffset = $matches[1];

            $nextTypes = $this->getTypes($nextOffset, $limit);
            $types = array_merge($types, $nextTypes);
        }

        return $types;
    }

    public function convertPokeApiToType(array $type):Type {

        $typeEntity = $this->typeRepository->findOneBy([
            'pokeapiId' => $type['id'],
        ]);

        if(null === $typeEntity) {
            $typeEntity = new Type();
            $typeEntity->setPokeapiId($type['id']);
            $typeEntity->setName($type['name']);
            $this->em->persist($typeEntity);
            $this->em->flush();

        }
        return $typeEntity;

    }



}