<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/register')]
class RegistrationController extends AbstractController
{
    #[Route('/{id}/edit', name: 'app_register_edit', methods: ['GET', 'POST'])]
    #[Route('', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, ?User $user): Response
    {
//        //Création de la variable à transmettre au template pour obtenir la route (la finalité étant d'afficher ou non les champs mail et mdp dans le formulaire).
//        $currentRoute=null;
//        if (!$user){
//            $currentRoute="app_register";
//        }else{
//            $currentRoute="app_register_edit";
//        }

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

            return $this->redirectToRoute('app_index');
        }

        return $this->render('registration/register.html.twig', [
//            'current_route' => $currentRoute,
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

}
