<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\RecipeIngredientType;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    private RecipeRepository $recipeRepository;

    public function __construct(RecipeRepository $recipeRepository){
        $this->recipeRepository = $recipeRepository;
    }
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $form = $this->createForm(RecipeIngredientType::class);

        $recipes = $this->recipeRepository->findAll();
        $recipeKeys = array_rand($recipes, 3);

        return $this->render('main/index.html.twig', [
            'form' => $form->createView(),
            'randRecipe1' => $recipes[$recipeKeys[0]],
            'randRecipe2' => $recipes[$recipeKeys[1]],
            'randRecipe3' => $recipes[$recipeKeys[2]],
            'activePage' => ''
        ]);
    }

    #[Route('/categories', name: 'show_categories', methods: ['GET'])]
    public function categories(): Response
    {
        return $this->render('main/categories.html.twig', [
            'activePage' => 'Category'
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
