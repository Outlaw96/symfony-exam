<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function save(Author $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Author $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByAuthor(?string $firstname, ?string $lastname): array
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->select('a')
            ->leftJoin('a.books', 'b')
            ->addSelect('b', 'b.id, count(b)');

        $firstnameFilled = false;
        if (null !== $firstname) {
            $queryBuilder->where('a.firstname = :firstname')
                ->setParameter('firstname', $firstname);

            $firstnameFilled = true;
        }

        if (null !== $lastname) {
            if (!$firstnameFilled) {
                $queryBuilder->where('a.lastname = :lastname')
                    ->setParameter('lastname', $lastname);
            } else {
                $queryBuilder->andWhere('a.lastname = :lastname')
                    ->setParameter('lastname', $lastname);
            }
        }

        return $queryBuilder
            ->orderBy('a.id', 'ASC')
            ->groupBy('a.id')
            ->getQuery()
            ->getArrayResult();
    }
//    /**
//     * @return Author[] Returns an array of Author objects
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

//    public function findOneBySomeField($value): ?Author
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
