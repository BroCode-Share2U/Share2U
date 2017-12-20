<?php

namespace Form;

use Model\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;

class CommentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'rating',
            ChoiceType::class,
            [
                'choices' => [
                    1 => '1',
                    2 => '2',
                    3 => '3',
                    4 => '4',
                    5 => '5'
                ],
                'choice_label' => function (){
                return ' ';
                },
                'constraints' => [
                    new Assert\Choice([0, 1, 2, 3, 4, 5])
                ],
                'expanded' => true
            ]
        )->add(
            'text',
            TextareaType::class, [
                'constraints' => [
                    new Assert\Length([
                        'max' => 280
                    ])
                ],
                'required' => false
            ]
        );

        if ($options['standalone']) {
            $builder->add('submit', SubmitType::class);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Comment::class);
        $resolver->setDefault('standalone', false);

        $resolver->addAllowedTypes('standalone', 'bool');
    }
}