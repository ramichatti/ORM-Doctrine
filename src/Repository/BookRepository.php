<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function getBooksByAuthor($author): array
       {
           return $this->createQueryBuilder('b')
               ->join('b.author','a')
               ->addSelect('a')
               ->where('a.id = ?1')
               ->SetParamter('1',$author)
               ->getQuery()
               ->getResult()
           ;
       }

       public function getBooksByDate($date1, $date2): array
{
    return $this->createQueryBuilder('b')
        ->where('b.publicationDate BETWEEN :startDate AND :endDate')
        ->setParameter('startDate', $date1)   // Correction de SetParamter en setParameter
        ->setParameter('endDate', $date2)     // Même correction ici
        ->getQuery()
        ->getResult();
}

}
