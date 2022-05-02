<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserEditType;
use App\Repository\ToolRepository;
use App\Repository\UserRepository;
use App\Repository\ImageRepository;
use App\Repository\BookingRepository;
use App\Security\LoginFormAuthenticator;
use App\Repository\BricosphereRepository;
use App\Repository\ToolCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\User as UserUser;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(UserRepository $userRepository): Response
    {
        return $this->render('user/browse.html.twig', ['users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/read", name="read", methods={"GET"})
     */
    public function read(Request $request, BricosphereRepository $bricosphereRepository, ToolCategoryRepository $toolCategoryRepository): Response
    {
        $user = $this->getUser();
        $id = $user->getId();
        $bricosphere = $user->getBricospheres();

        $bricosphereCreator = $bricosphereRepository->findBy(['creator' => $id]);
        $toolCategories = $toolCategoryRepository->findAll();

        return $this->render('user/read.html.twig', [
            'user' => $user,
            'bricosphere' => $bricosphere,
            'bricosphereCreator' => $bricosphereCreator,
            'toolCategories' => $toolCategories,
        ]);
    }

    /**
     * @Route("/edit", name="edit", methods={"GET|POST"})
     */
    public function edit(Request $request): Response
    {
        $user = $this->getUser();
        
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new \DateTime());
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a bien été modifié.');

            return $this->redirectToRoute('user_read');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, BricosphereRepository $bricosphereRepository, ToolRepository $toolRepository, BookingRepository $bookingRepository): Response
    {
        $submittedToken = $request->request->get('token');
        if ($this->isCsrfTokenValid('delete', $submittedToken)) {
            $user = $this->getUser();
            $id = $user->getId();


            $bricosphereArray = $bricosphereRepository->findBy(['creator' => $id]);
            $toolArray = $toolRepository->findBy(['user' => $id]);
            
            $em = $this->getDoctrine()->getManager();
            if ($bricosphereArray == !null) {
                foreach ($bricosphereArray as $key => $bricosphere) {
                    $em->remove($bricosphere);
                }
            }
                        
            if ($toolArray == !null){
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
            }

            $em->remove($user);
            $em->flush();
    
            session_unset();
    
            $this->addFlash('success', 'Votre compte a été supprimé.');
        }

        return $this->redirectToRoute('homepage');
    
    }

    /**
     * @Route("/add", name="add", methods={"GET|POST"})
     */
    public function add(Request $request, UserPasswordEncoderInterface $passwordEncoder, LoginFormAuthenticator $login, GuardAuthenticatorHandler $guard): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            $user->setPassword($passwordEncoder->encodePassword($user, $password));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre compte a bien été créé !');

            return $guard->authenticateUserAndHandleSuccess($user,$request,$login,'main');        
        }
        
        return $this->render('user/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}