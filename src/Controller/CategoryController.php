<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name:'category_index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }
    
    #[Route('/category/new',name:'category_create')]
    public function create(EntityManagerInterface $em,Request $request):Response{
        $category = new Category();
        
        $form = $this->createForm(CategoryFormType::class,$category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){         
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('category_index');
        }
        return $this->render('category/new.html.twig',['form' => $form->createView()]);
        
    }
    
    #[Route('/category/{id}/update',name:'category_update')]
    public function edit(Category $category,EntityManagerInterface $em,Request $request ):Response{
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $em->flush();
          return  $this->redirectToRoute('category_index');
        }
        return $this->render('category/edit.html.twig', ['form' => $form->createView()]);
    }
    
    #[Route('/category/{id}/delete',name:'category_delete')]
    public function delete (EntityManagerInterface $em,Category $category):Response{
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('category_index');
    }
}