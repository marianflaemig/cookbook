<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\RecipeIngredientType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $form = $this->createForm(RecipeIngredientType::class);

        return $this->render('main/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/categories', name: 'show_categories', methods: ['GET'])]
    public function categories(): Response
    {
        return $this->render('main/categories.html.twig', [

        ]);
    }

    #[Route('/tips', name: 'show_tips', methods: ['GET'])]
    public function tips(): Response
    {
        return $this->render('main/tips.html.twig', [

        ]);
    }

    #[Route('/about', name: 'show_about', methods: ['GET'])]
    public function about(): Response
    {
        return $this->render('main/about.html.twig', [

        ]);
    }
}
