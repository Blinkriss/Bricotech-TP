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
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email ',
                'constraints' => new Assert\NotBlank(),
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent etre identiques.',
                'first_options'  => ['label' => 'Modifier le mot de passe'],
                'second_options' => ['label' => 'Confirmer le nouveau mot de passe'],
                'required' => false,
                'mapped' => false,
                'constraints' => new Assert\Length([
                    'min' => 8,
                    'max' => 50,
                    'minMessage' => 'Le mot de passe doit aux moins contenir 8 caractères',
                    'maxMessage' => 'Le mot de passe ne doit pas dépasser 50 caractères',
                ])    
            ])
            ->add('address', null, [
                'label' => 'Votre adresse ',
                'constraints' => new Assert\NotBlank,
            ])
            ->add('city', null, [
                'label' => 'Votre ville ',
                'constraints' => new Assert\NotBlank,
            ])
            ->add('postalCode', null, [
                'label' => 'Votre code postal ',
                'constraints' => new Assert\NotBlank,
                'constraints' => new Assert\Length([
                    'min' => 5,
                    'max' => 5,
                    'minMessage' => 'Le code postal doit contenir 5 caractères',
                    'maxMessage' => 'Le code postal doit contenir 5 caractères',
                ]),
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone ',
                'required' => false,
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
