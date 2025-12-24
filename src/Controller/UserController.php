<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    #[IsGranted('ROLE_USER')]
    public function profile(): Response
    {
        $user = $this->getUser();

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'workshops' => $user->getWorkshops(),
        ]);
    }

    #[Route('/user/{id}', name: 'app_user_profile_view')]
    public function viewProfile(User $user): Response
    {
        $currentUser = $this->getUser();
        $canViewContact = $currentUser && ($this->isGranted('ROLE_ADMIN') || $currentUser === $user);

        return $this->render('user/profile_view.html.twig', [
            'user' => $user,
            'workshops' => $user->getWorkshops(),
            'canViewContact' => $canViewContact,
        ]);
    }

    #[Route('/profile/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function editProfile(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();

        if ($request->isMethod('POST')) {
            $username = $request->request->get('username');
            $description = $request->request->get('description');
            $phone = $request->request->get('phone');
            $address = $request->request->get('address');

            if ($username) {
                $user->setUsername($username);
            }
            $user->setDescription($description);
            $user->setPhone($phone);
            $user->setAddress($address);

            // Handle avatar upload
            /** @var UploadedFile $avatarFile */
            $avatarFile = $request->files->get('avatar');
            if ($avatarFile) {
                // Validate file size (2MB max)
                $maxSize = 2 * 1024 * 1024; // 2MB in bytes
                if ($avatarFile->getSize() > $maxSize) {
                    $this->addFlash('error', 'Le fichier est trop volumineux. Taille maximum: 2MB');
                    return $this->redirectToRoute('app_profile_edit');
                }

                // Validate MIME type (only images)
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $mimeType = $avatarFile->getMimeType();
                if (!in_array($mimeType, $allowedMimeTypes)) {
                    $this->addFlash('error', 'Format de fichier non autorisé. Utilisez JPG, PNG, GIF ou WebP.');
                    return $this->redirectToRoute('app_profile_edit');
                }

                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $avatarFile->guessExtension();

                // Use parameter for upload directory
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/avatars';
                
                $avatarFile->move($uploadDir, $newFilename);

                // Delete old avatar if exists
                if ($user->getAvatar()) {
                    $oldAvatarPath = $uploadDir . '/' . $user->getAvatar();
                    if (file_exists($oldAvatarPath)) {
                        unlink($oldAvatarPath);
                    }
                }

                $user->setAvatar($newFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès !');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('user/profile_edit.html.twig', [
            'user' => $user,
        ]);
    }
}