<?php

namespace App\Repository;

use App\Entity\Entrepot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Entrepot>
 */
class EntrepotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entrepot::class);
    }

    //    /**
    //     * @return Entrepots[] Returns an array of Entrepots objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Entrepots
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }



    public function getProductStock(int $idProduit)
    {
        $query = $this->createQueryBuilder('po')
            ->select('po.id, po.nom, po.web, COALESCE(en.quantite, 0) AS quantite')
            ->leftJoin('po.entreposer', 'en', 'WITH', 'en.produit = :idProduit')
            ->setParameter('idProduit', $idProduit);

        return $query->getQuery()->getResult();
    }

//     SELECT 
//     po.id, 
//     po.nom, 
//     po.adresse, 
//     po.web, 
//     COALESCE(en.quantite, 0) AS quantite
// FROM entrepot po
// LEFT JOIN entreposer en 
//     ON en.entrepot_id = po.id 
//     AND en.produit_id = 73;

    
}
