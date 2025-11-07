<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;

class CategoryFixtures extends Fixture
{
    // Category name => Reference name
    public const CATEGORIES = [
        'Main Dishes' => 'main_dishes',
        'Desserts & Baking' => 'desserts_baking',
        'Appetizers & Sides' => 'appetizers_sides',
        'Soups & Stews' => 'soups_stews',
        'Beverages' => 'beverages',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $name => $referenceName) {
            $category = new Category();
            $category->setName($name);

            $manager->persist($category);

            // Create a public reference for RecipeFixtures to use
            $this->addReference('category_' . $referenceName, $category);
        }

        $manager->flush();
    }
}
