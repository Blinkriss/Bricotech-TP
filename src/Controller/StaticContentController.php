<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticContentController extends AbstractController
{
    /**
     * @Route("/static/content", name="static_pages_")
     */
    public function index(): Response
    {
        return $this->render('static_content/index.html.twig', [
            'controller_name' => 'StaticContentController',
        ]);
    }

    /**
     * @Route("/403", name="static_403")
     */
    public function error_forbidden(): Response
    {
        return $this->render('static_content/403.html.twig', [
            'controller_name' => 'StaticContentController',
        ]);
    }

    /**
     * @Route("/404", name="static_404")
     */
    public function error_notfound(): Response
    {
        return $this->render('static_content/404.html.twig', [
            'controller_name' => 'StaticContentController',
        ]);
    }

    /**
     * @Route("/about", name="static_about")
     */
    public function about(): Response
    {
        return $this->render('static_content/about.html.twig', [
            'controller_name' => 'StaticContentController',
        ]);
    }

    /**
     * @Route("/mentions-legales", name="static_legacy")
     */
    public function legacy(): Response
    {
        return $this->render('static_content/legacy.html.twig', [
            'controller_name' => 'StaticContentController',
        ]);
    }

    /**
     * @Route("/cgu", name="static_cgu")
     */
    public function cgu(): Response
    {
        return $this->render('static_content/cgu.html.twig', [
            'controller_name' => 'StaticContentController',
        ]);
    }

}
