<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Repository\CredentialRepository;
use App\Entity\Credential;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UserUpdateType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


final class CompteUtilisateurController extends AbstractController
{
#[Route('/compte/utilisateur', name: 'app_compte_utilisateur', methods: ['GET'])]
public function index(): Response
{
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    /** @var Credential|null $credential */
    $credential = $this->getUser();

    if (!$credential) {
        throw $this->createAccessDeniedException('Vous devez être connecté.');
    }

    $utilisateur = $credential->getUser();

    if (!$utilisateur) {
        throw new \LogicException('Credential sans entité User associée');
    }

    return $this->render('compte_utilisateur/index.html.twig', [
        'credential'  => $credential,
        'utilisateur' => $utilisateur,
    ]);
}

#[Route('/compte/utilisateur/supprimer', name: 'app_compte_delete', methods: ['POST'])]
public function delete(
    Request $request,
    EntityManagerInterface $em,
    SessionInterface $session
): Response {
    $credential = $this->getUser();

    if (!$credential) {
        throw $this->createAccessDeniedException();
    }

    $user = $credential->getUser();

    if (!$user) {
        throw new \LogicException('Credential sans entité User associée');
    }

    if (!$this->isCsrfTokenValid('delete-account-' . $credential->getId(), $request->request->get('_token'))) {
        $this->addFlash('error', 'Vérification de sécurité échouée.');
        return $this->redirectToRoute('app_compte_utilisateur');
    }

    $session->invalidate();
    $this->container->get('security.token_storage')->setToken(null);

    $em->remove($credential);
    $em->flush();


    $this->addFlash('success', 'Votre compte a été supprimé avec succès.');

    return $this->redirectToRoute('app_login');
}

#[Route('/compte/utilisateur/modification', name: 'app_compte_modif', methods: ['GET', 'POST'])]
public function update(
    Request $request,
    EntityManagerInterface $em,
    UserPasswordHasherInterface $passwordHasher
): Response {
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    /** @var Credential $credential */
    $credential = $this->getUser();

    $form = $this->createForm(UserUpdateType::class, $credential);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $changed = false;

        // Email modifié ?
/** @var Credential $credential */
$credential = $this->getUser();
$user = $credential->getUser(); // entité User liée

$form = $this->createForm(UserUpdateType::class, $credential);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
    $changed = false;

    // Email modifié ?
    $email = $form->get('email')->getData();
    if ($email !== null && $email !== '') {
        $credential->setEmail($email);
        $changed = true;
    }

    // Mot de passe modifié ?
    $plainPassword = $form->get('plainPassword')->getData();
    if ($plainPassword !== null && $plainPassword !== '') {
        $credential->setPassword(
            $passwordHasher->hashPassword($credential, $plainPassword)
        );
        $changed = true;
    }

    // Nom modifié ?
    $nom = $form->get('nom')->getData();
    if ($nom !== null && $nom !== '') {
        $user->setNom($nom);
        $changed = true;
    }

    // Prénom modifié ?
    $prenom = $form->get('prenom')->getData();
    if ($prenom !== null && $prenom !== '') {
        $user->setPrenom($prenom);
        $changed = true;
    }

    // Téléphone modifié ?
    $tel = $form->get('tel')->getData();
    if ($tel !== null && $tel !== '') {
        $user->setTel($tel);
        $changed = true;
    }

    // Sport modifié ?
    $sport = $form->get('sport')->getData();
    if ($sport !== null && $sport !== '') {
        $user->setSport($sport);
        $changed = true;
    }

    if ($changed) {
        $entityManager->flush();
    }
}

        // Le reste (profil) est automatiquement géré par Symfony grâce à property_path
        // → flush suffit si quelque chose a changé
        if ($changed || $form->get('nom')->isSubmitted()) {
            $em->flush();
            $this->addFlash('success', 'Modifications enregistrées.');
        } else {
            $this->addFlash('info', 'Aucune modification n\'a été effectuée.');
        }

        return $this->redirectToRoute('app_compte_utilisateur');
    }

    return $this->render('compte_utilisateur/update_user.html.twig', [
        'form' => $form->createView(),
    ]);
}


    #[Route('/compte/utilisateur/commandes', name: 'app_compte_commandes')]
        public function commands(){
            $commandes = $this->getUser()->getUser()->getCommandeClients()->toArray();
            usort($commandes, function($a, $b) {
                return $b->getDateCommande() <=> $a->getDateCommande();
            });
        return $this->render('compte_utilisateur/commandes.html.twig', [
            'commandes' => $commandes
        ]);

        }
    
}