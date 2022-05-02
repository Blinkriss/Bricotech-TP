<?php

namespace App\Controller;


use App\Repository\BlogCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/category", name="blog_category_")
 */
class BlogCategoryController extends AbstractController
{
    /**
     * @Route("", name="browse")
     */
    public function browse( BlogCategoryRepository $blogCategoryRepository): Response
    {
        $categories = $blogCategoryRepository->findAll();
        return $this->render('includes/blog-category.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/read/{id}", name="read")
     */
    public function read( BlogCategoryRepository $blogCategoryRepository, int $id): Response
    {
        $category = $blogCategoryRepository->find($id);
        return $this->render('category_article/read.html.twig', [
            'category' => $category,
        ]);
    }
}
