<?php

namespace App\Repository\Blog;

use App\Entity\Blog\BlogPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BlogPost>
 *
 * @method BlogPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogPost[]    findAll()
 * @method BlogPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogPost::class);
    }

    public function add(BlogPost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BlogPost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return BlogPost[] Returns an array of BlogPost objects
     */
    public function findByCreatedAt(): array
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.createdAt', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return BlogPost[] Returns an array of BlogPost objects
     */
    public function findByCategory($value): array
        {
            return $this->createQueryBuilder('b')
                ->andWhere('b.category = :val')
                ->setParameter('val', $value)
                ->orderBy('b.createdAt', 'DESC')
                ->setMaxResults(5)
                ->getQuery()
                ->getResult()
        ;
    }

    /**
     * @return BlogPost[] Returns an array of BlogPost objects
     */
    public function findBySlug($value): array
        {
            return $this->createQueryBuilder('b')
                ->andWhere('b.slug = :val')
                ->setParameter('val', $value)
                ->getQuery()
                ->getResult()
        ;
    }

//    /**
//     * @return BlogPost[] Returns an array of BlogPost objects
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

//    public function findOneBySomeField($value): ?BlogPost
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
