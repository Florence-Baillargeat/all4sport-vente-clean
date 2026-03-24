<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function getAllForHome(String $name, String $order, int $min, int $max = 0, ?int $categorie = null) {

        // echo "\n";
        // echo "name : ", $name;
        // echo "\n";
        // echo "order  :", $order;
        // echo "\n";
        // echo "min  :", $min;
        // echo "\n";
        // echo "max  :", $max;
        // echo "\n";
        // echo "cat  :", $categorie;


        $sql = $this->createQueryBuilder('p')
                    ->select('p.id, p.libelle, p.prix, p.description, c.libelle as categorie, MIN(i.url) as url')
                    ->join('p.categorie', 'c')
                    ->leftJoin('p.image', "i")
                    ->where('p.libelle like :name')
                    ->andwhere('p.prix >= :min')
                    ->setParameter('name', "%$name%")
                    ->setParameter('min', $min)
                    ->groupBy('p.id')
                    ;
                    
                    
        if ($max > 0 ) {
                        $sql ->andwhere('p.prix <= :max')
                        ->setParameter('max', $max);
        }

        if (strtoupper($categorie) != null || strtoupper($categorie) != "") {
            $sql->andwhere("c.id = :cat")
                ->setparameter("cat", $categorie);
        }

        if (strtoupper($order) === 'ASC' || strtoupper($order) === 'DESC') {
            $sql->orderBy('p.prix', strtoupper($order));
        }

        return $sql->getQuery()->getResult();
    }   

    public function getMaxPrice() {

        $sql = $this->createQueryBuilder('p')
                    ->select('p.prix')
                    ->join('p.categorie', 'c')
                    ->orderBy('p.prix', 'desc')
                    ->setMaxResults(1)
                    ;


        return $sql->getQuery()->getResult();
    }   


    public function getPanierWithJson( array $listId = []) {

        $sql = $this->createQueryBuilder("p")
                    ->select("p.id, p.libelle, p.prix")
                    ->join('p.categorie', 'c')
                    ->leftJoin('p.image', "i")
                    ->where("p.id IN (:listId)")
                    ->setParameter('listId', $listId)
                    ->groupBy('p.id');
        
        return $sql->getQuery()->getResult();

    }


    public function getProductImages(?int $id) {
        if ($id === null) {
            return ["no id"];
        }
        $sql = $this->createQueryBuilder("p")
                    ->select("i.url, i.id")
                    ->leftJoin('p.image', "i")
                    ->where('p.id = :id')
                    ->setParameter('id', $id);

        return $sql->getQuery()->getResult();
    }

//    public function getProductStockWeb(int $id) {
//        $sql = $this->createQueryBuilder("p")
//            ->join('p.entreposer','e')
//            ->join('e.entrepot','en')
//            ->select('e.quantite')
//            ->where('p.id = :id')
//            ->andWhere('en.web = true')
//            ->setParameter('id', $id); 
//        return $sql->getQuery()->getOneOrNullResult();
//
//    }  
//    
//    public function getProductStockPhysique(int $id) {
//        $sql = $this->createQueryBuilder("p")
//            ->join('p.entreposer','e')
//            ->join('e.entrepot','en')
//            ->select('en.nom','e.quantite')
//            ->where('p.id = :id')
//            ->andWhere('en.web = 0')
//            ->setParameter('id', $id); 
//        return $sql->getQuery()->getResult();
//
//    }

      
    

  
}
