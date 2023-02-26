<?php

namespace App\Repository;

use App\Entity\Rh;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rh>
 *
 * @method Rh|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rh|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rh[]    findAll()
 * @method Rh[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RhRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rh::class);
    }

    public function add(Rh $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Rh $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

 /*   public function searchRhByCompanyId($id_company)
{
    $entityManager = $this->getEntityManager();

    $query = $entityManager->createQuery(
        'SELECT rh.name, rh.fonction, rh.phone_number, rh.email, rh.departement
         FROM App\Entity\Rh rh
         WHERE rh.id_company = :id_company'
    )->setParameter('id_company', $id_company);

    return $query->getResult();
}*/

  /*  public function findByCompanyId($idcompany)
    {
        $qb = $this->createQueryBuilder('rh');

        $qb->select('rh.name', 'rh.fonction', 'rh.phonenumber', 'rh.email', 'rh.departement')
           ->where('rh.idcompany = :idcompany')
           ->setParameter('idcompany', $idcompany);

        $query = $qb->getQuery();

        return $query->getResult();
    }*/

//    /**
//     * @return Rh[] Returns an array of Rh objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Rh
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
