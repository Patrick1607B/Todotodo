<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner votre email.',
                    ]),
                    new Regex([
                        'pattern' => '/^([a-zA-Z0-9-._\-0-9]*)([@]{1})([aA-zZ.]*)([.])([aA-zZ.]{2,4})$/',
                        'message' => 'Cette email "{{ value }}" est pas valid.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Votre Email',
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identique',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password'],
                'first_options'  => 
                    ['label' => 'Mot de passe',
                    'attr' => ['placeholder' => 'Entrez votre mot de passe.'
                ],
                ],
                'second_options' => 
                    ['label' => 'Confirmation mot de passe',
                    'attr' => ['placeholder' => 'Confirmez mot de passe.'],
                ],
                'constraints' => [
                    new Regex(
                        '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                        "Il faut un mot de passe avec 8 caractères avec au moins 1 minuscule, 1 majuscule, 1chiffre et 1 caractère spécial."
                    )
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
