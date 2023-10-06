<?php

namespace App\Repository\User;

use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }


    /**
     * find items by multi parameters with pagination
     *
     * @param boolean $onlyCount - true if return only count
     * @param array $filter - data array with filter parameters
     * @param array $orderBy - order param (key is field, value is direction, default id DESC)
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
            ->from(User::class, 'i')
            ->select($select);

        // id
        if (isset($filter['id']) && $filter['id'] != null) {
            $query->andWhere('i.id = :id')->setParameter('id', $filter['id']);
        }

        // available date from
        if (isset($filter['createdDateFrom']) && $filter['createdDateFrom'] != null) {
            if ($filter['createdDateFrom'] instanceof \DateTime) {
                $filter['createdDateFrom']->setTime(0, 0, 0, 0);
                $query->andWhere('i.createdAt >= :createdDateFrom')->setParameter('createdDateFrom', $filter['createdDateFrom']);
            }
        }

        // available date to
        if (isset($filter['createdDateTo']) && $filter['createdDateTo'] != null) {
            if ($filter['createdDateTo'] instanceof \DateTime) {
                $filter['createdDateTo']->setTime(23, 59, 59, 999999);
                $query->andWhere('i.createdAt <= :createdDateTo')->setParameter('createdDateTo', $filter['createdDateTo']);
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
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
