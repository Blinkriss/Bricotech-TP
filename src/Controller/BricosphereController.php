<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Creator;
use App\Entity\Image;
use App\Entity\Bricosphere;
use App\Entity\ToolCategory;
use App\Form\BricosphereType;
use App\Form\BricosphereEditType;
use App\Repository\ToolRepository;
use App\Repository\BricosphereRepository;
use App\Repository\ToolCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/bricosphere", name="bricosphere_")
 */
class BricosphereController extends AbstractController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(BricosphereRepository $bricosphereRepository): Response
    {
        return $this->render('bricosphere/browse.html.twig', ['bricospheres' => $bricosphereRepository->findAll(),

        ]);
    }

    /**
     * @Route("/read/{id}", name="read", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function read(Bricosphere $bricosphere, ToolCategoryRepository $toolCategoryRepository): Response
    {
        $toolCategories = $toolCategoryRepository->findAll();

        return $this->render('bricosphere/read.html.twig', [
            'bricosphere' => $bricosphere,
            'toolCategories' => $toolCategories,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", requirements={"id":"\d+"}, methods={"GET|POST"})
     */
    public function edit(Request $request, Bricosphere $bricosphere): Response
    {

        if ($this->getUser() == $bricosphere->getCreator()) {
            $form = $this->createForm(BricosphereEditType::class, $bricosphere);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $bricosphere->setUpdatedAt(new \DateTime());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                $this->addFlash('success', 'Votre bricosphère ' . $bricosphere->getTitle() . ' a bien été modifié');

                return $this->redirectToRoute('bricosphere_read', ['id'=> $bricosphere->getId()]);
            }
        
            return $this->render('bricosphere/edit.html.twig', [

            'form' => $form->createView(),
            ]);
        } else {
            return $this->render('bundles/TwigBundle/Exception/error403.html.twig');
        }
    }

    /**
     * @Route("/add", name="add", methods={"GET|POST"})
     */
    public function add(Request $request): Response
    {
        $bricosphere = new Bricosphere();
        
        $user = $this->getUser();

        $form = $this->createForm(BricosphereType::class, $bricosphere);
        $form->handleRequest($request);
        
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $bricosphere->setCreator($user);

            $entityManager->persist($bricosphere);
            $entityManager->flush();
            
    
            $this->addFlash('success', 'Votre bricosphère ' . $bricosphere->getTitle() . ' a bien été créé');

            return $this->redirectToRoute('user_read', ['id'=> $bricosphere->getId()]);
        }

        return $this->render('bricosphere/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/join/{id}", name="join", requirements={"id":"\d+"}, methods={"GET|POST"})
     */
    public function join(Request $request, Bricosphere $bricosphere): Response
    {
        $user = $this->getUser();
        $bricosphere->addUser($user);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($bricosphere);
        $entityManager->flush();
        
        $this->addFlash('success', 'Félicitation, vous avez rejoint la bricosphere : ' . $bricosphere->getTitle());

        return $this->redirectToRoute('bricosphere_read', ['id'=> $bricosphere->getId()]);
    }

    /**
     * @Route("/quit/{id}", name="quit", requirements={"id":"\d+"}, methods={"GET|POST"})
     */
    public function quit(Request $request, Bricosphere $bricosphere): Response
    {
        $user = $this->getUser();
        $bricosphere->removeUser($user);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($bricosphere);
        $entityManager->flush();
        
        $this->addFlash('success', 'Vous avez quitté la bricosphere : ' . $bricosphere->getTitle());

        return $this->redirectToRoute('user_read');
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id":"\d+"}, methods={"DELETE"})
     */
    public function delete(Bricosphere $bricosphere): Response
    {
        if ($this->getUser() == $bricosphere->getCreator()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($bricosphere);
            $em->flush();
        
            $this->addFlash('success', 'Votre bricosphère ' . $bricosphere->getTitle() . ' a bien été supprimé');

            return $this->redirectToRoute('user_read');
        } else {
            return $this->render('bundles/TwigBundle/Exception/error403.html.twig');
        }
    }
}
