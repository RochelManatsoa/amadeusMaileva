<?php

namespace App\Form;

use App\Entity\Resiliation;
use App\Form\{ServiceFormType, ClientFormType};
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResiliationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('service', ServiceFormType::class, ['label' => 'A qui envoyer cette lettre de résiliation ?'])
            ->add('client', ClientFormType::class)
            ->add('type', ChoiceType::class, [
                'label' => 'Motif',
                'choices' => [
                    'Résiliation à l\'échéance' => 1,
                    'Droit de rétractation (14 jours)' => 2,
                    'Décès de l\'assuré' => 3,
                    'Disparition ou destruction' => 4,
                    'Autres raisons' => 5,
                ],
            ])
            ->add('number', null, ['label' => 'Numéro de contrat *'])
            ->add('description', TextareaType::class, [
                'label' => 'Modèle de lettre',
                'attr' => [ 'style' => 'height:200px'],
            ])
            ->add('createdAt', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'html5' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Resiliation::class,
        ]);
    }
}
