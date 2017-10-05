<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('username', TextType::class, [
                'label' => 'form.username'
            ])
            ->add('email', EmailType::class)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'form.password'],
                'second_options' => ['label' => 'form.repeat_password']
            ])
            ->add('_referer', HiddenType::class, [
                'data' => $options['data']['_referer']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'form.submit_register',
                'attr' => [
                    'class' => 'btn-success btn-lg'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_group' => ['Default', 'Registration']
        ]);
    }

    public function getBlockPrefix()
    {
        return null;
    }
}