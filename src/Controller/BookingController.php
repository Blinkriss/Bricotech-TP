<?php

namespace App\Controller;

use DatePeriod;
use DateInterval;
use App\Entity\Tool;
use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use App\Repository\ToolRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/booking", name="booking_")
 */
class BookingController extends AbstractController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(BookingRepository $bookingRepository): Response
    {
        $userId = $this->getUser()->getId();        
        $userBooking = $bookingRepository->findBy(['booker' => $userId]);

        return $this->render('booking/browse.html.twig', [
            'bookings' => $userBooking,
        ]);       
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id":"\d+"}, methods={"POST"})
     */
    public function delete(Request $request, Booking $booking,  ToolRepository $ToolRepository): Response
    {
        $toolId = $booking->getTool()->getId();
        $toolBooking = $ToolRepository->findBy(['id' => $toolId, 'user' => $this->getUser()->getId()]);

        if ($toolBooking != null) {
            foreach ($toolBooking as $key => $value) {
                $currentTool = $value;
            }
        }
        
        if ($this->getUser() == $booking->getBooker() || $this->getUser() == $currentTool->getUser()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($booking);
            $em->flush();
        
            $this->addFlash('success', 'Votre réservation a bien été annulée');
        
            $toolId = $booking->getTool()->getId();
            return $this->redirectToRoute('tool_read', ['id' => $toolId]);
        } else {
            return $this->render('bundles/TwigBundle/Exception/error403.html.twig');
        }
    }

    /**
     * @Route("/edit/{id}", name="edit", requirements={"id":"\d+"}, methods={"GET|POST"})
     */
    public function edit(Request $request, Booking $booking, ToolRepository $ToolRepository): Response
    {
        $toolId = $booking->getTool()->getId();
        $toolBooking = $ToolRepository->findBy(['id' => $toolId, 'user' => $this->getUser()->getId()]);

        if ($toolBooking != null) {
            foreach ($toolBooking as $key => $value) {
                $currentTool = $value;
            }
        }
        
        if ($this->getUser() == $booking->getBooker() || $this->getUser() == $currentTool->getUser()) {
            $form = $this->createForm(BookingType::class, $booking);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $booking->setUpdatedAt(new \DateTime());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                $this->addFlash('success', 'Votre réservation à bien été modifiée');
            
                $toolId = $booking->getTool()->getId();
                return $this->redirectToRoute('tool_read', ['id'=> $toolId]);
            }
            return $this->render('booking/edit.html.twig', [
            'form' => $form->createView(),
        ]);
        } else {
            return $this->render('bundles/TwigBundle/Exception/error403.html.twig');
        }
    }

    /**
     * @Route("/success/{id}", name="success", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function success(Booking $booking, BookingRepository $bookingRepository): Response
    {   
        $currentBooking = $bookingRepository->findOneBy(['id' => $booking]);
        return $this->render('booking/success.html.twig', [
            'currentBooking' => $currentBooking,
        ]);       
    }
}
