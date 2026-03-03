<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;


use App\Repository\ProduitRepository;


final class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(): Response
    {
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }


    #[Route("/getPanierJson", methods: ["POST"])]
    public function getPanierJson(Request $request, ProduitRepository $produitRepository) : Response {


        $products = $produitRepository->getPanierWithJson(json_decode($request->getContent()));

        return new Response(
        json_encode($products),
        200,
        ['Content-Type' => 'application/json']
    );
    }
}
