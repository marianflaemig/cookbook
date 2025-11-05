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
        'Oil',
        'Black Pepper',
        'Garlic Cloves',
        'Chicken Breast',
        'Onion',
        'Potato',
        'Vinegar',
        'Broth',
        'Mustard'
    ];

    public function load(ObjectManager $manager): void
    {
        // Loop through the list and create a new Ingredient entity for each item
        foreach (self::INGREDIENTS as $name) {
            $ingredient = new Ingredient();
            $ingredient->setName($name);
            $manager->persist($ingredient);
        }

        // Execute all the INSERT queries to the database
        $manager->flush();
    }
}
