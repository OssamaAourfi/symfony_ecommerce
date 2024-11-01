<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;

class UserController extends AbstractController
{
    #[Route('/admin/users', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {   
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    #[Route('admin/user/{id}/to/editor',name:'app_user_to_editor')]
    public function changeRole(EntityManagerInterface $em , User $user):Response{
        $user->setRoles(["ROLE_EDITOR","ROLE_USER"]);
        $em->flush();
        $this->addFlash("success","le role editeur a ete ajouter a votre utilisateur");
        return $this->redirectToRoute('app_user');
    }
    #[Route('admin/user/{id}/remove/editor/role', name: 'app_user_remove_editor_role')]
    public function removeEditorRole(EntityManagerInterface $em, User $user): Response
    {
        $user->setRoles([]);
        $em->flush();
        $this->addFlash("success", "le role editeur a ete supprimer a votre utilisateur");
        return $this->redirectToRoute('app_user');
    }
    #[Route('admin/user/{id}/remove', name: 'app_user_remove')]
    public function removeUser(EntityManagerInterface $em, UserRepository $userRepository,$id): Response
    {
        $userFind = $userRepository->find($id);
        $em->remove($userFind);
        $em->flush();
        $this->addFlash("danger", "l'utilisateur est  supprimer ");
        return $this->redirectToRoute('app_user');
    }
    
}