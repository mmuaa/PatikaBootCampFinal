<?php

namespace App\Form\Admin;

use App\Entity\Admin\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('parentid',TextType::class,[
                'label' => 'Üst Kategorisi'
            ])
            ->add('title',TextType::class,[
                'label'=> 'Kategori İsmi'
        ])
            ->add('keywords')
            ->add('description')
            ->add('image',FileType::class,[
                'mapped'=>false,'required'=>false,
                'constraints'=> [
                    new File(['maxSize'=>'1024k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage'=> 'Please upload a valid Image File',
                    ])
                ],
            ])
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
            'data_class' => Category::class,
            'csrf_protection' => false,
        ]);
    }
}
