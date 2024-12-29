<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends AbstractController
{
    #[Route('/{slug}', name: 'products_category_show')]
    final public function category(string $slug, CategoryRepository $categoryRepo): Response
    {
        $category = $categoryRepo->findOneBySlug($slug);

        if (!$category) {
            throw $this->createNotFoundException('Category Not Found');
        }

        return $this->render('product/category.html.twig', ['category' => $category,]);
    }

    #[Route('/{categorySlug}/{productSlug}', name: 'product_show')]
    final public function show(string $productSlug, ProductRepository $productRepo): Response
    {
        $product = $productRepo->findOneBySlug($productSlug);

        if (!$product) {
            throw $this->createNotFoundException('Product Not Found');
        }

        return $this->render('product/show.html.twig', ['product' => $product,]);
    }

    #[Route('/admin/product/{id}/edit', name: 'product_edit')]
    final public function edit(Product $product, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProductType::class, $product, [
            'validations_groups' => ['default','with-price'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            return $this->redirectToRoute('product_show', [
                'categorySlug' => $product->getCategory()->getSlug(),
                'productSlug' => $product->getSlug(),
            ]);
        }

        return $this->render('product/edit.html.twig', [
            'formView' => $form->createView()
        ]);
    }

    #[Route('/admin/product/create', name: 'product_create')]
    final public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $product->setSlug(\strtolower($slugger->slug($product->getName())));
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_show', [
                'categorySlug' => $product->getCategory()->getSlug(),
                'productSlug' => $product->getSlug(),
            ]);
        }

        return $this->render('product/create.html.twig', [
            'formView' => $form->createView()
        ]);
    }
}
