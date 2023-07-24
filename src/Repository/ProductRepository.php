<?php

namespace App\Repository;

use App\Entity\Product;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Commune;
use App\Entity\User;

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

    public function findByYearMonth(Commune $commune,$month,$year): array
    {
        //initially use current month and year
        if($year == 0)
        {
            $year = date("Y");
        }

        if($month == 0)
        {
            $month = date("m");
        }

        $number_of_days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $qb = $this->createQueryBuilder('p')
            ->addSelect('u', 'c')
            ->innerJoin('p.user', 'u')
            ->innerJoin('u.commune', 'c')
            ->where('c.id ='.$commune->getId())
            ->andWhere("p.purchased_at >= '$year-$month-01 00:00:00'" )
            ->andWhere("p.purchased_at <= '$year-$month-$number_of_days_in_month 23:59:59'" )
            ->orderBy('p.user', 'DESC')
            ;
        $query = $qb->getQuery();
        return $query->execute();
       /* $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Product p
            INNER JOIN App\Entity\User on p.user_id = u.id
            INNER JOIN App\Entity\Commune on u.commune_id = c.id
            WHERE p.purchased_at >= '.$year.'-'.$month.'-01 00:00:00
            AND p.purchased_at <= '.$year.'-'.$month.'-'.$number_of_days_in_month.' 23:59:59'
        );
        dump($query->getResult());
        die();

        // returns an array of Product objects
        return $query->getResult();*/
    }

    public function findByCommuneId(Commune $commune):array
    {
        $commune_id = $commune->getId();
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT DISTINCT YEAR(purchased_at) as year
                FROM product p 
                INNER JOIN user u on p.user_id = u.id 
                INNER JOIN commune c on u.commune_id = c.id 
                WHERE c.id = ' . $commune_id;

        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }
    public function findByUserMonth(Commune $commune,$month,$year):array
    {
        $users = $commune->getUsers();
        $number_of_days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $start_date = $year."-".$month."-01 00:00:00";
        $end_date = $year."-".$month."-".$number_of_days_in_month." 23:59:59";
        $conn = $this->getEntityManager()->getConnection();
        $result=[];

        foreach ($users as $user)
        {
            $user_id =$user->getId();
            $sql = "SELECT user.name, SUM(product.price) AS paid 
                    FROM user
                    INNER JOIN product
                    ON user.id = product.user_id 
                    WHERE user.id = $user_id 
                    AND product.purchased_at BETWEEN '$start_date' AND '$end_date';
            ";

            $resultSet = $conn->executeQuery($sql);
            $result[$user_id]=$resultSet->fetchAllAssociative();

        }
        return $result;
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
