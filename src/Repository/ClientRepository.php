<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function add(Client $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Client $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    // public function filterwithdiv($filters=null){
    //     $qb = $this->createQueryBuilder('c');
    //     if($filters!=null){
    //         $qb
    //         ->Where('c.division IN(:divs)')
    //         ->setParameter(':divs', array_values($filters));
    //     }
    //     else{
    //         $qb->select('c');
    //     }
    //     return $qb->getQuery()->getResult();
  
  
    //   }

    public function filterwithdiv($filters=null, $userId=null){
        $qb = $this->createQueryBuilder('c');
        if($filters!=null){
            $qb
            ->Where('c.division IN(:divs)')
            ->setParameter(':divs', array_values($filters));
        }
        if($userId!=null){
            $qb
            ->andWhere('c.User = :user_id')
            ->setParameter(':user_id', $userId);
        }
        return $qb->getQuery()->getResult();
    }
    //   public function TotalClients($filters=null){ 
    //     $qb = $this->createQueryBuilder('c')
    //     ->select('COUNT(c)');
    //     if($filters!=null){
    //         $qb
    //         ->Where('c.division IN(:divs)')
    //         ->setParameter(':divs', array_values($filters));
    //     }
    //     return $qb->getQuery()->getSingleScalarResult();
  
  
    //   }
    public function TotalClients($filters=null, $userId=null){ 
        $qb = $this->createQueryBuilder('c')
            ->select('COUNT(c)');
        if($filters!=null){
            $qb
            ->andWhere('c.division IN(:divs)')
            ->setParameter(':divs', array_values($filters));
        }
        if($userId!=null){
            $qb
            ->andWhere('c.User = :user_id')
            ->setParameter(':user_id', $userId);
        }
        return $qb->getQuery()->getSingleScalarResult();
    }
      
//    /**
//     * @return Client[] Returns an array of Client objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Client
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
