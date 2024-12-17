<?php

namespace App\Controller\Admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("IS_AUTHENTICATED")]
#[Route('/admin/formation')]
class FormationController extends AbstractController
{
    #[Route('/{id}/edit', name: 'app_admin_formation_edit', methods: ['GET', 'POST'])]
    #[Route('', name: 'app_admin_formation',methods: ['GET', 'POST'])]
    public function index(Request $request,EntityManagerInterface $manager, ?Formation $formation): Response
    {
        $formation ??= new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $dateCreation = new \DateTimeImmutable();
            $formation->setDateCreation($dateCreation);

            $manager->persist($formation);
            $manager->flush();

            return $this->redirectToRoute('app_admin_formation');
        }


        return $this->render('admin/formation/new.html.twig', [
            'form'=>$form,
        ]);
    }

    #[Route('/liste', name: 'app_admin_listeFormation')]
    public function listeFormation(FormationRepository $formationRepository): Response
    {
        $formations = $formationRepository->findAll();

        return $this->render('admin/formation/liste.html.twig', [
            'formations'=>$formations,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_formation_show', requirements: ['id'=>'\d+'], methods: ['GET'])]
    public function detailFormation(Formation $formation): Response{

        return $this->render('admin/formation/detail.html.twig', [
            'formation'=>$formation,
        ]);

    }

}
