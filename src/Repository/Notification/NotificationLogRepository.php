<?php

namespace App\Repository\Notification;

use App\Entity\Notification\NotificationLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NotificationLog>
 *
 * @method NotificationLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method NotificationLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method NotificationLog[]    findAll()
 * @method NotificationLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotificationLog::class);
    }

    public function save(NotificationLog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(NotificationLog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * find items by multi parameters with pagination
     *
     * @param boolean $onlyCount - true if return only count
     * @param array $filter - data array with filter parameters
     * @param array $orderBy - order param (key is field, value is direction, defulat id DESC)
     * @param integer $offset - pagination offset
     * @param integer $limit - pagination limit
     * @param string $groupBy
     *
     * @return array
     */
    public function findByMultiParameters(bool $onlyCount, array $filter = [], array $orderBy = ['i.id' => 'DESC'], $offset = null, $limit = null, $groupBy = null)
    {
        $select = ($onlyCount ? 'COUNT(i.id)' . ($groupBy ? ', ' . $groupBy : '') : ($groupBy ? $groupBy : 'i'));
        $query = $this->getEntityManager()->createQueryBuilder()
            ->from(NotificationLog::class, 'i')
            ->select($select);

        // id
        if (isset($filter['id']) && $filter['id'] != null) {
            $query->andWhere('i.id = :id')->setParameter('id', $filter['id']);
        }

        // is finished
        if (isset($filter['status']) && $filter['status'] != null) {
            $query->andWhere('i.status = :status')->setParameter('status', $filter['status']);
        }

        // available date from
        if (isset($filter['dateFrom']) && $filter['dateFrom'] != null) {
            if ($filter['dateFrom'] instanceof \DateTime) {
                $filter['dateFrom']->setTime(0, 0, 0, 0);
                $query->andWhere('i.date >= :dateFrom')->setParameter('dateFrom', $filter['dateFrom']);
            }
        }

        // available date to
        if (isset($filter['dateTo']) && $filter['dateTo'] != null) {
            if ($filter['dateTo'] instanceof \DateTime) {
                $filter['dateTo']->setTime(23, 59, 59, 999999);
                $query->andWhere('i.date <= :dateTo')->setParameter('dateTo', $filter['dateTo']);
            }
        }


        if ($groupBy) {
            $query = $query->groupBy($groupBy);
        }

        if (!empty($orderBy)) {
            foreach ($orderBy as $orderField => $orderDirection) {
                $query->addOrderBy($orderField, $orderDirection);
            }
        }

        if (!$onlyCount) {
            $query = $query->setFirstResult($offset)
                ->setMaxResults($limit);
        }

        try {
            if ($onlyCount) {
                if ($groupBy) {
                    return $query->getQuery()->getResult();
                } else {
                    return $query->getQuery()->getSingleScalarResult();
                }
            } else {
                return $query->getQuery()->getResult();
            }
        } catch (\Doctrine\ORM\NoResultException $e) {
            return $onlyCount ? 0 : [];
        }
    }

//    /**
//     * @return NotificationLog[] Returns an array of NotificationLog objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?NotificationLog
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
