<?php

namespace App\Repository;

use App\Entity\Charity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Charity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Charity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Charity[]    findAll()
 * @method Charity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Charity::class);
    }
}
