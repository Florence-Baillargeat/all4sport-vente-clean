<?php

namespace App\Repository;

use App\Entity\CommandeClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommandeClient>
 */
class CommandeClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandeClient::class);
    }

    //    /**
    //     * @return CommandeClient[] Returns an array of CommandeClient objects
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

    //    public function findOneBySomeField($value): ?CommandeClient
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function getAllCommandes(int $status = null, String $search = null){
        $query = $this->createQueryBuilder('c')
            ->join('c.user_id', 'u')
            ->join('c.statutCommande_id', 's');


        if($search){
            $query->andWhere('c.id LIKE :search')
                ->setParameter('search', '%'.$search.'%');
            
            $query->orWhere('c.dateCommande LIKE :search')
                ->setParameter('search', '%'.$search.'%');

            $query->orWhere('concat(u.prenom, u.nom) LIKE :search')
                ->setParameter('search', '%'.$search.'%');

        }


        if($status !== null){
            $query->andWhere('s.id = :status')
                ->setParameter('status', $status);
        }

        $query->orderby('c.dateCommande', 'DESC');

        return $query->getQuery()->getResult();
    }
}
