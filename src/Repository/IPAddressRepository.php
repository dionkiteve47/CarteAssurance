<?php

namespace App\Repository;

use App\Entity\IPAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IPAddress>
 *
 * @method IPAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method IPAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method IPAddress[]    findAll()
 * @method IPAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IPAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IPAddress::class);
    }
    public function searchByIp($searchQuery)
    {
        return $this->createQueryBuilder('u')
            ->where('u.Address LIKE :query')
            ->setParameter('query', '%'.$searchQuery.'%')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return IPAddress[] Returns an array of IPAddress objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?IPAddress
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
