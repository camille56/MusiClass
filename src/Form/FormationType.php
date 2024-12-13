<?php

namespace App\Form;

use App\Entity\Cours;
use App\Enumeration\EtatPublication;
use App\Enumeration\Niveau;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, [
                'required' => true,
            ])
            ->add('image',TextType::class)
            ->add('cours', EntityType::class, [
                'class'=> Cours::class,
                'choice_label'=> 'titre',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'by_reference' => false, //important pour une utilisation automatique des methodes addcour(),..
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
            // Configure your form options here
        ]);
    }
}
