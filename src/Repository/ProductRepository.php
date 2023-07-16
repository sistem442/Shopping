<?php

namespace App\Repository;

use App\Entity\Product;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Commune;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Product[]
     */
    public function findAll(int $page = 1): Paginator
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.id > 0')
            ->orderBy('p.id', 'DESC')
        ;

        return (new Paginator($qb))->paginate($page);
    }

    public function findByExampleField2($user,int $page = 1): Paginator
    {
        $commune = $user->getCommune();
        $users = $commune->getUsers();
        $qb = $this->createQueryBuilder('p')
            ->where('p.id > 0')
           // ->setParameter('val', $users)
        ;
        return (new Paginator($qb))->paginate($page);
    }

    public function findByExampleField(Commune $commune,int $page = 1): Paginator
    {
        $qb = $this->createQueryBuilder('p')
            ->addSelect('u', 'c')
            ->innerJoin('p.user', 'u')
            ->innerJoin('u.commune', 'c')
            ->where('c.id ='.$commune->getId())
            ->orderBy('p.user', 'DESC')
        ;

        return (new Paginator($qb))->paginate($page);
    }


//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
