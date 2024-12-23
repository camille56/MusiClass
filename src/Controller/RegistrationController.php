<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserEditionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/register')]
class RegistrationController extends AbstractController
{
    #[Route('/{id}/edit_mdp', name: 'app_register_edit_mdp', methods: ['GET', 'POST'])]
    #[Route('', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, ?User $user): Response
    {

        $user ??= new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $dateInscription = new \DateTimeImmutable();
            $user->setDateInscription($dateInscription);

            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_register');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/liste', name: 'app_liste_user', methods: ['GET', 'POST'])]
    public function listeUsers(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();
        return $this->render('registration/listeUsers.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_register_edit', methods: ['GET', 'POST'])]
    public function editionUtilisateur(Request $request, EntityManagerInterface $entityManager, ?User $user): \Symfony\Component\HttpFoundation\RedirectResponse|Response
    {

        $user ??= new User();
        $form = $this->createForm(UserEditionType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_register');
        }

        return $this->render('registration/editionUtilisateur.html.twig', [
            'form' => $form,
            'user_id' =>$user->getId(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_register_delete', methods: ['POST'])]
    public function SuppressionUser(EntityManagerInterface $entityManager, User $user):RedirectResponse
    {
        if (!$user) {
            $this->addFlash('error', 'Utilisateur non trouvé!');
            return $this->redirectToRoute('app_liste_user');
        }

        // Supprimer l'utilisateur
        $entityManager->remove($user);
        $entityManager->flush();

        // Ajouter un message flash de succès
        $this->addFlash('success', 'Utilisateur supprimé avec succès!');

        // Rediriger vers la liste des utilisateurs ou autre page
        return $this->redirectToRoute('app_liste_user');
    }
}
