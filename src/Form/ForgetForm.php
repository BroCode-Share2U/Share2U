<?php

namespace Form;

use Model\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Description of AddressForm
 */
class ForgetForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $emailCallback = new Assert\Callback(
            function ($value, ExecutionContextInterface $context, $payload) {
                if (!$payload->emailExists($value)) {
                    $context
                      ->buildViolation('No user account was found with that email address.')
                      ->atPath('email')
                      ->addViolation();
                }
            }
        );
        $emailCallback->payload = $options['user_repository'];

        $builder->add(
            'email',
            EmailType::class, [
                'constraints' => [
                    new Assert\Email(),
                    new Assert\Length([
                        'max' => 64
                    ]),
                    $emailCallback
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
