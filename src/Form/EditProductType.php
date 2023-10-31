<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use App\Enum\ProductColor;
use App\Enum\ProductGender;
use App\Enum\ProductSize;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('price', NumberType::class, [
                'attr' => [
                    'min' => 0,
                ]
            ])
            ->add('color', ChoiceType::class, [
                'choices' => ProductColor::toArray(),
                'choice_label' => function ($choice, $key, $value) {
                    return $value;
            },
            ])
            ->add('size', ChoiceType::class, [
                'choices' => ProductSize::toArray(),
                'choice_label' => function ($choice, $key, $value) {
                    return $value;
            },
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => ProductGender::toArray(),
                'choice_label' => function ($choice, $key, $value) {
                    return $value;
            },
            ])
            ->add('brand', EntityType::class, [
                'label' => 'Brand',
                'class' => Brand::class,
                'choice_label' => 'name'
            ])
            ->add('stock_quantity', NumberType::class, [
                'attr' => [
                    'min' => 0,
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => 'Category',
                'class' => Category::class,
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
