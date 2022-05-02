<?php

namespace App\Form;

use App\Entity\ImageBricosphere;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageBricosphereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', FileType::class,[
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' =>false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ImageBricosphere::class,
        ]);
    }
}
