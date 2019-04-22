<?php

namespace App\Repository;

use App\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Page::class);
    }

    /**
    * Query all records.
    *
    * @return \Doctrine\ORM\QueryBuilder Query builder
    */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('t.updatedAt', 'DESC');
    }

    /**
    * Save record.
    *
    * @param \App\Entity\Page $page Page entity
    *
    * @return void
    *
    * @throws \Doctrine\ORM\ORMException
    * @throws \Doctrine\ORM\OptimisticLockException
    */
    public function save(Page $page): void
    {
        $this->_em->persist($page);
        $this->_em->flush($page);
    }

    /**
    * Delete record.
    *
    * @param \App\Entity\Page $page Page entity
    *
    * @throws \Doctrine\ORM\ORMException
    * @throws \Doctrine\ORM\OptimisticLockException
    */
    public function delete(Page $page): void
    {
        $this->_em->remove($page);
        $this->_em->flush($page);
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
    //  * @return Page[] Returns an array of Page objects
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
    public function findOneBySomeField($value): ?Page
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
