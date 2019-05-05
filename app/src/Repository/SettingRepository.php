<?php

namespace App\Repository;

use App\Entity\Setting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Setting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Setting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Setting[]    findAll()
 * @method Setting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SettingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Setting::class);
    }

    public function getHomepageId()
    {
        $settings =  $this->getOrCreateQueryBuilder()
            ->andWhere('t.name = \'homepage\'')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        return $settings[0]->getValue();
    }

    public function getPageTitle(): string
    {
        $settings =  $this->getOrCreateQueryBuilder()
            ->andWhere('t.name = \'title\'')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        return $settings[0]->getValue();
    }

    /**
    * Get or create new query builder.
    *
    * @param \Doctrine\ORM\QueryBuilder|null $queryBuilder Query builder
    *
    * @return \Doctrine\ORM\QueryBuilder Query builder
    */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?: $this->createQueryBuilder('t');
    }

    // /**
    //  * @return Setting[] Returns an array of Setting objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Setting
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
