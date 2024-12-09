<?php

namespace App\Controller\Admin;

use App\Entity\Cours;
use App\Form\CoursType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/admin/cours')]
class CoursController extends AbstractController
{
//    #[Route('', name: 'app_admin_cours')]
//    public function index(): Response
//    {
//        return $this->render('admin/cours/index.html.twig', [
//            'controller_name' => 'CoursController',
//        ]);
//    }

    #[Route('', name: 'app_admin_cours_newCours')]
    public function newCours(Request $request, EntityManagerInterface $manager): Response{

        $cours = new Cours();

        $form = $this->createForm(CoursType::class,$cours );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $dateCreation = new \DateTimeImmutable();
            $cours->setDateCreation($dateCreation);

            $manager->persist($cours);
            $manager->flush();

            return $this->redirectToRoute('app_admin_cours_newCours');
        }

        return $this->render('admin/cours/new.html.twig', ['form'=>$form]);
    }
}
