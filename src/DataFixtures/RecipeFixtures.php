<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recipe; // Ensure this matches your actual Recipe entity namespace
use App\Entity\RecipeIngredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Loads recipe data and relies on IngredientLookupFixtures to run first.
 */
class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    // --- Recipe Data (Simplified arrays for ingredient names) ---
    private const RECIPE_DATA = [
        [
            'title' => 'Grandma\'s Best Apple Pie',
            'prep_time' => 45,
            'cook_time' => 75,
            'instructions' => "Prepare pie crust and chill for 30 minutes. Peel and slice apples, mixing them with cinnamon and sugar. Fill the crust and bake at 375°F (190°C) until golden brown. Let cool completely before serving.",
            'ingredients' => [
                ['name' => 'All-Purpose Flour', 'quantity' => 2.5, 'unit' => 'cups'],
                ['name' => 'Unsalted Butter', 'quantity' => 1, 'unit' => 'cup'],
                ['name' => 'Granny Smith Apple', 'quantity' => 6, 'unit' => 'units'],
                ['name' => 'Cinnamon', 'quantity' => 1, 'unit' => 'tbsp'],
                ['name' => 'Granulated Sugar', 'quantity' => 0.5, 'unit' => 'cup']
            ]
        ],
        [
            'title' => 'Hearty Winter Beef Stew',
            'prep_time' => 25,
            'cook_time' => 150,
            'instructions' => "Sear the beef chuck in a Dutch oven. Add vegetables, broth, and red wine. Simmer gently over low heat for at least 2.5 hours until the beef is fork-tender. Season with salt and pepper to taste.",
            'ingredients' => [
                ['name' => 'Beef Chuck', 'quantity' => 2, 'unit' => 'lbs'],
                ['name' => 'Onion', 'quantity' => 1, 'unit' => 'unit'],
                ['name' => 'Potato', 'quantity' => 3, 'unit' => 'units'],
                ['name' => 'Broth (Beef)', 'quantity' => 4, 'unit' => 'cups'],
                ['name' => 'Red Wine', 'quantity' => 1, 'unit' => 'cup']
            ]
        ],
        [
            'title' => 'Spicy Chili Tofu Stir Fry',
            'prep_time' => 15,
            'cook_time' => 20,
            'instructions' => "Slice the firm tofu and pan-fry until crispy. Add broccoli and stir-fry for 5 minutes. Combine soy sauce, sriracha, and ginger for the sauce, then toss with tofu and vegetables until heated through.",
            'ingredients' => [
                ['name' => 'Firm Tofu', 'quantity' => 1, 'unit' => 'block'],
                ['name' => 'Broccoli', 'quantity' => 1, 'unit' => 'head'],
                ['name' => 'Soy Sauce', 'quantity' => 4, 'unit' => 'tbsp'],
                ['name' => 'Olive Oil', 'quantity' => 2, 'unit' => 'tbsp'],
                ['name' => 'Garlic Cloves', 'quantity' => 3, 'unit' => 'units']
            ]
        ],
        [
            'title' => 'Bright Lemon Garlic Pasta',
            'prep_time' => 10,
            'cook_time' => 15,
            'instructions' => "Cook spaghetti according to package directions. Sauté garlic in olive oil. Add lemon zest and juice. Toss the drained pasta with the sauce and Parmesan cheese, using a little pasta water if needed.",
            'ingredients' => [
                ['name' => 'Spaghetti', 'quantity' => 12, 'unit' => 'oz'],
                ['name' => 'Garlic Cloves', 'quantity' => 4, 'unit' => 'units'],
                ['name' => 'Lemon', 'quantity' => 2, 'unit' => 'units'],
                ['name' => 'Parmesan Cheese', 'quantity' => 0.5, 'unit' => 'cup'],
            ]
        ],
        [
            'title' => 'Classic Potato Salad',
            'prep_time' => 30,
            'cook_time' => 25,
            'instructions' => "Boil potatoes until fork-tender. Peel while warm and slice thinly. Mix vinegar, mustard, salt, and pepper into the broth. Pour dressing over the potatoes and stir gently. Add chopped onions and chives (optional).",
            'ingredients' => [
                ['name' => 'Potato', 'quantity' => 1, 'unit' => 'kg'],
                ['name' => 'Broth (Beef)', 'quantity' => 250, 'unit' => 'ml'],
                ['name' => 'Vinegar (White)', 'quantity' => 2, 'unit' => 'tbsp'],
                ['name' => 'Mustard (Dijon)', 'quantity' => 1, 'unit' => 'tsp'],
                ['name' => 'Onion', 'quantity' => 0.5, 'unit' => 'unit']
            ]
        ],
    ];

    // Helper array for common ingredients and units for randomized recipes
    private const RAND_INGREDIENTS = [
        ['name' => 'Chicken Breast', 'units' => ['unit', 'lb', 'g']],
        ['name' => 'Rice', 'units' => ['cup', 'g']],
        ['name' => 'Heavy Cream', 'units' => ['ml', 'cup']],
        ['name' => 'Large Eggs', 'units' => ['unit']],
        ['name' => 'All-Purpose Flour', 'units' => ['cup', 'g']],
        ['name' => 'Tomato Sauce', 'units' => ['oz', 'ml']],
    ];

    public function load(ObjectManager $manager): void
    {
        // Load the 5 detailed signature recipes
        foreach (self::RECIPE_DATA as $data) {
            $this->createRecipeWithIngredients($manager, $data);
        }

        // --- 2. Randomized Recipes (20 Additional Entries) ---
        // New approach: Extract only the titles from RECIPE_DATA and merge them with the new titles
        $signatureTitles = array_column(self::RECIPE_DATA, 'title');

        $otherTitles = [
            'Garlic Butter Shrimp', 'Easy Focaccia Bread', 'Baked Ziti',
            'Quick Pasta Salad', 'Simple Chicken Soup', 'Chocolate Chip Cookies'
        ];

        $randTitles = array_merge($signatureTitles, $otherTitles);


        $prepTimes = [10, 15, 20, 35];
        $cookTimes = [15, 30, 45, 60, 90];

        for ($i = 0; $i < 20; $i++) {

            // Now $randTitles is an array of strings, so array_rand returns a key, and $title is always a string.
            $title = $randTitles[array_rand($randTitles)];
            $title = $title . ' (Quick)';

            // Randomly select 3 ingredients
            $ingredients = [];
            $keys = array_rand(self::RAND_INGREDIENTS, 3);

            foreach ($keys as $key) {
                $randIngData = self::RAND_INGREDIENTS[$key];
                $ingredients[] = [
                    'name' => $randIngData['name'],
                    'quantity' => rand(1, 5) + (rand(0, 100) / 100), // Random float quantity
                    'unit' => $randIngData['units'][array_rand($randIngData['units'])]
                ];
            }

            $randomData = [
                'title' => $title,
                'prep_time' => $prepTimes[array_rand($prepTimes)],
                'cook_time' => $cookTimes[array_rand($cookTimes)],
                'instructions' => "This is a placeholder instruction block for the randomly generated recipe: **{$title}**. The basic steps involve mixing, heating, and serving. Always remember to taste and adjust your seasoning!",
                'ingredients' => $ingredients
            ];

            $this->createRecipeWithIngredients($manager, $randomData);
        }

        // Execute all the inserts (total 25+ recipes)
        $manager->flush();
    }

    /**
     * Helper method to create Recipe, fetch Ingredients via reference, and create RecipeIngredient join entities.
     */
    private function createRecipeWithIngredients(ObjectManager $manager, array $data): void
    {
        $recipe = new Recipe();

        $recipe->setTitle($data['title']);
        $recipe->setDescription($data['instructions']);
        $recipe->setPrepTime($data['prep_time']);
        $recipe->setCookTime($data['cook_time']);
        $recipe->setImage(null); // Image will be handled via upload/placeholder

        // Create RecipeIngredient entities
        foreach ($data['ingredients'] as $ingData) {
            // 1. Generate the slug used in IngredientLookupFixtures
            $slug = strtolower(str_replace([' ', '(', ')'], ['_', '', ''], $ingData['name']));

            $referenceName = 'ingredient_' . $slug;

            // 2. Fetch the Ingredient Entity using the reference, passing null for the manager name
            if ($this->hasReference($referenceName, Ingredient::class)) {
                $ingredientEntity = $this->getReference($referenceName, Ingredient::class);
            } else {
                // If an ingredient is missing from the lookup, skip or log error
                // In a real app, you'd likely throw an exception here.
                continue;
            }

            // 3. Create and configure the RecipeIngredient join entity
            $recipeIngredient = new RecipeIngredient();
            $recipeIngredient->setRecipe($recipe);
            $recipeIngredient->setIngredient($ingredientEntity);
            $recipeIngredient->setQuantity($ingData['quantity']);
            $recipeIngredient->setUnit($ingData['unit']);

            // 4. Link the RecipeIngredient to the Recipe collection
            $recipe->addRecipeIngredient($recipeIngredient);

            // Persist the join entity (it will be saved when $recipe is flushed)
            $manager->persist($recipeIngredient);
        }

        $manager->persist($recipe);
    }

    /**
     * This method specifies that IngredientLookupFixtures must run before this fixture.
     */
    public function getDependencies(): array
    {
        return [
            IngredientLookupFixtures::class,
        ];
    }
}
