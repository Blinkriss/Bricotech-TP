<?php

namespace App\Controller\Admin;

use App\Entity\Avatar;
use App\Form\AvatarType;
use App\Repository\AvatarRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/avatar", name="admin_avatar_")
 */
class AvatarController extends AbstractController
{
    /**
     * @Route("", name="browse",  methods={"GET"})
     */
    public function browse( AvatarRepository $avatarRepository): Response
    {

        return $this->render('admin/avatar/browse.html.twig', [
            'avatar' => $avatarRepository->findAll(),
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET|POST"})
     */
    public function add( Request $request): Response
    {
        $avatar = new Avatar(); 
        $form = $this->createForm(AvatarType::class, $avatar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatars = $form->get('name')->getData();

            foreach($avatars as $avatar){
                $fichier = md5(uniqid()).'.'.$avatar->guessExtension();

                $avatar->move(
                    $this->getParameter('avatar_directory'),
                    $fichier
                );

                $avatar = new Avatar;
                $avatar->setName($fichier);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($avatar);
            $em->flush();

            return $this->redirectToRoute('admin_avatar_browse');
        }

        return $this->render('admin/avatar/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id" : "\d+"}, methods={"DELETE"})
     */
    public function delete(Avatar $avatar): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($avatar);
        $em->flush();
        
        $this->addFlash('success', 'L\'avatar a bien été supprimé');

        return $this->redirectToRoute('admin_avatar_browse');
    }
}
