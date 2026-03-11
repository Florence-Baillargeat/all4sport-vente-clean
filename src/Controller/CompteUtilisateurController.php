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
public function update(EntityManagerInterface $entityManager, int $id, Request $request
):Response{
    $credential = $this->getUser();

    if (!$credential) {
        throw $this->createAccessDeniedException();
    }

    $user = $credential->getUser();

    $form = $this->createForm(UserProfileType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        $this->addFlash('success', 'Vos informations ont été mises à jour.');
        return $this->redirectToRoute('app_compte_utilisateur');
    }

}
}