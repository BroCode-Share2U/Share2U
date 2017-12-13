<?php

namespace Form;

use Model\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of AddressForm
 */
class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'firstname',
            TextType::class,
            [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ]
        )->add(
            'lastname',
            TextType::class,
            [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ]
        )->add(
            'username',
            TextType::class,
            [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^[A-Za-z0-9_-]$/'
                    ])
                ]
            ]
        )->add(
            'description',
            TextareaType::class, [
                'constraints' => [
                    new Assert\Length([
                        'max' => 280
                    ])
                ]
            ]
        )->add(
            'email',
            EmailType::class, [
                'constraints' => [
                    new Assert\Email(),
                    new Assert\Length([
                        'max' => 64
                    ])
                ]
            ]
        )->add(
            'gender',
            ChoiceType::class, [
                'choices' => [
                    0 => 'male',
                    1 => 'female',
                    2 => 'other',
                    3 => 'unspecified'
                ],
                'constraints' => [
                    new Assert\Choice([0, 1, 2, 3])
                ]
            ]
        )->add(
            'password',
            RepeatedType::class,
            [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => [
                    'label' => 'Password'
                ],
                'second_options' => [
                    'label' => 'Repeat password'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'min' => 3,
                        'max' => 64
                    ])
                ]
            ]
        );

        if ($options['standalone']) {
            $builder->add('submit', SubmitType::class);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', User::class);
        $resolver->setDefault('standalone', false);

        $resolver->addAllowedTypes('standalone', 'bool');
    }
}
