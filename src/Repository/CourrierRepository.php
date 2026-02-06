<?php

namespace App\Repository;

use App\Entity\Courrier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Courrier>
 */
class CourrierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Courrier::class);
    }

    public function findPaginated(int $page, int $limit): Paginator
    {
        // pagination sans filtre
        $query = $this->createQueryBuilder('c')
            ->orderBy('c.dateReception', 'DESC')
            ->getQuery()
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return new Paginator($query, true);
    }

    public function findFilteredPaginated(array $filters, int $page, int $limit): Paginator
    {
        // pagination avec filtres
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.dateReception', 'DESC');

        foreach (['destinataire', 'type', 'nature', 'gestionnaire', 'responsable'] as $field) {
            if (!empty($filters[$field])) {
                $qb->andWhere("c.$field = :$field")
                    ->setParameter($field, $filters[$field]);
            }
        }

        if (!empty($filters['reference'])) {
            $qb->andWhere('c.reference LIKE :reference')
                ->setParameter('reference', $filters['reference'] . '%');
        }

        if (!empty($filters['expediteur'])) {
            $qb->andWhere('c.expediteur LIKE :expediteur')
                ->setParameter('expediteur', $filters['expediteur'] . '%');
        }

        $qb->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return new Paginator($qb->getQuery(), true);
    }


    public function countAll(): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countByType(string $type): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->andWhere('c.type = :statut')
            ->setParameter('statut', $type)
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    /**
    //     * @return Courrier[] Returns an array of Courrier objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Courrier
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
