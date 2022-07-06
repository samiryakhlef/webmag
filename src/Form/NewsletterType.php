<?php

namespace App\Form;

use App\Entity\Newsletter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class NewsletterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Entrer votre adresse Email',
                ],
            ])
            ->add('Envoyer', SubmitType::class, [
                'attr' => [
                    'label' => 'Envoyer',
                    'class' => 'btn btn-outline-warning col-5 mx-auto fs-6 rounded-pill',
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Newsletter::class,
        ]);
    }
}
