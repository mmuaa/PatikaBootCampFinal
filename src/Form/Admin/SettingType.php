<?php

namespace App\Form\Admin;

use App\Entity\Admin\Setting;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('keywords')
            ->add('description')
            ->add('companyname')
            ->add('address')
            ->add('phone')
            ->add('fax')
            ->add('email')
            ->add('facebook')
            ->add('instagram')
            ->add('twitter')
            ->add('about',CKEditorType::class,array(
                'config' => array(
                    'uiColor' => '#ffffff',
                ),
            ))
            ->add('contact',CKEditorType::class,array(
                'config' => array(
                    'uiColor' => '#ffffff',
                ),
            ))
            ->add('reference',CKEditorType::class,array(
                'config' => array(
                    'uiColor' => '#ffffff',
                )
            ))
            ->add('status',ChoiceType::class,[
                'choices' => [
                    'True' => 'True',
                    'False' => 'False'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Setting::class,
        ]);
    }
}
