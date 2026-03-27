<?php

namespace App\Controller;

use App\Repository\EntreposerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\CommandeClient;
use App\Entity\Contenir;
use App\Repository\StatutCommandeRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommandeClientRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\EntrepotRepository;

final class CommandeClientController extends AbstractController
{
    #[Route('/commande/client', name: 'app_commande_client')]
    public function index(Request $request, StatutCommandeRepository $statutCommandeRepository, ProduitRepository $produitRepository, EntreposerRepository $entreposerRepository, EntityManagerInterface $entityManager, EntrepotRepository $entrepotRepository): Response
    {
        try {

            if ($request->getMethod() === 'POST') {
                
                $authorized = true;
                $panier = json_decode($request->getContent(), true);
                $qttMissing = [];
                
                // Parcourir tt les produits du panier et vérifier la quantité disponible dans l'entrepôt
                // Si pas disponible mettre authorized à false et sortir de la boucle
                foreach($panier as $produit){
                    $produitId = $produit['articleId'];
                    $quantite = $produit['quantiter'];
                    $produit = $produitRepository->find($produitId);
                    $entreposer = $entreposerRepository->findOneBy(['produit' => $produit]);
                    if ($entreposer->getQuantite() < $quantite) {
                        // si la quantité disponible est inférieure à la quantité demandée, mettre authorized à false et sortir de la boucle
                        $authorized = false;
                        
                        $qttMissing[] = [
                            'name' => $produit->getLibelle(),
                            'quantiteDisponible' => $entreposer->getQuantite(),
                            'quantiteDemandee' => $quantite
                        ];

                    }
                }

                // si authorized est false retourner une réponse json avec un message d'erreur et ne pas créer la commande
                if (!$authorized) {
                    return $this->json(['status' => 'error', 'message' => 'Quantité insuffisante pour certains articles', 'details' => $qttMissing], Response::HTTP_BAD_REQUEST);
                }

            
            // Si authorized est true, créer la commande et les contenirs associés, puis mettre à jour les quantités dans l'entrepôt

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

                    $produit = $produitRepository->find($produitId);
                    $entreposer = $entreposerRepository->findOneBy(['produit' => $produit]);
                        
                    $entreposer->setQuantite($entreposer->getQuantite()- $quantite);
                    $entityManager->persist($entreposer);
    
                }

                
    
                  $entityManager->flush();
                  return $this->json(['status' => 'success']);
               
            }
            return $this->render('commande_client/index.html.twig', [
                'controller_name' => 'CommandeClientController',
            ]);
        }catch (\Exception $e) {
            return $this->json(['status' => 'error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }



    
    #[Route('/commande/client/payee', name: 'app_commande_client_payee')]
    public function payer(): Response
    {
        return $this->render('commande_client/payee.html.twig', [
            'controller_name' => 'CommandeClientController',
        ]);
    }






    #[IsGranted('ROLE_ADMIN')]
    #[Route('/commande/client/admin', name: 'app_commande_client_admin')]
    public function admin(CommandeClientRepository $CommandeClientRepository,
    StatutCommandeRepository $statutCommandeRepository, Request $request): Response{

        $action = $request->query->get('action');
        
        if ($action == "filter") {
            $statusFilter = $request->query->get('status') ?? null;
            $searchInput = trim($request->query->get('search')) ?? '';
        }else {
            $statusFilter = null;
            $searchInput = '';
        }


        $commandes = $CommandeClientRepository->getAllCommandes($statusFilter, $searchInput); 
        $allStatus = $statutCommandeRepository->findAll();

        return $this->render('commande_client/admin.html.twig', [
            'commandes' => $commandes,
            'statutsCommande' => $allStatus,
                'selectedStatus' => $statusFilter,
                'searchInput' => $searchInput
        ]);
    }


    #[IsGranted('ROLE_ADMIN')]
    #[Route('/commande/client/update', name: 'app_commande_client_update')]
    public function commandUpdate(Request $request, EntityManagerInterface $entityManager, CommandeClientRepository $commandeClientRepository, StatutCommandeRepository $statutCommandeRepository): Response
    {
        $commandeId = $request->request->get('commandeId') ?? null;
        $newStatusId = (int) $request->request->get('newStatusId') ?? null;

        echo "commandeId: $commandeId, newStatusId: $newStatusId";

        $currentCommand = $commandeClientRepository->find($commandeId);

        if (!$currentCommand) {
            return $this->redirectToRoute('app_commande_client_admin', ['error' => 'Commande non trouvée.']);
        }

        $newStatus = $statutCommandeRepository->find($newStatusId);

        if (!$newStatus) {
            return $this->redirectToRoute('app_commande_client_admin', ['error' => 'Statut de commande non trouvée.']);
        }


        $currentCommand->setStatutCommandeId($newStatus);
        $entityManager->flush();

        return $this->redirectToRoute('app_commande_client_admin', ['success' => 'Statut de la commande mis à jour avec succès.']);

    }

}
