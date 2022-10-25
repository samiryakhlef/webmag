<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Votre nom',
                ],
                "constraints" => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez saisir votre nom',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'Votre nom doit faire au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 30,
                    ]),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Votre Prénom',
                ],
                "constraints" => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez saisir votre prénom',
                    ]),
                    new  Assert\Length([
                        'min' => 2,
                        'minMessage' => 'Votre prénom doit faire au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 30,
                    ]),
                ],
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'attr' => [
                    'placeholder' => 'Choisir un Pseudo',
                ],
                "constraints" => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez saisir votre pseudo',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'Votre pseudo doit faire au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 30,
                    ]),
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Votre Email',
                ],
                "constraints" => [
                    new Assert\Email(),
                    new Assert\NotBlank([
                        'message' => 'Veuillez saisir votre email',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'Votre email doit faire au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 30,
                    ]),
                ],
            ])
            ->add('RGPDConsent', CheckboxType::class, [
                'label' => 'J\'accepte les conditions d\'utilisation',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ])
            ->add(
                'Password',
                RepeatedType::class,
                [
                    "type" => PasswordType::class,
                    "first_options" => [
                        "label" => "Mot de passe",
                        'attr' => [
                            'placeholder' => 'Choisir un mot de passe',
                        ],
                        "constraints" => [
                            new Assert\NotBlank([
                                'message' => 'Veuillez saisir votre mot de passe',
                            ]),
                            new Assert\Length([
                                'min' => 6,
                                'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères',
                                // max length allowed by Symfony for security reasons
                                'max' => 30,
                            ]),
                        ],
                    ],
                    "second_options" => [
                        "label" => "Confirmer le mot de passe",
                        'attr' => [
                            'placeholder' => 'Confirmer votre mot de passe',
                        ],
                        "invalid_message" => "Les mots de passe ne correspondent pas",

                    ],
                ],
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
