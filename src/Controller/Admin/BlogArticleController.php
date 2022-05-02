<?php

namespace App\Controller\Admin;

use App\Entity\BlogArticle;
use App\Form\BlogArticleEditType;
use App\Form\BlogArticleType;
use App\Repository\BlogArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/blog/article", name="admin_blog_article_")
 */
class BlogArticleController extends AbstractController
{
    /**
     * @Route("/", name="browse")
     */
    public function browse( BlogArticleRepository $blogArticleRepository): Response
    {
        return $this->render('admin/blogarticle/browse.html.twig', [
            'blogarticles' => $blogArticleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add( Request $request): Response
    {
        $blogArticle = new BlogArticle(); 
        $form = $this->createForm(BlogArticleType::class, $blogArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($blogArticle);
            $em->flush();

            // On crée un message flash qui annonce que l'ajout a fonctionné
            // On l'affiche dans la page admin_department_browse
            // $this->addFlash('success', 'L\'article ' . $blogArticle->getName() . ' a bien été ajouté');

            return $this->redirectToRoute('admin_blog_article_browse');
        }

        return $this->render('admin/blogarticle/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/read/{id}", name="read", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function read(BlogArticle $blogArticle): Response
    {
        return $this->render('admin/blogarticle/read.html.twig', [
            'blogarticle' => $blogArticle,
            
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", requirements={"id" : "\d+"})
     */
    public function edit(BlogArticle $blogArticle, Request $request): Response
    {
        $form = $this->createForm(BlogArticleEditType::class, $blogArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $blogArticle->setUpdatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'Votre Article a bien été modifié.');


            return $this->redirectToRoute('admin_blog_article_browse');
        }

        return $this->render('admin/blogarticle/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id" : "\d+"}, methods={"DELETE"})
     */
    public function delete(BlogArticle $blogArticle): Response
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($blogArticle);
        $em->flush();
        
        $this->addFlash('success', 'L\'article' . $blogArticle->getTitle() . ' a bien été supprimé');

        return $this->redirectToRoute('admin_blog_article_browse');
    }

}
