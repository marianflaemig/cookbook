<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryApiController extends AbstractController
{
    private CategoriesRepository $repository;

    public function __construct(CategoriesRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/api/categories/names', name: 'api_category_names', methods: ['GET'])]
    public function getCategories(): Response
    {
        $categories = $this->repository->findAll();

        $categoryNames = array_map(function ($category) {
            return $category->getName();
        }, $categories);

        return $this->json($categoryNames);
    }
}
