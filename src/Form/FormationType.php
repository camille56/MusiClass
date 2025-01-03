<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Formation;
use App\Enumeration\EtatPublication;
use App\Enumeration\Niveau;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, [
                'required' => true,
            ])
            ->add('image',FileType::class,[
                'required' => false, // L'image n'est pas obligatoire
                'mapped' => false, // Cela indique que ce champ ne doit pas être mappé automatiquement sur l'entité
                'constraints' => [
                    new File([
                        'maxSize' => '5M', // Limite de taille, par exemple
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG, GIF)',
                    ])
                ],
            ])
            ->add('cours', EntityType::class, [
                'class'=> Cours::class,
                'choice_label'=> 'titre',
                'choices'=> $options['listeCoursDisponibles'],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'by_reference' => false, //important pour une utilisation automatique des méthodes addcour(),..
            ])
            ->add('public',CheckboxType::class)
            ->add('niveau',EnumType::class,
                [
                    'class' => Niveau::class,
                ])
            ->add('etat_publication',EnumType::class,
                [
                    'class' => EtatPublication::class,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class, // Modèle de données du formulaire
            'listeCoursDisponibles' => [], // Valeur par défaut pour l'option personnalisée
        ]);

        $resolver->setAllowedTypes('listeCoursDisponibles', 'array'); // Valide que l'option est un tableau
    }
}
