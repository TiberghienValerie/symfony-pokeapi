<?php

namespace App\Repository;

use App\Entity\PokemonAttack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonAttack|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonAttack|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonAttack[]    findAll()
 * @method PokemonAttack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonAttackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonAttack::class);
    }

    // /**
    //  * @return Pokemonattack[] Returns an array of Pokemonattack objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Pokemonattack
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}