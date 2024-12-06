<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
}
