<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 *
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function add(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllOrderByTitleAsc()
    {   
        // $entityManager = $this->getEntityManager();
        // $query = $entityManager->createQuery('
        // SELECT m FROM App\Entity\Movie m LIMIT "5" ORDER BY m.title ASC');
        // // $query->setMaxResults(2);
        // $query->setFirstResult(0);
        // $movies = $query->getResult();

        // $entityManager = $this->getEntityManager();
        // $qb = $entityManager->createQueryBuilder();
        // $movies = $qb->select('m')
        //     ->from('App\Entity\Movie', 'm')
        //     ->orderBy('m.title', 'ASC')
        //     ->getQuery()
        //     ->getResult();

        return $this->createQueryBuilder('m')
        ->orderBy("m.title", "ASC")
        ->setMaxResults(50)
        ->getQuery()
        ->getResult();
    }

    public function findSearch($searchMovies)
    {
        return $this->createQueryBuilder('m')
                    ->orderBy("m.title", "ASC")
                    ->where('m.title LIKE :searchTerm')
                    ->setParameter('searchTerm', "%$searchMovies%")
                    ->getQuery()
                    ->getResult();
    }

    /**
    * get a random movie
    */
    public function findRandomMovie()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM movie
        ORDER BY RAND()
        LIMIT 1";
        $resultSet = $conn->executeQuery($sql);

         return $resultSet->fetchAssociative();
    }

    public function averageRating($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUBSTR(AVG(review.rating),1,3)
        FROM movie
        INNER JOIN review ON review.movie_id = movie.id
        WHERE movie.id = $id
        AND review.rating IS NOT NULL;";
        $resultSet = $conn->executeQuery($sql);

        return $resultSet->fetchOne();
    }

//    /**
//     * @return Movie[] Returns an array of Movie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Movie
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
