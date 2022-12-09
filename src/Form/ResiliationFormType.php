<?php

namespace App\Form;

use App\Entity\Letter;
use App\Entity\Resiliation;
use Symfony\Component\Form\AbstractType;
use App\Form\{ServiceFormType, ClientFormType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;

class ResiliationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('service', ServiceFormType::class, ['label' => 'A qui envoyer cette lettre de résiliation ?'])
            ->add('client', ClientFormType::class)
            ->add('type', EntityType::class, [
                'label' => 'Motif',
                'class' => Letter::class,
                'choice_label'  => 'name',
                'data'=>$options['defaultModel']
            ])
            ->add('number', null, ['label' => 'Numéro de contrat *'])
            ->add('description', TextareaType::class, [
                'label' => 'Modèle de lettre',
                'attr' => ['style' => 'height:200px', 'contenteditable' => true ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Resiliation::class,
        ]);

        $resolver->setRequired(array('defaultModel'));
    }
}
