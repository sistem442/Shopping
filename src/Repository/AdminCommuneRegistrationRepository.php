<?php

namespace App\Repository;

use App\Entity\AdminCommuneRegistration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdminCommuneRegistration>
 *
 * @method AdminCommuneRegistration|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminCommuneRegistration|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminCommuneRegistration[]    findAll()
 * @method AdminCommuneRegistration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminCommuneRegistrationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminCommuneRegistration::class);
    }

    public function save(AdminCommuneRegistration $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AdminCommuneRegistration $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return AdminCommuneRegistration[] Returns an array of AdminCommuneRegistration objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AdminCommuneRegistration
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
