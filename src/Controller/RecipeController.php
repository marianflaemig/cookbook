<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\DeleteRecipeType;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecipeController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private RecipeRepository $recipeRepository;

    public function __construct(EntityManagerInterface $entityManager, RecipeRepository $recipeRepository)
    {
        $this->entityManager = $entityManager;
        $this->recipeRepository = $recipeRepository;
    }

    #[Route('/recipe/{id}', name: 'show_recipe', methods: ['GET'])]
    public function show(int $id): Response
    {
        $recipe = $this->recipeRepository->find($id);

        $deleteForm = $this->_createDeleteForm($recipe)->createView();

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
            'deleteForm' => $deleteForm,
            'activePage' => ''
        ]);
    }

    #[Route('/recipes', name: 'show_recipes', methods: ['GET'])]
    public function showALl(): Response
    {
        $recipes = $this->recipeRepository->findAll();

        $deleteForms = [];
        foreach ($recipes as $recipe) {
            $deleteForms[$recipe->getId()] = $this->_createDeleteForm($recipe)->createView();
        }

        return $this->render('recipe/showAll.html.twig', [
            'recipes' => $recipes,
            'deleteForms' => $deleteForms,
            'activePage' => 'Recipes'
        ]);
    }

    #[Route('/search/', name: 'search_recipe', methods: ['GET'])]
    public function search(Request $request): Response
    {
        $searchPhrase = $request->query->get('search_phrase');
        $recipes = $this->recipeRepository->searchByPhrase($searchPhrase);

        return $this->render('recipe/showAll.html.twig', [
            'recipes' => $recipes,
            'activePage' => ''
        ]);
    }

    #[Route('/create', name: 'create_recipe')]
    public function create(Request $request, SluggerInterface $slugger): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $saveFilename = $slugger->slug($originalFilename);
                $newFilename = $saveFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/images/recipes',
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Could not upload file: ' . $e->getMessage());
                    return $this->redirectToRoute('create_recipe');
                }

                $recipe->setImage($newFilename);
            }

            $this->entityManager->persist($recipe);
            $this->entityManager->flush();

            $this->addFlash('success', 'Recipe created successfully.');
            return $this->redirectToRoute('show_recipes');
        }

        return $this->render('recipe/create.html.twig', [
            'form' => $form->createView(),
            'activePage' => ''
        ]);
    }

    #[Route('/edit/{id}', name: 'edit_recipe', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recipe $recipe, SluggerInterface $slugger): Response
    {
        $recipe = $this->recipeRepository->find($recipe->getId());
        $oldFilename = $recipe->getImage();

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        $imageFile = $form->get('image')->getData();

        if ($form->isSubmitted() && $form->isValid()) {

            if ($imageFile) {

                if ($oldFilename) {

                    $fileToDelete = $this->getParameter('kernel.project_dir') . '/public/images/recipes/' . $oldFilename;
                    if (file_exists($fileToDelete)) {
                        unlink($fileToDelete);
                    }
                }

                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $saveFilename = $slugger->slug($originalFilename);
                $newFilename = $saveFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/images/recipes',
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Could not upload file: ' . $e->getMessage());
                }

                $recipe->setImage($newFilename);
            }
            $this->entityManager->persist($recipe);
            $this->entityManager->flush();

            $this->addFlash('success', 'Recipe updated successfully.');

            return $this->redirectToRoute('show_recipes');
        }

        return $this->render('recipe/create.html.twig', [
            'form' => $form->createView(),
            'activePage' => ''
        ]);
    }

    #[Route('/delete/{id}', name: 'delete_recipe', methods: ['DELETE'])]
    public function delete(Request $request, Recipe $recipe): RedirectResponse
    {
        // Create the form using the new Type class
        $form = $this->_createDeleteForm($recipe);
        $form->handleRequest($request);

        // Check if the form is submitted AND the CSRF token is valid
        if ($form->isSubmitted() && $form->isValid()) {

            // 1. Delete the image file from disk
            $imagePath = $recipe->getImage();
            if ($imagePath) {
                $fileToDelete = $this->getParameter('kernel.project_dir') . '/public' . $imagePath;
                if (file_exists($fileToDelete)) {
                    unlink($fileToDelete);
                }
            }

            // 2. Delete the entity from the database
            $this->entityManager->remove($recipe);
            $this->entityManager->flush();

            $this->addFlash('success', 'Recipe successfully deleted.');
        } else {
            // Handle invalid token
            $this->addFlash('error', 'Invalid security token. Could not delete recipe.');
        }

        return $this->redirectToRoute('show_recipes');
    }

    private function _createDeleteForm(Recipe $recipe): FormInterface
    {
        return $this->createForm(DeleteRecipeType::class, null, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('delete_recipe', ['id' => $recipe->getId()]),
            'csrf_protection' => true,
            'csrf_token_id' => 'delete' . $recipe->getId(),
        ]);
    }
}
