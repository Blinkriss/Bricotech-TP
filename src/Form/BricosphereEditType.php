<?php

namespace App\Form;

use App\Entity\Bricosphere;
use App\Entity\ImageBricosphere;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class BricosphereEditType extends AbstractType
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
                'expanded' => true,
                'multiple' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bricosphere::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
