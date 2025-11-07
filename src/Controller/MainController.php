<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    private RecipeRepository $recipeRepository;
    private CategoriesRepository $categoriesRepository;

    public function __construct(RecipeRepository $recipeRepository, CategoriesRepository $categoriesRepository){
        $this->recipeRepository = $recipeRepository;
        $this->categoriesRepository = $categoriesRepository;
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $recipes = $this->recipeRepository->getLatestRecipes(3);

        return $this->render('main/index.html.twig', [
            'activePage' => '',
            'recipes' => $recipes,
        ]);
    }

    #[Route('/categories', name: 'show_categories', methods: ['GET'])]
    public function categories(): Response
    {
        $categories = $this->categoriesRepository->findAll();

        return $this->render('main/categories.html.twig', [
            'activePage' => 'Category',
            'categories' => $categories
        ]);
    }

    #[Route('/tips', name: 'show_tips', methods: ['GET'])]
    public function tips(): Response
    {
        return $this->render('main/tips.html.twig', [
            'activePage' => 'Tips'
        ]);
    }

    #[Route('/about', name: 'show_about', methods: ['GET'])]
    public function about(): Response
    {
        return $this->render('main/about.html.twig', [
            'activePage' => 'About'
        ]);
    }
}
