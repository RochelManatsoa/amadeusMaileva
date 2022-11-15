<?php

namespace App\Form;

use App\Entity\Client;
use App\Form\AddressFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', null, ['label' => 'Nom *'])
            ->add('lastName', null, ['label' => 'Prénom *'])
            ->add('mobile', null, ['label' => 'Mobile'])
            ->add('address', AddressFormType::class)
            // ->add('user', )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
