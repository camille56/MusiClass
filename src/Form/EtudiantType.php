<?php

namespace App\Form;

use App\Entity\Formation;
use App\Enumeration\Role;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class EtudiantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => true,
            ])
            ->add('prenom', TextType::class, [
                'required' => true,
            ])
            ->add('email', EmailType::class)
            ->add('password', TextType::class, [
                'required' => true,
            ])
            ->add('login', TextType::class, [
                'required' => true,
            ])
            ->add('commentaire', TextareaType::class,[
                'required' => false,
            ])
//            ->add('date_inscription', DateType::class)
            ->add('role', \Symfony\Component\Form\Extension\Core\Type\EnumType::class,
                [
                    'class' => Role::class,
                ])
//            ->add('Formation', EntityType::class,[
//                    'class' => Formation::class,
//                    'choice_label' => 'nom',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
