<?php

namespace App\Form\DataTransformer;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CategoryNameToStringTransformer implements DataTransformerInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform(mixed $category): string
    {
        if (null === $category) {
            return '';
        }

        if (!$category instanceof Category) {
            throw new TransformationFailedException('Expected input to be a category.');
        }

        return $category->getName();
    }

    public function reverseTransform(mixed $categoryName): mixed
    {
        if (null === $categoryName || '' === $categoryName) {
            return null;
        }

        if (!is_string($categoryName)) {
            throw new TransformationFailedException('Expected input to be a string.');
        }

        $category = $this->entityManager->getRepository(Category::class)->findOneBy(['name' => $categoryName]);

        if ($category) {
            return $category;
        }

        $category = new Category();
        $category->setName($categoryName);

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
    }
}
