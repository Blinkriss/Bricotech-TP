<?php

namespace App\Controller;

use App\Repository\FaqRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/faq", name="faq_")
 */
class FaqController extends AbstractController
{
    /**
     * @Route("", name="browse")
     */
    public function browse( FaqRepository $faqRepository): Response
    {
        $faqs = $faqRepository->findAll();

        return $this->render('faq/faq.html.twig', [
            'faqs' => $faqs,
        ]);
    }
}
