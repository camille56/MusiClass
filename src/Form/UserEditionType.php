<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\User;
use App\Enumeration\Instrument;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserEditionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [])
            ->add('prenom', TextType::class, [])
            ->add('telephone', TextType::class, [])
            ->add('commentaire', TextareaType::class, [])
            ->add('instrument', EnumType::class, [
                'class' => Instrument::class,
            ])
            ->add('formations', EntityType::class, [
                'class' => Formation::class,
                'choice_label' => 'nom',
                'by_reference' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => ['user' => 'ROLE_USER', 'admin' => 'ROLE_ADMIN'],
                'label' => 'Roles',
                'expanded' => true, // Cases à cocher
                'multiple' => true, // Plusieurs choix possibles
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
