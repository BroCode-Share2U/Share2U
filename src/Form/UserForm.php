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
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Description of AddressForm
 */
class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $emailCallback = new Assert\Callback(
            function ($value, ExecutionContextInterface $context, $payload) {
                if ($payload->emailExists($value)) {
                    $context
                    ->buildViolation('This email is already taken')
                    ->atPath('email')
                    ->addViolation();
                }
            }
        );
        $emailCallback->payload = $options['user_repository'];

        $usernameExistsCallback = new Assert\Callback(
            function ($value, ExecutionContextInterface $context, $payload) {
                if ($payload->usernameExists($value)) {
                    $context
                        ->buildViolation('This username is already taken')
                        ->atPath('username')
                        ->addViolation();
                }
            }
        );
        $usernameExistsCallback->payload = $options['user_repository'];


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
                        'pattern' => '/^[A-Za-z0-9_-].*$/'
                    ]),
                    $usernameExistsCallback,
                ],
                'error_bubbling' => true
            ]
        )->add(
            'description',
            TextareaType::class, [
                'constraints' => [
                    new Assert\Length([
                        'max' => 280
                    ])
                ],
                'required' => false
            ]
        )->add(
            'email',
            EmailType::class, [
                'constraints' => [
                    new Assert\Email(),
                    new Assert\Length([
                        'max' => 64
                    ]),
                    $emailCallback
                ],
                'error_bubbling' => true
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
        $resolver->setRequired('user_repository');
        $resolver->setDefault('data_class', User::class);
        $resolver->setDefault('standalone', false);

        $resolver->addAllowedTypes('standalone', 'bool');
    }
}
