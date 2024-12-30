<?php

namespace App\Controller\Admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\CoursRepository;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted("IS_AUTHENTICATED")]
#[Route('/admin/formation')]
class FormationController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('', name: 'app_admin_formation',methods: ['GET', 'POST'])]
    public function index(Request $request,EntityManagerInterface $manager, SluggerInterface $slugger, CoursRepository $coursRepository): Response
    {
        $formation = new Formation();

        //récupération des cours disponibles ou étant associé à la formation.
        $listeCoursDisponibles=null;
        $listeCoursDisponibles = $coursRepository->getCoursDisponibles();

        // On utilise un tableau d'options pour transmettre au formulaire les cours disponibles.
        // Le formulaire attend une entrée nommée 'listeCoursDisponibles' dans les options passées à la méthode createForm.
        // Ces données seront ensuite utilisées pour remplir le champ 'cours' du formulaire dans le template.
        // Ce champ 'cours' affichera les cours disponibles dans l'option 'listeCoursDisponibles'.
        $form = $this->createForm(FormationType::class, $formation, [
            'listeCoursDisponibles' => $listeCoursDisponibles,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Gestion de l'image
            $fichierImage=$form->get('image')->getData();

            if ($fichierImage){

                //suppression de l'ancienne image si elle existe
                if ($formation->getImage()){
                    $ancienneImage =$this->getParameter('dossier_uploads').'/'.$formation->getImage();
                    if (file_exists($ancienneImage)){
                        unlink($ancienneImage);
                    }
                }

                //génération du nom de l'image' avec in identifiant unique

                $nomBaseImage=pathinfo($fichierImage->getClientOriginalName(),PATHINFO_FILENAME);//permet d'avoir le nom du fichier téléchargé.
                $nomBaseSecuriseVideo=$slugger->slug($nomBaseImage);//permet de transformer le string pour être compatible avec le système de fichier et urls
                $extensionImage=$fichierImage->guessExtension(); //permet d'avoir l'extension du fichier en fonction de son type mime
                $nomImage=$nomBaseSecuriseVideo.'-'.uniqid().'.'.$extensionImage; //uniqid() génère un string unique comme "6762fe9aee198". Basé sur l'heure


                //Déplacement du fichier dans le dossier des uploads
                try {
                    $fichierImage->move(
                        $this->getParameter('dossier_uploads'), //le dossier upload est défini dans services.yaml
                        $nomImage
                    );

                    //Définition du nom de la vidéo en bdd
                    $formation->setImage($nomImage);

                }catch(FileException $e){
                    throw new Exception('Erreur lors du téléchargement de l\'image. '.$e->getMessage());
                }

            }

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

    #[Route('/{id}/edit', name: 'app_admin_formation_edit', methods: ['GET', 'POST'])]
    public function editFormation(Request $request,EntityManagerInterface $manager, ?Formation $formation, SluggerInterface $slugger, CoursRepository $coursRepository): Response
    {
        $formation ??= new Formation();

        //récupération des cours disponibles ou étant associé à la formation.
        $listeCoursDisponibles=null;
        $listeCoursDisponibles = $coursRepository->getCoursDisponibles($formation->getId());

        $form = $this->createForm(FormationType::class, $formation, [
            'listeCoursDisponibles' => $listeCoursDisponibles,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Gestion de l'image
            $fichierImage=$form->get('image')->getData();

            if ($fichierImage){

                //suppression de l'ancienne image si elle existe
                if ($formation->getImage()){
                    $ancienneImage =$this->getParameter('dossier_uploads').'/'.$formation->getImage();
                    if (file_exists($ancienneImage)){
                        unlink($ancienneImage);
                    }
                }

                //génération du nom de l'image' avec in identifiant unique

                $nomBaseImage=pathinfo($fichierImage->getClientOriginalName(),PATHINFO_FILENAME);//permet d'avoir le nom du fichier téléchargé.
                $nomBaseSecuriseVideo=$slugger->slug($nomBaseImage);//permet de transformer le string pour être compatible avec le système de fichier et urls
                $extensionImage=$fichierImage->guessExtension(); //permet d'avoir l'extension du fichier en fonction de son type mime
                $nomImage=$nomBaseSecuriseVideo.'-'.uniqid().'.'.$extensionImage; //uniqid() génère un string unique comme "6762fe9aee198". Basé sur l'heure


                //Déplacement du fichier dans le dossier des uploads
                try {
                    $fichierImage->move(
                        $this->getParameter('dossier_uploads'), //le dossier upload est défini dans services.yaml
                        $nomImage
                    );

                    //Définition du nom de la vidéo en bdd
                    $formation->setImage($nomImage);

                }catch(FileException $e){
                    throw new Exception('Erreur lors du téléchargement de l\'image. '.$e->getMessage());
                }

            }

            $dateCreation = new \DateTimeImmutable();
            $formation->setDateCreation($dateCreation);

            $manager->persist($formation);
            $manager->flush();

            return $this->redirectToRoute('app_admin_formation');
        }



        return $this->render('admin/formation/edit.html.twig', [
            'form'=>$form,
            'formation'=>$formation,
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

    #[Route('/{id}/delete', name: 'app_admin_formation_suppression', methods: ['POST'])]
    public function SuppressionUser(EntityManagerInterface $entityManager, Formation $formation):RedirectResponse
    {
        if (!$formation) {
            $this->addFlash('error', 'Utilisateur non trouvé!');
            return $this->redirectToRoute('app_admin_listeFormation');
        }

        // Supprimer l'utilisateur
        $entityManager->remove($formation);
        $entityManager->flush();

        // Ajouter un message flash de succès
        $this->addFlash('success', 'Formation supprimée avec succès!');

        // Rediriger vers la liste des utilisateurs ou autre page
        return $this->redirectToRoute('app_admin_listeFormation');
    }



}
