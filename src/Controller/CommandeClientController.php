<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\CommandeClient;
use App\Entity\Contenir;
use App\Repository\StatutCommandeRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;

final class CommandeClientController extends AbstractController
{
    #[Route('/commande/client', name: 'app_commande_client')]
    public function index(Request $request, StatutCommandeRepository $statutCommandeRepository, ProduitRepository $produitRepository, EntityManagerInterface $entityManager): Response
    {

        if ($request->getMethod() === 'POST') {
            $commande = new CommandeClient();
            $commande->setDateCommande(new \DateTime());
            $commande->setUserId($this->getUser()->getUser());
            $commande->setStatutCommandeId($statutCommandeRepository->findOneBy(['libelle' => 'transmise']));
            $entityManager->persist($commande);
           
            $panier = json_decode($request->getContent(), true);
            foreach($panier as $produit){
                $produitId = $produit['articleId'];
                $quantite = $produit['quantiter'];
                $contenir = new Contenir();
                $contenir->setProduit($produitRepository->find($produitId));
                $contenir->setQuantite($quantite);
                $contenir->setCommandeClient($commande);
                $contenir->setPrixUnitaire($produitRepository->find($produitId)->getPrix());
                $entityManager->persist($contenir);
            }

              $entityManager->flush();
              return $this->json(['status' => 'success']);
           
        }
        return $this->render('commande_client/index.html.twig', [
            'controller_name' => 'CommandeClientController',
        ]);
    }

    #[Route('/commande/client/payee', name: 'app_commande_client_payee')]
    public function payer(): Response
    {
        return $this->render('commande_client/payee.html.twig', [
            'controller_name' => 'CommandeClientController',
        ]);
    }

    #[Route('/commande/client/admin', name: 'app_commande_client_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function admin(){
        return $this->render('commande_client/admin.html.twig', [
            'controller_name' => 'CommandeClientController',
        ]);
    }

}
