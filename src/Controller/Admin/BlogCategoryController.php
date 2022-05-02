<?php

namespace App\Controller\Admin;

use App\Entity\BlogCategory;
use App\Form\BlogCategoryEditType;
use App\Form\BlogCategoryType;
use App\Repository\BlogCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/blog/category", name="admin_blog_category_")
 */
class BlogCategoryController extends AbstractController
{
    /**
     * @Route("", name="browse")
     */
    public function browse(BlogCategoryRepository $blogCategoryRepository): Response
    {
        return $this->render('admin/blogcategory/browse.html.twig', [
            'blogcategories' => $blogCategoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request): Response
    {
        $blogcategory = new BlogCategory(); 
        $form = $this->createForm(BlogCategoryType::class, $blogcategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($blogcategory);
            $em->flush();

            return $this->redirectToRoute('admin_blog_category_browse');
        }

        return $this->render('admin/blogcategory/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", requirements={"id" : "\d+"})
     */
    public function edit(Request $request, BlogCategory $blogcategory ): Response
    {

        $form = $this->createForm(BlogCategoryEditType::class, $blogcategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $blogcategory->setUpdatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'Votre catégorie a bien été modifié.');

            // On crée un message flash qui annonce que l'ajout a fonctionné
            // On l'affiche dans la page admin_department_browse

            return $this->redirectToRoute('admin_blog_category_add');
        }
    }

    /**
     * @Route("/read/{id}", name="read", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function read(BlogCategory $blogcategory): Response
    {
        return $this->render('admin/blogcategory/read.html.twig', [
            'blogcategory' => $blogcategory,
            
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id" : "\d+"}, methods={"DELETE"})
     */
    public function delete(BlogCategory $blogcategory): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($blogcategory);
        $em->flush();
        
        $this->addFlash('success', 'La catégorie' . $blogcategory->getName() . ' a bien été supprimé');

        return $this->redirectToRoute('admin_blog_category_browse');
    }

}
