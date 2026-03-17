<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/produitADM/crud')]
final class ProduitCrudController extends AbstractController
{
    #[Route(name: 'app_produit_crud_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit_crud/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_produit_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit_crud/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_crud_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {

        $images = $produit->getImage();

        return $this->render('produit_crud/show.html.twig', [
            'produit' => $produit,
            'images' => $images,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit_crud/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_crud_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/images', name: 'app_produit_image')]
    public function imageProduit(int $id, Request $request, ProduitRepository $produitRepository) : Response {

        $Result = $produitRepository->getProductImages($id);

        return $this->render('produit_crud/images.html.twig', [
            'images' => $Result,
            'produitId' => $id
        ]);
    }
}
