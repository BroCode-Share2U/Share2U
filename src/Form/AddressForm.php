<?php

namespace Form;

use Model\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class AddressForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'address1',
            TextType::class,
            [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'max' => 32
                    ])
                ]
            ]
        )->add(
            'address2',
            TextType::class, [
                'constraints' => [
                    new Assert\Length([
                        'max' => 32
                    ])
                ],
                'required' => false
            ]
        )->add(
            'zipCode',
            TextType::class,
            [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'max' => 10
                    ]),
//                    new Assert\Regex([
//                        'pattern' => '/^$/'
//                    ])
                ]
            ]
        )->add(
            'city',
            TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'max' => 64
                    ])
                ]
            ]
        )->add(
            'country',
            TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
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
        $resolver->setDefault('data_class', Address::class);
        $resolver->setDefault('standalone', false);

        $resolver->addAllowedTypes('standalone', 'bool');
    }
}