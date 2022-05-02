<?php

namespace App\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class BlogArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('title', TextType::class,[
                'label' => 'Titre',
                'constraints' => [ 
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'min' => 0,
                        'max' => 200,
                    ]),
                ]
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'Contenu',
                'constraints' => new Assert\NotBlank(),
            ])
            
            ->add('blogCategory', null,[
                'label' => 'Catégorie ',
                'expanded' => false,
                'multiple' => false,
            ])
            // ->add('createdAt')
            // ->add('updatedAt')
            // ->add('file', FileType::class, [
            //     'required' => true,
            // ]) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
