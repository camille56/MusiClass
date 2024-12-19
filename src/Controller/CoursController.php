<?php

namespace App\Controller;

use App\Entity\Cours;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/cours')]
class CoursController extends AbstractController
{
//    #[Route('', name: 'app_cours')]
//    public function index(): Response
//    {
//        return $this->render('cours/index.html.twig', [
//            'controller_name' => 'CoursController',
//        ]);
//    }

    #[Route('detail/{id}', name: 'app_cours_detailcours',requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detailCours(Cours $cours): Response
    {


        return $this->render('cours/detailCours.html.twig', [
            'cours' => $cours,
        ]);
    }
}
