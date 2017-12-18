<?php

namespace Form;


use Model\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ItemForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'igdbId',
            HiddenType::class,
            [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ]
        )->add(
            'plateform',
            ChoiceType::class,[
                'choices' => [
                    'PC' => 'PC',
                    'Playstatiion 4' => 'Playstatiion 4',
                    'Xbox One' => 'Xbox One',
                    'Wii U' => 'Wii U',
                    'Nintendo 3DS' => 'Nintendo 3DS',
                    'Playstation Vita' => 'Playstation Vita',
                    'Wii' => 'Wii',
                    'Playstation 3' => 'Playstation 3',
                    'Xbox 360' => 'Xbox 360',
                    'Nintendo DS' => 'Nintendo DS',
                    'Playstation Portable' => 'Playstation Portable',
                    'Playstation 2' => 'Playstation 2',
                    'Xbox' => 'Xbox',
                    'Nintendo Gamecube' => 'Nintendo Gamecube',
                    'Dreamcast' => 'Dreamcast',
                    'Gameboy Advance' => 'Gameboy Advance',
                    'N-Gage' => 'N-Gage',
                    'Neo Geo Pocket Color' => 'Neo Geo Pocket Color',
                    'Playstation' => 'Playstation',
                    'Nintendo 64' => 'Nintendo 64',
                    'Sega Saturn' => 'Sega Saturn ',
                ],
                'constraints' => [
                    new Assert\Choice([
                        'PC',
                        'Playstatiion 4',
                        'Xbox One',
                        'Wii U',
                        'Nintendo 3DS',
                        'Playstation Vita',
                        'Wii',
                        'Playstation 3',
                        'Xbox 360',
                        'Nintendo DS',
                        'Playstation Portable',
                        'Playstation 2',
                        'Xbox',
                        'Nintendo Gamecube',
                        'Dreamcast',
                        'Gameboy Advance',
                        'N-Gage',
                        'Neo Geo Pocket Color',
                        'Playstation',
                        'Nintendo 64',
                        'Sega Saturn',
                    ])
                ]
            ]
        )->add(
            'name',
            HiddenType::class,
            [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ]
        )->add(
            'summary',
            HiddenType::class,
            [
                'required' => false
            ]
        )->add(
            'cover',
            HiddenType::class, []
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
        );

        if ($options['standalone']) {
            $builder->add('submit', SubmitType::class);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Item::class);
        $resolver->setDefault('standalone', false);

        $resolver->addAllowedTypes('standalone', 'bool');
    }
}