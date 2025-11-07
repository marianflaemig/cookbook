<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function searchByPhrase(string $searchPhrase)
    {
        return $this->createQueryBuilder('recipe')

            ->leftJoin('recipe.recipeIngredients', 'ingredients')
            ->leftJoin('ingredients.ingredient', 'ingredient')

            ->where('recipe.title LIKE :searchPhrase')
            ->orWhere('ingredient.name LIKE :searchPhrase')

            ->setParameter('searchPhrase', '%'.$searchPhrase.'%')

            ->distinct()

            ->getQuery()
            ->getResult();
    }
}
