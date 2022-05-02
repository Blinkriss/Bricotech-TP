<?php

namespace App\Controller\Admin;

use App\Entity\ToolCategory;
use App\Form\ToolCategoryType;
use App\Repository\ToolCategoryRepository;
use App\Repository\ToolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("admin/tool_category", name="admin_tool_category_")
 */
class ToolCategoryController extends AbstractController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(ToolCategoryRepository $toolCategoryRepository): Response
    {
        return $this->render('admin/tool_category/browse.html.twig', ['toolCategory' => $toolCategoryRepository->findAll(),
        ]);        
    }

    /**
     * @Route("/read/{id}", name="read", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function read(ToolCategory $toolCategory): Response
    {
        return $this->render('admin/tool_category/read.html.twig', [
            'toolCategory' => $toolCategory,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", requirements={"id":"\d+"}, methods={"GET|POST"})
     */
    public function edit(Request $request, ToolCategory $toolCategory): Response
    {
        $form = $this->createForm(ToolCategoryType::class, $toolCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $toolCategory->setUpdatedAt(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('success', 'La catégorie ' . $toolCategory->getName() . ' a bien été modifié');

            return $this->redirectToRoute('admin_tool_category_browse', ['id'=> $toolCategory->getId()]);
        }

        return $this->render('admin/tool_category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET|POST"})
     */
    public function add(Request $request): Response
    {   
        $toolCategory = new ToolCategory();
        $form = $this->createForm(ToolCategoryType::class, $toolCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($toolCategory);
            $entityManager->flush();

            $this->addFlash('success', 'La catégorie ' . $toolCategory->getName() . ' a bien été ajouté');

            return $this->redirectToRoute('admin_tool_category_browse');
        }

        return $this->render('admin/tool_category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id":"\d+"}, methods={"DELETE"})
     */
    public function delete(ToolCategory $toolCategory): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($toolCategory);
        $em->flush();
        
        $this->addFlash('success', 'La catégorie ' . $toolCategory->getName() . ' a bien été supprimé');

        return $this->redirectToRoute('admin_tool_browse');
    }
}
