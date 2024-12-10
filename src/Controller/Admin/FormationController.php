<?php

namespace App\Controller\Admin;

use App\Entity\Formation;
use App\Form\FormationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/formation')]
class FormationController extends AbstractController
{
    #[Route('', name: 'app_admin_formation')]
    public function index(Request $request,EntityManagerInterface $manager): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);


        return $this->render('admin/formation/new.html.twig', [
            'form'=>$form,
        ]);
    }

    #[Route('/liste', name: 'app_admin_listeFormation')]
    public function listeFormation(): Response
    {
        return $this->render('admin/formation/liste.html.twig', [

        ]);
    }

}
