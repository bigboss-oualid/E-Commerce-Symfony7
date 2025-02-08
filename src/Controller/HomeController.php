<?php

/*
* @package       e-commrece_symfony_7
 * @author       Oualid Boulatar
 * @file         HomeController.php
 * @copyright    2024. ${ORGANIZATION} All rights reserved.
 * @license      Mit License
 * @link         https:www.boulatar.com
 */

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    final public function homepage(Request $request, ProductRepository $productRepo): Response
    {
        $products = $productRepo->findBy([], [], 3);

        return $this->render('home.html.twig', ['products' => $products]);
    }
}
