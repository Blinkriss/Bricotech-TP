<?php

namespace App\Form;

use App\Entity\Tool;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class ToolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, [
                'label' => 'Nom de votre outil',
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length([
                            'min' => 5,
                            'max' => 100,
                            'minMessage' => 'Le nom de l\'outil doit au moins contenir 5 caractères',
                            'maxMessage' => 'Le nom de l\'outil ne doit pas dépasser 100 caractères',
                        ]),
                    ]
            ])
            ->add('description', CKEditorType::class, [
                'config' => array('toolbar'=>'basic'),
                'label' => 'Description de votre outil',
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length([
                            'min' => 5,
                            'max' => 500,
                            'minMessage' => 'La description de l\'outil doit au moins contenir 5 caractères',
                            'maxMessage' => 'La description de l\'outil ne doit pas dépasser 500 caractères',
                        ]),
                    ]
            ])
            ->add('brand', TextType::class, [
                'label' => 'Marque',
                'constraints' => new Assert\NotBlank(),
            ])
            ->add('toolCategory', null, [
                'label' => 'Catégorie ',
                'expanded' => false,
                'multiple' => true,
            ])
            ->add('images', FileType::class,[
                'label' => 'Ajouter une image pour votre outil',
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tool::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
