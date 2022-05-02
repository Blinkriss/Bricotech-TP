<?php

namespace App\Controller\Admin;

use App\Entity\Bricosphere;
use App\Form\BricosphereAdminType;
use App\Repository\BricosphereRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/bricosphere", name="admin_bricosphere_")
 */
class BricosphereController extends AbstractController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(BricosphereRepository $bricosphereRepository): Response
    {
        return $this->render('admin/bricosphere/browse.html.twig', ['bricosphere' => $bricosphereRepository->findAll(),
        ]);        
    }

    /**
     * @Route("/read/{id}", name="read", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function read(Bricosphere $bricosphere): Response
    {
        return $this->render('admin/bricosphere/read.html.twig', [
            'bricosphere' => $bricosphere,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", requirements={"id":"\d+"}, methods={"GET|POST"})
     */
    public function edit(Request $request, Bricosphere $bricosphere): Response
    {
        $form = $this->createForm(BricosphereAdminType::class, $bricosphere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $bricosphere->setUpdatedAt(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('success', 'La bricosphère ' . $bricosphere->getTitle() . ' a bien été modifié');

            return $this->redirectToRoute('admin_bricosphere_browse');
        }

        return $this->render('admin/bricosphere/edit.html.twig', [
            'form' => $form->createView(),
            'bricosphere' => $bricosphere,
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET|POST"})
     */
    public function add(Request $request): Response
    {   
        $bricosphere = new Bricosphere();
        $form = $this->createForm(BricosphereAdminType::class, $bricosphere);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $creator = $form->get('creator')->getData();
            $bricosphere->setCreator($creator); 

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bricosphere);
            $entityManager->flush();

            $this->addFlash('success', 'La bricosphère ' . $bricosphere->getTitle() . ' a bien été ajouté');

            return $this->redirectToRoute('admin_bricosphere_browse');
        }

        return $this->render('admin/bricosphere/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id":"\d+"}, methods={"DELETE"})
     */
    public function delete(Bricosphere $bricosphere): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($bricosphere);
        $em->flush();
        
        $this->addFlash('success', 'La bricosphère ' . $bricosphere->getTitle() . ' a bien été supprimé');

        return $this->redirectToRoute('admin_bricosphere_browse');
    }


}
