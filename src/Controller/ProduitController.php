<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit')]
    public function index(Request $request, ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {

        $action = $request->query->get("action", null);
        $maxPrice = ceil($produitRepository->getMaxPrice()[0]['prix']);
        $allCategories = $categorieRepository->getCategoriesForFilter();

        if ($action == "filter") {
    
            $name = (string) trim($request->query->get("name", ""));
            $order = $request->query->get("order", "null");
            $min = (int) $request->query->get("min");
            $max;

            $categorie = $request->query->get("categorie");
            $categorie = $categorie ? (int) $categorie : null;
    
            if ($request->query->get("max")){
                $max = (int) $request->query->get("max");
            }else {
                $max = $maxPrice;
            }
        }else {

            $name = "";
            $order = "null";
            $min = 0;
            $max = $maxPrice;
            $categorie = null;

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
    public function show(int $id, ProduitRepository $produitRepository): Response
    {
        $produit = $produitRepository->find($id);
        $images = $produit->getImage();
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
            'images' => $images,
        ]);
    }
}