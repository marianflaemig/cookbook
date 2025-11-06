<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Populates the Ingredient lookup table with common items.
 */
class IngredientLookupFixtures extends Fixture
{
    // A simple array of ingredients for the dropdown
    private const INGREDIENTS = [
        'All-Purpose Flour',
        'Granulated Sugar',
        'Salt',
        'Unsalted Butter',
        'Milk (Whole)',
        'Large Eggs',
        'Baking Powder',
        'Vanilla Extract',
        'Olive Oil',
        'Black Pepper',
        'Garlic Cloves',
        'Chicken Breast',
        'Beef Chuck',
        'Onion',
        'Potato',
        'Vinegar (White)',
        'Broth (Beef)',
        'Red Wine',
        'Firm Tofu',
        'Broccoli',
        'Soy Sauce',
        'Spaghetti',
        'Lemon',
        'Parmesan Cheese',
        'Sourdough Starter',
        'Cinnamon',
        'Granny Smith Apple',
        'Brown Sugar',
        'Heavy Cream',
        'Mustard (Dijon)'
    ];

    public function load(ObjectManager $manager): void
    {
        // Loop through the list and create a new Ingredient entity for each item
        foreach (self::INGREDIENTS as $name) {
            $ingredient = new Ingredient();
            $ingredient->setName($name);
            $manager->persist($ingredient);

            // Add a reference for other fixtures to access this ingredient object
            // The reference key is a 'slug' version of the ingredient name.
            $slug = strtolower(str_replace([' ', '(', ')'], ['_', '', ''], $name));
            $this->addReference('ingredient_' . $slug, $ingredient);
        }

        // Execute all the INSERT queries to the database
        $manager->flush();
    }
}
