<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Messages;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class MessagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /* ->add('recipient', EntityType::class, [
                'label' => 'Destinataire',
                "class" => User::class,
                "choice_label" => "firstname",
                "attr" => [
                    "class" => "form-control"
                ],
            ]) */
            ->add('title', TextType::class, [
                'label' => 'Sujet',
                "attr" => [
                    "class" => "form-control"
                ],
            ])
            
            ->add('message',  CKEditorType::class, [
                'config' => array('toolbar'=>'basic'),
                'label' => 'Votre message',
                "attr" => [
                    "class" => "form-control"
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'min' => 5,
                        'max' => 500,
                        'minMessage' => 'Votre message doit au moins contenir 5 caractères',
                        'maxMessage' => 'Votre message ne doit pas dépasser 500 caractères',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Messages::class,
        ]);
    }
}
