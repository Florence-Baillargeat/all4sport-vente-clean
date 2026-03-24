<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\EntrepotRepository;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Entrepot;
use App\Entity\Entreposer;
use App\Repository\EntreposerRepository;

final class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit')]
    public function index(Request $request, ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {

        $action = $request->query->get("action", null);
        $allCategories = $categorieRepository->getCategoriesForFilter();
        
        $maxPriceResult = $produitRepository->getMaxPrice();

        if (!empty($maxPriceResult) && isset($maxPriceResult[0]["prix"])) {
            $maxPrice = ceil($maxPriceResult[0]["prix"]);
        } else {
            $maxPrice = 0;
        }

        if ($action == "filter") {
    
            $name = (string) trim($request->query->get("name", ""));
            $order = $request->query->get("order", "null");
            $min = (int) $request->query->get("min");
            $max = $request->query->get("max");
            $categorie = $request->query->get("categorie");
            $categorie = $categorie ? (int) $categorie : null;
        }else {

            // echo "else";

            $name = "";
            $order = "null";
            $min = 0;
            $categorie = null;
            $max = $maxPrice;
            
            }
            
            
            $produits = $produitRepository->getAllForHome($name, $order, $min, $max, $categorie);

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'name' => $name,
            'order' => $order,
            'min' => $min,
            'max' => $max,
            'maxPrice' => $maxPrice,
            'allCategories' => $allCategories, 
            'categorieSelected' => $categorie
        ]);
    }

    #[Route('/produit/{id}', name: 'app_produit_show')]
    public function show(int $id, ProduitRepository $produitRepository, EntrepotRepository $entrepotRepository): Response
    {
        $produit = $produitRepository->find($id);
        $images = $produit->getImage();

        // $stockweb = $produitRepository->getProductStockWeb($id);
        // $stockphysique = $produitRepository->getProductStockPhysique($id);
        // $endroitStockage = $produit->getEntreposer();


        $entrepots = $entrepotRepository->getProductStock($id);

        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
            'images' => $images,
            'entrepots' => $entrepots
        ]);
    }

    #[Route('/produit/addImage/{id}', name: 'app_produit_add_image')]
    public function addImage(int $id, Request $request, ProduitRepository $produitRepository, EntityManagerInterface $entityManager): Response
    {
        $produit = $produitRepository->find($id);
        $imageUrl = $request->request->get("image");
        $image = new Image();
        $image->setUrl($imageUrl);
        $image->setProduit($produit);
        $entityManager->persist($image);
        $entityManager->flush();
        return $this->redirectToRoute('app_produit_crud_show', ['id' => $id]);
    }
}