<?php

namespace App\Controller\Admin;

use App\Entity\Faq;
use App\Form\FaqType;
use App\Form\FaqEditType;
use App\Repository\FaqRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/faq", name="admin_faq_")
 */
class FAQController extends AbstractController
{
    /**
     * @Route("/", name="browse", methods={"GET"})
     */
    public function browse( FaqRepository $faqRepository): Response
    {

        return $this->render('admin/faq/browse.html.twig', [
            'faq' => $faqRepository->findAll(),
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET|POST"})
     */
    public function add( Request $request): Response
    {
        $faq = new Faq(); 
        $form = $this->createForm(FaqType::class, $faq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($faq);
            $em->flush();

            return $this->redirectToRoute('admin_faq_browse');
        }

        return $this->render('admin/faq/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/read/{id}", name="read", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function read(Faq $faq): Response
    {
        return $this->render('admin/faq/read.html.twig', [
            'faq' => $faq,
            
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", requirements={"id" : "\d+"}, methods={"GET|POST"})
     */
    public function edit(Faq $faq, Request $request): Response
    {
        $form = $this->createForm(FaqEditType::class, $faq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'Votre Question/reponse a bien été modifié.');

            return $this->redirectToRoute('admin_faq_browse');
        }

        return $this->render('admin/faq/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id" : "\d+"}, methods={"DELETE"})
     */
    public function delete(Faq $faq): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($faq);
        $em->flush();
        
        $this->addFlash('success', 'La question et la réponse  ont bien été supprimées');

        return $this->redirectToRoute('admin_faq_browse');
    }

}
