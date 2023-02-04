<?php

namespace App\Form;

use App\Entity\Order;
use DateTime as GlobalDateTime;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sendedAt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'envoi',
                'placeholder' => 'Aujourd\'hui',
            ])
            // ->add('option1')
            // ->add('option2')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
