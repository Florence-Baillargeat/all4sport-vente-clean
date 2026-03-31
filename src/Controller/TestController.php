<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class TestController extends AbstractController
{
    #[Route('/api/getAllProduits', name: 'app_produit')]
    public function index(Request $request, ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {

				$produits = $produitRepository->findAll();				

				return $this->json($produits);
    }


}