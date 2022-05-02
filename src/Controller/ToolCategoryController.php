<?php

namespace App\Controller;

use App\Entity\Bricosphere;
use App\Entity\ToolCategory;
use App\Form\ToolCategoryType;
use App\Repository\ToolRepository;
use App\Repository\ToolCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("tool_category", name="tool_category_")
 */
class ToolCategoryController extends AbstractController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(ToolCategoryRepository $toolCategoryRepository): Response
    {
        return $this->render('tool_category/browse.html.twig', ['toolCategory' => $toolCategoryRepository->findAll(),
        ]);        
    }

    /**
     * @Route("/read/{id}", name="read", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function read(ToolCategory $toolCategory): Response
    {
        return $this->render('tool_category/read.html.twig', [
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

            $this->addFlash('success', 'La catégorie d\'outil ' . $toolCategory->getName() . ' a bien été modifié');

            return $this->redirectToRoute('tool_category_read', ['id'=> $toolCategory->getId()]);
        }

        return $this->render('tool_category/edit.html.twig', [
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

            $this->addFlash('success', 'La catégorie d\'outil ' . $toolCategory->getName() . ' a bien été ajouté');

            return $this->redirectToRoute('tool_category_read', ['id'=> $toolCategory->getId()]);
        }

        return $this->render('tool_category/add.html.twig', [
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

        return $this->redirectToRoute('tool_category_browse');
    }

    /**
     * @Route("/{id}", name="tool_user_filter", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function toolUserFilter(ToolCategory $toolCategory, ToolRepository $toolRepository): Response
    {
        $categoryId = $toolCategory->getId();
        $userId = $this->getUser()->getId();
        $filter = $toolRepository->findBy([
            'toolCategory' => $categoryId,
            'user' => $userId,
        ]);
        return $this->render('tool_category/read.html.twig', [
            'filterResponse' => $filter,
        ]);
    }
}
