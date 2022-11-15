<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => 'Nom du service'])
            ->add('description')
            ->add('address', null, ['label' => 'Addresse du service'])
            ->add('complement', null, ['label' => 'Complément'])
            ->add('zipCode', null, ['label' => 'Code postale *'])
            ->add('city', null, ['label' => 'Ville *'])
            ->add('country')
            ->add('slug')
            ->add('category')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
