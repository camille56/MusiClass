<?php

namespace App\Form;

use Doctrine\DBAL\Types\DateImmutableType;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class)
            ->add('texte', TextareaType::class)
            ->add('video', FileType::class, [
                    'label' => 'Vidéo (MP4 uniquement)',
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\File([
                                'maxSize' => '50M', // Limite de taille
                                'mimeTypes' => [
                                    'video/mp4',
                                ],
                                'mimeTypesMessage' => 'Veuillez uploader une vidéo au format MP4 uniquement',
                            ]
                        )
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
