<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FormationController extends AbstractController
{
    #[Route('/admin/formation/new', name: 'app_admin_formation_new')]
    public function index(): Response
    {
        return $this->render('admin/formation/new/index.html.twig', [
            'controller_name' => 'etudiantController',
        ]);
    }

    #[Route('/admin/formation/liste', name: 'app_admin_formation_liste')]
    public function liste(): Response
    {
        return $this->render('admin/formation/liste/index.html.twig', [
            'controller_name' => 'listeController',
        ]);
    }

}
