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
        // Define common classes for form inputs/selects
        $inputClasses = 'mt-1 w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-[#9b59b6] focus:border-[#9b59b6] transition duration-150';

        $builder
            ->add('quantity', NumberType::class, [
                'label' => 'Quantity',
                'html5' => true,
                'attr' => [
                    // Adjusted width to w-full so it works better in the flex container
                    'placeholder' => 'e.g. 250',
                    'class' => $inputClasses . ' text-right'
                ],
            ])
            ->add('unit', TextType::class, [
                'label' => 'Unit',
                'attr' => [
                    'placeholder' => 'e.g. grams, cups',
                    'class' => $inputClasses
                ],
            ])
            ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'name',
                'label' => 'Ingredient',
                'attr' => [
                    'placeholder' => 'Choose an Ingredient',
                    'class' => $inputClasses // Applies styling to the select dropdown
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecipeIngredient::class,
        ]);
    }
}
