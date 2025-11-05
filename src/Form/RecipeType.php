<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500',
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Instructions',
                'attr' => [
                    'rows' => 6,
                    'class' => 'mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500'
                ],
            ])
            ->add('prepTime', IntegerType::class, [
                'label' => 'Prep time (minutes)',
                'required' => false,
                'attr' => [
                    'class' => 'mt-1 block w-max border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500'
                ],
            ])
            ->add('cookTime', IntegerType::class, [
                'label' => 'Cook time (minutes)',
                'required' => false,
                'attr' => [
                    'class' => 'mt-1 block w-max border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500'
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Recipe Photo (JPG/PNG)',
                'mapped' => false,
                'required' => false,
            ])
            ->add('recipeIngredients', CollectionType::class, [
                'entry_type' => RecipeIngredientType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Ingredients',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
