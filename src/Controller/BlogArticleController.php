<?php

namespace App\Controller;

use App\Entity\BlogArticle;
use App\Entity\BlogCategory;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BlogArticleRepository;
use App\Repository\BlogCategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/article", name="blog_article_")
 */
class BlogArticleController extends AbstractController
{
    /**
     * @Route("", name="browse")
     */
    public function browse(BlogArticleRepository $blogArticleRepository, BlogCategoryRepository $BlogCategoryRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $articleslist = $blogArticleRepository->findAll();
        $categories = $BlogCategoryRepository->findAll();

        $articles = $paginator->paginate(
            $articleslist, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        
        return $this->render('article/browse.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/read/{id}", requirements={"id":"\d+"}, name="read")
     */
    public function read(BlogCategoryRepository $blogCategoryRepository, BlogArticle $article ): Response
    {
        $categories = $blogCategoryRepository->findAll();
        return $this->render('article/read.html.twig', [
            'article' => $article,
            'categories' => $categories,
            
        ]);
    }

    /**
     * @Route("/list/{id}", name="list")
     */
    public function browseByCategory(BlogCategoryRepository $blogCategoryRepository, EntityManagerInterface $manager, int $id, Request $request, PaginatorInterface $paginator)
    {
        $categories = $blogCategoryRepository->findAll();

        $query = $manager->createQuery(
            'SELECT ba FROM App\Entity\BlogArticle ba
            JOIN ba.blogCategory bc
            WHERE bc.id = ' . $id ,
            );
        $articles = $query->getResult();


        $articleList = $paginator->paginate(
            $articles, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );

        

        return $this->render('article/list.html.twig', [
            'articleList' => $articleList,
            'categories' => $categories,
        ]);
    }
}
