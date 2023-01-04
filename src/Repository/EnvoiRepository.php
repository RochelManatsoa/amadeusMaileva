<?php

namespace App\Repository;

use App\Entity\Envoi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Envoi>
 *
 * @method Envoi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Envoi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Envoi[]    findAll()
 * @method Envoi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnvoiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Envoi::class);
    }

    public function add(Envoi $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Envoi $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Envoi[] Returns an array of Envoi objects
    */
   public function getEnvoiByResiliation($value): array
   {
       return $this->createQueryBuilder('e')
           ->andWhere('e.customId = :val')
           ->setParameter('val', $value)
           ->orderBy('e.id', 'ASC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

   public function getOneEnvoiByResiliation($value): ?Envoi
   {
       return $this->createQueryBuilder('e')
           ->andWhere('e.customId = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
