<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', TextType::class, [
                'label' => 'form.username'
            ])
            ->add('_password', PasswordType::class, [
                'label' => 'form.password'
            ])
            ->add('_remember_me', CheckboxType::class, [
                'label' => 'form.remember_me',
                'required' => false,
                'attr' => [
                    'checked' => true
                ]
            ])
            ->add('_referer', HiddenType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'form.submit_login',
                'attr' => [
                    'class' => 'btn-success btn-lg'
                ]
            ]);
    }

    public function getBlockPrefix()
    {
        return null;
    }
}