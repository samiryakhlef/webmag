<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom',
                ],
            ])
            ->add('email', EmailType::class,
                [
                    'label' => 'Email',
                    'attr' => [
                        'placeholder' => 'E-mail',
                    ],
                ])
            ->add('sujet', TextType::class,
                [
                    'label' => 'Sujet',
                    'attr' => [
                        'placeholder' => 'Objet de votre message',
                    ],
                ])
            ->add('message', TextareaType::class,
                [
                    'label' => 'Message',
                    'attr' => [
                        'placeholder' => 'Votre message',
                    ],
                ])
            ->add('envoyer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-dark col-9 mx-auto fs-6 rounded-pill',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
