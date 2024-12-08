<?php

namespace App\Controller\Admin;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EtudiantController extends AbstractController
{
    #[Route('/admin/etudiant/new', name: 'app_admin_etudiant_new')]
    public function nouvelEtudiant(Request $request, EntityManagerInterface $manager): Response
    {

        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantType::class, $etudiant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dateInscription = new \DateTimeImmutable();
            $etudiant->setDateInscription($dateInscription);
            $manager->persist($etudiant);
            $manager->flush();

            return $this->redirectToRoute('app_admin_etudiant_new');
        }

        return $this->render('admin/etudiant/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/etudiant/liste', name: 'app_admin_etudiant_liste')]
    public function index(EtudiantRepository $etudiantRepository): Response
    {
        $etudiants = $etudiantRepository->findAll();

        return $this->render('admin/etudiant/liste.html.twig', [
            'etudiants' => $etudiants,
        ]);
    }

    #[Route('/admin/etudiant/{id}', name: 'app_admin_etudiant_detailEtudiant', requirements: ['id'=>'\d+'],methods: ['GET'])]
    public function detailEtudiant(?Etudiant $etudiant): Response{

        return $this->render('admin/etudiant/detail.html.twig', [
            'etudiant' => $etudiant,
        ]);
    }
}
