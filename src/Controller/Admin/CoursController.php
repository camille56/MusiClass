<?php

namespace App\Controller\Admin;

use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;


#[IsGranted("IS_AUTHENTICATED")]
#[Route('/admin/cours')]
class CoursController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/cours/{id}',name: 'app_admin_cours_editcours',requirements: ['id'=>'\d+'], methods: ['GET', 'POST'],)]
    #[Route('', name: 'app_admin_cours_newCours')]
    public function newCours(Request $request, EntityManagerInterface $manager, ?Cours $cours,SluggerInterface $slugger): Response{

        $cours ??= new Cours();

        $form = $this->createForm(CoursType::class,$cours );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Gestion de la date de création
            $dateCreation = new \DateTimeImmutable();
            $cours->setDateCreation($dateCreation);

            //Gestion de la vidéo
            $fichierVideo=$form->get('video')->getData();

            if ($fichierVideo){

                //suppression de l'ancienne vidéo si elle existe
                if ($cours->getVideo()){
                    $ancienneVideo =$this->getParameter('dossier_uploads').'/'.$cours->getVideo();
                    if (file_exists($ancienneVideo)){
                        unlink($ancienneVideo);
                    }
                }

                //génération du nom de la vidéo avec in identifiant unique

                $nomBaseVideo=pathinfo($fichierVideo->getClientOriginalName(),PATHINFO_FILENAME);//permet d'avoir le nom du fichier téléchargé.
                $nomBaseSecuriseVideo=$slugger->slug($nomBaseVideo);//permet de transformer le string pour être compatible avec le système de fichier et urls
                $extensionVideo=$fichierVideo->guessExtension(); //permet d'avoir l'extension du fichier en fonction de son type mime
                $nomVideo=$nomBaseSecuriseVideo.'-'.uniqid().'.'.$extensionVideo; //uniqid() génère un string unique comme "6762fe9aee198". Basé sur l'heure


                //Déplacement du fichier dans le dossier des uploads
                try {
                    $fichierVideo->move(
                        $this->getParameter('dossier_uploads'), //le dossier upload est défini dans services.yaml
                        $nomVideo
                    );

                    //Définition du nom de la vidéo en bdd
                    $cours->setVideo($nomVideo);

                }catch(FileException $e){
                    throw new Exception('Erreur lors du téléchargement de la vidéo. '.$e->getMessage());
                }

            }

            //envoi BDD
            $manager->persist($cours);
            $manager->flush();

            return $this->redirectToRoute('app_admin_cours_newCours');
        }

        return $this->render('admin/cours/new.html.twig', ['form'=>$form]);
    }

    #[Route('/liste', name: 'app_admin_cours_listecours')]
    public function listeCours(CoursRepository $coursRepository): Response
    {
        $listeCours = $coursRepository->findAll();
        return $this->render('admin/cours/liste.html.twig', ['listeCours'=>$listeCours]);
    }

    #[Route('/detail/{id}', name: 'app_admin_cours_detail', requirements: ['id'=>'\d+'], methods: ['GET', 'POST'])]
    public function detailCours(?Cours $cours): Response{

        return $this->render('admin/cours/detail.html.twig', ['cours'=>$cours]);
    }


}
