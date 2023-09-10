<?php

namespace App\Form;

use App\Entity\IPAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Ip;
class IPAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Address', TextType::class, [
            'label' => 'IP Address',
            'attr' => [
                'class' => 'form-control',
            ],
            'constraints' => [
                new Ip(['version' => Ip::V4_ONLY_PUBLIC, 'message' => 'Please enter a valid IPv4 address.']),
            ],  
            ])
            ->add('Mask', TextType::class, [
            'label' => 'Mask',
            'attr' => [
                'class' => 'form-control',
            ],
            'constraints' => [
                new Ip(['version' => Ip::V4_ONLY_PUBLIC, 'message' => 'Please enter a valid Mask.']),
            ],
         ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => IPAddress::class,
        ]);
    }
}
