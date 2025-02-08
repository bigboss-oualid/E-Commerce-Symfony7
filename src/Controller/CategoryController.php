<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryController extends AbstractController
{
    #[Route('/admin/category/{id}/edit', name: 'category_edit')]
    final public function edit(
        Category $category,
        Request $request,
        SluggerInterface $slugger,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(\strtolower($slugger->slug($category->getName())));
            $em->flush();

            return $this->redirectToRoute('products_category_show', [
                'slug' => $category->getSlug(),
            ]);
        }

        return $this->render('category/edit.html.twig', [
            'formView' => $form->createView()
        ]);
    }

    #[Route('/admin/category/create', name: 'category_create')]
    final public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(\strtolower($slugger->slug($category->getName())));
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('products_category_show', [
                'slug' => $category->getSlug(),
            ]);
        }

        return $this->render('category/create.html.twig', [
            'formView' => $form->createView()
        ]);
    }
}
