<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductType extends AbstractType
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => $this->translator->trans('product.create.form.name.label'),
                    'attr' => ['placeholder' => $this->translator->trans('product.create.form.name.placeholder')]
                ]
            )->add(
                'shortDescription',
                TextareaType::class,
                [
                    'label' => $this->translator->trans('product.create.form.shortDesc.label'),
                    'attr' => ['placeholder' => $this->translator->trans('product.create.form.shortDesc.placeholder')]
                ]
            )->add(
                'price',
                MoneyType::class,
                [
                    'divisor' => 100,
                    'label' => $this->translator->trans('product.create.form.price.label'),
                    'attr' => ['placeholder' => $this->translator->trans('product.create.form.price.placeholder')]
                ]
            )->add(
                'mainPicture',
                UrlType::class,
                [
                    'label' => $this->translator->trans('product.create.form.img.label'),
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => $this->translator->trans('product.create.form.img.placeholder')
                    ],
                ]
            )->add(
                'category',
                EntityType::class,
                [
                    'label' => $this->translator->trans('product.create.form.category.label'),
                    'placeholder' => $this->translator->trans('product.create.form.category.placeholder'),
                    'class' => Category::class,
                    'choice_label' => function (Category $category) {
                        return strtoupper($category->getName());
                    },
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
