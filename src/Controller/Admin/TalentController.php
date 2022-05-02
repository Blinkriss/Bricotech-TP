<?php

namespace App\Controller\Admin;

use App\Entity\Talent;
use App\Form\TalentEditType;
use App\Form\TalentType;
use App\Repository\TalentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TalentController extends AbstractController
{
    /**
     * @Route("/admin/talent", name="admin_talent")
     */
    public function browse(TalentRepository $talentRepository): Response
    {
        return $this->render('admin/talent/browse.html.twig', ['talents' => $talentRepository->findAll(),
        ]);
    }

    /**
     * @Route("admin/talent/add", name="admin_talent_add", methods={"GET|POST"})
     */
    public function add(HttpFoundationRequest $request): Response
    {   
        $talent = new Talent();
        $form = $this->createForm(TalentType::class, $talent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($talent);
            $entityManager->flush();

            $this->addFlash('success', 'Le talent ' . $talent->getName() . ' a bien été ajouté');

            return $this->redirectToRoute('admin_talent');
        }

        return $this->render('admin/talent/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/talent/edit/{id}", name="admin_talent_edit", requirements={"id":"\d+"}, methods={"GET|POST"})
     */
    public function edit(HttpFoundationRequest $request, Talent $talent): Response
    {   
        $form = $this->createForm(TalentEditType::class, $talent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('success', 'Le talent ' . $talent->getName() . ' a bien été modifié');

            return $this->redirectToRoute('admin_talent', ['id'=> $talent->getId()]);
        }

        return $this->render('admin/talent/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/talent/delete/{id}", name="admin_talent_delete", requirements={"id":"\d+"}, methods={"DELETE"})
     */
    public function delete(Talent $talent): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($talent);
        $em->flush();
        
        $this->addFlash('success', 'Votre talent ' . $talent->getName() . ' a bien été supprimé');

        return $this->redirectToRoute('admin_talent');
    }
}
