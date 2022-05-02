<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserAdminType;
use App\Form\UserAdminEditType;
use App\Repository\BookingRepository;
use App\Repository\BricosphereRepository;
use App\Repository\ToolRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("/admin/user", name="admin_user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/browse.html.twig', ['users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/read/{id}", name="read", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function read(): Response
    {
        $user = $this->getUser();
        
        return $this->render('admin/user/read.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", requirements={"id":"\d+"}, methods={"GET|POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserAdminEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new \DateTime());
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('success', 'Le profil a bien été modifié.');

            return $this->redirectToRoute('admin_user_browse');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET|POST"})
     */
    public function add(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserAdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            $user->setPassword($passwordEncoder->encodePassword($user, $password));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_browse');
        }

        return $this->render('admin/user/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id":"\d+"},  methods={"DELETE"},)
     */
    public function delete(Request $request, UserRepository $userRepository, int $id, BricosphereRepository $bricosphereRepository, ToolRepository $toolRepository, BookingRepository $bookingRepository): Response
    {
        $user = $userRepository->find($id);
        $toolArray = $toolRepository->findBy(['user' => $id]);
        $bricosphereArray = $bricosphereRepository->findBy(['creator' => $id]);

        $em = $this->getDoctrine()->getManager();
        if ($bricosphereArray == !null) {
            foreach ($bricosphereArray as $key => $bricosphere) {
                $em->remove($bricosphere);
            }
        }
        if ($toolArray == !null) {
            foreach ($toolArray as $key => $tool) {
                $toolId = $tool->getId();
            }
            foreach ($toolArray as $key => $tool) {
                $em->remove($tool);
            }
            $bookingArray = $bookingRepository->findBy(['tool' => $toolId]);
            if ($bookingArray == !null) {
                foreach ($bookingArray as $key => $booking) {
                    $em->remove($booking);
                }
            }
            $em->remove($user);
            $em->flush();

            $this->addFlash('success', 'Le compte a été supprimé.');
        }

        return $this->redirectToRoute('admin_user_browse');
    }
}