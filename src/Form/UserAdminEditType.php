<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Avatar;
use App\Entity\Talent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserAdminEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email* ',
                'constraints' => new Assert\NotBlank(),
            ])          
            ->add('lastname', TextType::class, [
                'label' => 'Nom*',
                'constraints' => new Assert\NotBlank(), 
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom*',
                'constraints' => new Assert\NotBlank(), 
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse*',
                'constraints' => new Assert\NotBlank(), 
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville*',
                'constraints' => new Assert\NotBlank(), 
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code postal: ',
                'constraints' => new Assert\NotBlank(),
                'constraints' => new Assert\Length([
                    'min' => 5,
                    'max' => 5,
                    'minMessage' => 'Le code postal doit contenir 5 caractères',
                    'maxMessage' => 'Le code postal doit contenir 5 caractères',
                ]),
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone: ',
                'required' => false,
            ])
            ->add('roles', ChoiceType::class,[
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Utilisateur' => 'ROLE_USER',
                ],
                'label' => 'Rôle: ', 
                'required' => true,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('talents', EntityType::class, [
                'class' => Talent::class,
                'label' => 'Vos talents',
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('avatar', EntityType::class, [
                'class' => Avatar::class,
                'label' => 'Choisissez l\'avatar',
                'expanded' => true,
                'multiple' => false,
            ]) 
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
