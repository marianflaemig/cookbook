<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Form\DataTransformer\CategoryNameToStringTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
    private CategoryNameToStringTransformer $categoryNameToStringTransformer;

    public function __construct(CategoryNameToStringTransformer $categoryNameToStringTransformer)
    {
        $this->categoryNameToStringTransformer = $categoryNameToStringTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Define reusable input classes for consistency
        $inputClasses = 'mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-[#9b59b6] focus:border-[#9b59b6] transition duration-150';

        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => $inputClasses,
                    'placeholder' => 'e.g., Grandma\'s Best Apple Pie',
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Instructions',
                'attr' => [
                    'class' => $inputClasses,
                    'rows' => 8,
                ],
            ])
            ->add('prepTime', IntegerType::class, [
                'label' => 'Prep time (minutes)',
                'required' => false,
                'attr' => [
                    // Adjusted width for small fields
                    'class' => 'mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-[#9b59b6] focus:border-[#9b59b6] transition duration-150',
                    'placeholder' => 'e.g. 30'
                ],
            ])
            ->add('cookTime', IntegerType::class, [
                'label' => 'Cook time (minutes)',
                'required' => false,
                'attr' => [
                    // Adjusted width for small fields
                    'class' => 'mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-[#9b59b6] focus:border-[#9b59b6] transition duration-150',
                    'placeholder' => 'e.g. 75'
                ],
            ])
            ->add('category', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => $inputClasses,
                    'placeholder' => 'Enter a recipe category',
                ]
            ]);
        $builder->get('category')->addModelTransformer($this->categoryNameToStringTransformer);

        $builder
            // FileType needs specialized classes in Twig since field rendering is complex
            ->add('image', FileType::class, [
                'label' => 'Recipe Photo (JPG/PNG)',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    // Tailwind classes for File input styling (note: may require custom CSS for full effect)
                    'class' => 'mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#f9f0fd] file:text-[#9b59b6] hover:file:bg-[#e6d0f5] cursor-pointer'
                ]
            ])
            ->add('recipeIngredients', CollectionType::class, [
                'entry_type' => RecipeIngredientType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
