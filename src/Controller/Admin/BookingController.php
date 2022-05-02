<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/booking", name="admin_booking_")
 */
class BookingController extends AbstractController
{
    /**
     * @Route("/", name="browse")
     */
    public function browse( BookingRepository $bookingRepository): Response
    {
        $bookings = $bookingRepository->findAll();

        return $this->render('admin/booking/browse.html.twig', [
            'bookings' => $bookings,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", requirements={"id":"\d+"}, methods={"GET|POST"})
     */
    public function edit(Request $request, Booking $booking): Response
    {
            $form = $this->createForm(BookingType::class, $booking);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $booking->setUpdatedAt(new \DateTime());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                $this->addFlash('success', 'La réservation à bien été modifiée');
            
                return $this->redirectToRoute('admin_booking_browse');
            }
            return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id":"\d+"}, methods={"POST"})
     */
    public function delete(Request $request, Booking $booking): Response
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($booking);
            $em->flush();
        
            $this->addFlash('success', 'La réservation a bien été annulée');
        
            return $this->redirectToRoute('admin_booking_browse');
    }

}
