<?php

namespace App\Controller;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/formations')]
class FormationsController extends AbstractController
{
    #[Route('', name: 'app_formations')]
    public function index(): Response
    {

        //récupération du user.
        $user = $this->getUser();

        //récupération des formations
        $listeFormation = null;
        if ($user) {
            $listeFormation = $user->getFormations();
        }

        return $this->render('formations/index.html.twig', [
            'user' => $user,
            'listeFormation' => $listeFormation,
        ]);
    }

    #[Route('/{id}', name: 'app_formations_detailformations', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detailFormations(Formation $formation): Response
    {
            $listeCours = $formation->getCours();

        return $this->render('formations/detailformations.html.twig', [
            'formation' => $formation,
            'listeCours' => $listeCours,
        ]);

    }
}
