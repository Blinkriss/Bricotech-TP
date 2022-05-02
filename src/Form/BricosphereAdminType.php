<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Bricosphere;
use App\Entity\ImageBricosphere;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BricosphereAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom de votre Bricosphère',
                'constraints' => [ 
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'min' => 5,
                        'max' => 50,
                    ]),
                ]
            ])
            ->add('imageBricosphere', EntityType::class, [
                'class' => ImageBricosphere::class,
                'label' => 'Choisissez une image',
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('creator', EntityType::class, [
                'class' => User::class,
                'label' => 'Choisissez le propriétaire ',
                'required' => true,
                'expanded' => false,
                'multiple' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bricosphere::class,
        ]);
    }
}
