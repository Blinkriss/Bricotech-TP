<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Tool;
use App\Form\ToolAdminType;
use App\Repository\BookingRepository;
use App\Repository\ToolRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("admin/tool", name="admin_tool_")
 */
class ToolController extends AbstractController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(ToolRepository $toolRepository): Response
    {
        return $this->render('admin/tool/browse.html.twig', ['tool' => $toolRepository->findAll(),]);
    }

    /**
     * @Route("/read/{id}", name="read", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function read(Tool $tool): Response
    {
        return $this->render('admin/tool/read.html.twig', [
            'tool' => $tool,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", requirements={"id":"\d+"}, methods={"GET|POST"})
     */
    public function edit(Request $request, Tool $tool): Response
    {
        $form = $this->createForm(ToolAdminType::class, $tool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les images transmises
            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $imgTool = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier public/assets/images/tools
                $image->move(
                    $this->getParameter('toolsimages_directory'),
                    $imgTool
                );

                // On crée l'image dans la base de données
                $img = new Image();
                $img->setName($imgTool);
                $tool->addImage($img);
            }

            $tool->setUpdatedAt(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('success', 'L\'outil ' . $tool->getName() . ' a bien été modifié');

            return $this->redirectToRoute('admin_tool_browse');
        }

        return $this->render('admin/tool/edit.html.twig', [
            'tool' => $tool,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add", name="add", requirements={"id":"\d+"}, methods={"GET|POST"})
     */
    public function add(Request $request): Response
    {
        $tool = new Tool();
        $form = $this->createForm(ToolAdminType::class, $tool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();

            if ($images) {
                foreach ($images as $image) {
                    $imgTool = md5(uniqid()) . '.' . $image->guessExtension();
                    
                    $image->move(
                        $this->getParameter('toolsimages_directory'),
                        $imgTool
                    );
                    
                    $img = new Image();
                    $img->setName($imgTool);
                    $tool->addImage($img);
                }
            } else {
                $img = new Image();
                $img->setName('no-image-found.png');
                $tool->addImage($img);
            }

            $tool->setIsActivate(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tool);
            $entityManager->flush();

            $this->addFlash('success', 'L\' outil ' . $tool->getName() . ' a bien été ajouté');

            return $this->redirectToRoute('admin_tool_browse');
        }

        return $this->render('admin/tool/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id":"\d+"}, methods={"DELETE"})
     */
    public function delete(Tool $tool, BookingRepository $bookingRepository, int $id): Response
    {
        $bookings = $bookingRepository->findBy(['tool' => $id]);

        $em = $this->getDoctrine()->getManager();
        
        if ($bookings == !null) {
            foreach ($bookings as $key => $booking) {
                $em->remove($booking);
            }
            $em->remove($tool);
            $em->flush();

            $this->addFlash('success', 'L\'outil' . $tool->getName() . ' a bien été supprimé');

            return $this->redirectToRoute('admin_tool_browse');
        }
    }

    /**
     * @Route("/supprime/image/{id}", name="delete_image", methods={"DELETE"})
     */
    public function deleteImage(Image $image, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {

            $nom = $image->getName();

            unlink($this->getParameter('toolsimages_directory') . '/' . $nom);

            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }
}
