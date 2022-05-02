<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticContentController extends AbstractController
{
    /**
     * @Route("/static/content", name="static_content_")
     */
    public function index(): Response
    {
        return $this->render('static_content/index.html.twig', [
            'controller_name' => 'StaticContentController',
        ]);
    }
}
