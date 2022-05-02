<?php

namespace App\Controller\Admin;

use App\Entity\ImageBricosphere;
use App\Form\ImageBricosphereType;
use App\Repository\ImageBricosphereRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/imagebricosphere", name="admin_imagebricosphere_")
 */
class ImageBricosphereController extends AbstractController
{
    /**
     * @Route("", name="browse")
     */
    public function browse( ImageBricosphereRepository $imageBricosphereRepository): Response
    {
        return $this->render('admin/image_bricosphere/browse.html.twig', [
            'imageBricosphere' => $imageBricosphereRepository->findAll(),
        ]);
    }

     /**
     * @Route("/add", name="add", methods={"GET|POST"})
     */
    public function add( Request $request): Response
    {
        $imagebricosphere = new ImageBricosphere(); 
        $form = $this->createForm(ImageBricosphereType::class, $imagebricosphere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagebricospheres = $form->get('name')->getData();

            foreach($imagebricospheres as $imagebricosphere){
                $fichier = md5(uniqid()).'.'.$imagebricosphere->guessExtension();

                $imagebricosphere->move(
                    $this->getParameter('bricoshperesimages_directory'),
                    $fichier
                );

                $imagebricosphere = new ImageBricosphere;
                $imagebricosphere->setName($fichier);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($imagebricosphere);
            $em->flush();

            return $this->redirectToRoute('admin_imagebricosphere_browse');
        }

        return $this->render('admin/image_bricosphere/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id" : "\d+"}, methods={"DELETE"})
     */
    public function delete(ImageBricosphere $imagebricosphere): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($imagebricosphere);
        $em->flush();
        
        $this->addFlash('success', 'L\'image a bien été supprimée');

        return $this->redirectToRoute('admin_imagebricosphere_browse');
    }
}
