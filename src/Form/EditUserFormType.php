<?php

namespace App\Form;

use App\Entity\IPAddress;
use App\Entity\User;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class EditUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['attr' => [ 
                'class' => 'form-control'
            ]]
            )
            ->add('lastname',TextType::class, ['attr' => [
                'class' => 'form-control'
             ]]
             )
            ->add('firstname',TextType::class, ['attr' => [
                'class' => 'form-control'
             ]]
             )
            ->add('cin',TextType::class, ['attr' => [
                'class' => 'form-control'
             ]]
             )
            ->add('naissance', DateType::class,array(
                'widget' => 'choice',
                'years' => range(date('Y')-100, date('Y')),
                'months' => range(date('m'), 12),
                'days' => range(date('d'), 31)), ['attr' => [
                    'class' => 'form-control'
            ]]
             )
            ->add('telephone',TextType::class, ['attr' => [
                'class' => 'form-control'
             ]]
             )
             ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => false,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('ipAddresses', EntityType::class, [
                'class' => IPAddress::class,
                'choices' => $options['available_ip_addresses'], // Use the available IP addresses as choices
                'choice_label' => 'address', // Use the 'address' property of IPAddress as the label
                'label' => 'Select IP Addresses',
                'expanded' => true,
                'multiple' => true,
                'attr' => ['class' => 'form-control'],
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'available_ip_addresses'=>null,
        ]);
        $resolver->setAllowedTypes('available_ip_addresses', 'array');
    }
}
