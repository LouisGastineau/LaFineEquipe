<?php

namespace App\Controller;

use App\Entity\Workshop;
use App\Form\WorkshopType;
use App\Repository\WorkshopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/workshop')]
final class WorkshopController extends AbstractController
{
    #[Route(name: 'app_workshop_index', methods: ['GET'])]
    public function index(Request $request, WorkshopRepository $workshopRepository, CategoryRepository $categoryRepository): Response
    {
        $selectedCategory = $request->query->get('category');
        $categories = $categoryRepository->findAll();

        if ($selectedCategory) {
            $workshops = $workshopRepository->findByCategoryId($selectedCategory);
        } else {
            $workshops = $workshopRepository->findAllWithUsers();
        }

        return $this->render('workshop/index.html.twig', [
            'workshops' => $workshops,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
        ]);
    }

    #[Route('/new', name: 'app_workshop_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $workshop = new Workshop();
        $form = $this->createForm(WorkshopType::class, $workshop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile instanceof UploadedFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads/workshops',
                    $newFilename
                );

                $workshop->setImage($newFilename);
            }
            $entityManager->persist($workshop);
            $entityManager->flush();

            return $this->redirectToRoute('app_workshop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('workshop/new.html.twig', [
            'workshop' => $workshop,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_workshop_show', methods: ['GET'])]
    public function show(Workshop $workshop): Response
    {
        return $this->render('workshop/show.html.twig', [
            'workshop' => $workshop,
        ]);
    }

    #[Route('/workshop/{id}/edit', name: 'app_workshop_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Workshop $workshop, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(WorkshopType::class, $workshop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Déplace le fichier dans le dossier des images
                $imageFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads/workshops',
                    $newFilename
                );

                // Supprime l'ancienne image si elle existe
                if ($workshop->getImage()) {
                    $oldImagePath = $this->getParameter('kernel.project_dir') . '/public/uploads/workshops/' . $workshop->getImage();
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Sauvegarde le nouveau nom
                $workshop->setImage($newFilename);
            }

            // Supprimer l’image si demandé
            if ($form->get('deleteImage')->getData()) {
                $oldImagePath = $this->getParameter('kernel.project_dir') . '/public/uploads/workshops/' . $workshop->getImage();
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                $workshop->setImage(null);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_workshop_index');
        }

        return $this->render('workshop/edit.html.twig', [
            'form' => $form,
            'workshop' => $workshop,
        ]);
    }



    #[Route('/{id}', name: 'app_workshop_delete', methods: ['POST'])]
    public function delete(Request $request, Workshop $workshop, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $workshop->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($workshop);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_workshop_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}/register', name: 'app_workshop_registration')]
    public function register(Workshop $workshop, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $workshop->addUser($user);
        $entityManager->persist($workshop);
        $entityManager->flush();

        $this->addFlash('success', 'Inscription réussie !');


        return $this->redirectToRoute('app_workshop_index');
    }


    #[Route('/atelier/{id}/unregister', name: 'workshop_unregister')]
    public function unregister(Workshop $workshop, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($workshop->getUsers()->contains($user)) {
            $workshop->removeUser($user);
            $entityManager->flush();

            $this->addFlash('info', 'Vous avez été désinscrit de l\'atelier.');
        }

        return $this->redirectToRoute('app_workshop_index');
    }
}
