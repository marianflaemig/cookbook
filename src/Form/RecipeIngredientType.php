<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeIngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', NumberType::class, [
                'label' => 'Quantity',
                'html5' => true,
                'attr' => ['placeholder' => 'e.g. 250'],
            ])
            ->add('unit', TextType::class, [
                'label' => 'Unit',
                'attr' => ['placeholder' => 'e.g. grams, cups'],
            ])
            ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'name',
                'label' => 'Ingredient',
                'placeholder' => 'Choose an Ingredient',
            ]);
//            ->add('recipe', EntityType::class, [
//                'class' => Recipe::class,
//                'choice_label' => 'id',
//            ])
//            ->add('ingredient', EntityType::class, [
//                'class' => Ingredient::class,
//                'choice_label' => 'id',
//            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecipeIngredient::class,
        ]);
    }
}
