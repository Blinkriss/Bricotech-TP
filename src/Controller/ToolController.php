<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Image;
use App\Entity\Tool;
use App\Form\BookingType;
use App\Form\ToolEditType;
use App\Form\ToolType;
use App\Repository\BookingRepository;
use App\Repository\ToolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tool", name="tool_")
 */
class ToolController extends AbstractController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(ToolRepository $toolRepository): Response
    {
        return $this->render('tool/browse.html.twig', ['tools' => $toolRepository->findAll(),]);
    }

    /**
     * @Route("/read/{id}", name="read", requirements={"id":"\d+"}, methods={"GET|POST"})
     */
    public function read(Request $request, Tool $tool): Response
    {
        $user = $this->getUser();
        
        $booking = new Booking;        
        $form = $this->createForm(BookingType::class, $booking);            
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $booking->setTool($tool);
            $booking->setBooker($user);

            if(!$booking->isBookableDates()) {
                $this->addFlash('warning', 'Ces dates de réservation ne sont pas disponibles');
            } else {
                $entityManager->persist($booking);
                $entityManager->flush();
    
                $this->addFlash('success', 'Votre réservation a bien été effectuée.');
    
                return $this->redirectToRoute('booking_success', ['id' => $booking->getId()]);
            }
        }
        return $this->render('tool/read.html.twig', [
            'form' => $form->createView(),
            'tool' => $tool,
        ]);
    }
    

    /**
     * @Route("/unbook/{id}", name="unbook", requirements={"id":"\d+"}, methods={"GET|POST"})
     */
    public function unbook(Request $request, Tool $tool): Response
    {
        $user = $this->getUser();
        $bookingsArray = $tool->getBookings()->getValues();
        foreach ($bookingsArray as $key => $value) {
            $bookingByTool = $value;
        }

        if ($bookingByTool->getBooker()->getId() === $user->getId()) {
            return $this->redirectToRoute('booking_delete', [
                'booking' => $bookingByTool,
            ]);
        }
        return $this->redirectToRoute('tool_read', ['id' => $tool->getId()]);
    }

    /**
     * @Route("/edit/{id}", name="edit", requirements={"id":"\d+"}, methods={"GET|POST"})
     */
    public function edit(Request $request, Tool $tool): Response
    {
        if ($this->getUser() == $tool->getUser()) {

            $form = $this->createForm(ToolEditType::class, $tool);
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

                $this->addFlash('success', 'Votre outil ' . $tool->getName() . ' a bien été modifié');

                return $this->redirectToRoute('tool_read', ['id' => $tool->getId()]);
            }

            return $this->render('tool/edit.html.twig', [
                'tool' => $tool,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->render('bundles/TwigBundle/Exception/error403.html.twig');
        }
    }

    /**
     * @Route("/add", name="add", requirements={"id":"\d+"}, methods={"GET|POST"})
     */
    public function add(Request $request): Response
    {
        $user = $this->getUser();
        $tool = new Tool();
        $form = $this->createForm(ToolType::class, $tool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les images transmises
            $images = $form->get('images')->getData();

            if ($images) {
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
            } else {
                $img = new Image();
                $img->setName('no-image-found.png');
                $tool->addImage($img);
            }
            $tool->setUser($user);
            $tool->setIsActivate(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tool);
            $entityManager->flush();

            $this->addFlash('success', 'Votre outil ' . $tool->getName() . ' a bien été créé');

            return $this->redirectToRoute('tool_read', ['id' => $tool->getId()]);
        }

        return $this->render('tool/add.html.twig', [
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

            $this->addFlash('success', 'Votre outil ' . $tool->getName() . ' a bien été supprimé');

            return $this->redirectToRoute('user_read');
        } else if ($bookings == null) {
            $em->remove($tool);
            $em->flush();

            $this->addFlash('success', 'Votre outil ' . $tool->getName() . ' a bien été supprimé');
            
            return $this->redirectToRoute('user_read');
        } else {
            return $this->render('bundles/TwigBundle/Exception/error403.html.twig');
        }
    }

    /**
     * @Route("/delete/image/{id}", name="delete_image", requirements={"id":"\d+"}, methods={"DELETE"})
     */
    public function deleteImage(Image $image, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
            // On récupère le nom de l'image
            $nom = $image->getName();
            // On supprime le fichier
            unlink($this->getParameter('toolsimages_directory') . '/' . $nom);

            // On supprime l'entrée de la base
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }

    /**
     * @Route("/desactivate/tool/{id}", name="desactivate", requirements={"id":"\d+"}, methods={"GET|POST"})
     */
    public function desactivateTool(Request $request, Tool $tool)
    {
        if ($this->getUser()->getId() == $tool->getUser()->getId()) {
            $em = $this->getDoctrine()->getManager();
            $tool->setIsActivate(false);
            $em->flush($tool);

            $this->addFlash('success', 'Votre outil ' . $tool->getName() . ' a bien été désactivé');

            return $this->redirectToRoute('tool_read', ['id' => $tool->getId()]);
        } else {
            return $this->render('bundles/TwigBundle/Exception/error403.html.twig');
        
        }
    }

    /**
     * @Route("/reactivate/tool/{id}", name="reactivate", requirements={"id":"\d+"}, methods={"GET|POST"})
     */
    public function reactivateTool(Request $request, Tool $tool)
    {
        if ($this->getUser()->getId() == $tool->getUser()->getId()) {
            $em = $this->getDoctrine()->getManager();
            $tool->setIsActivate(true);
            $em->flush($tool);

            $this->addFlash('success', 'Votre outil ' . $tool->getName() . ' a bien été réactivé');

            return $this->redirectToRoute('tool_read', ['id' => $tool->getId()]);
        } else {
            return $this->render('bundles/TwigBundle/Exception/error403.html.twig');
        
        }
    }
}
