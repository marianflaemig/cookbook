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
    #[Route('/', name: 'app_main_index', methods: ['GET'])]
    public function index(): Response
    {
        $form = $this->createForm(RecipeIngredientType::class);

        return $this->render('main/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
