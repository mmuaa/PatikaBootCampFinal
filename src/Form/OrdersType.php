<?php

namespace App\Form;

use App\Entity\Orders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('userid')
            ->add('productid')
            ->add('name')
            ->add('surname')
            ->add('email')
            ->add('address')
            ->add('phone')
            ->add('ip')
            ->add('created_at')
            ->add('price')
            ->add('shipping',ChoiceType::class,[
                'choices' => [
                    'Kapıda ödeme' => 'Kapıda ödeme',
                    'Online ödeme' => 'Online ödeme',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Orders::class,
            'csrf_protection' => false,
        ]);
    }
}
