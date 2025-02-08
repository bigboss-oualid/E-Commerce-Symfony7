<?php

namespace App\Twig;

use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FooterDataExtension extends AbstractExtension
{
    private CategoryRepository $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_footer_data', [$this, 'getFooterData']),
        ];
    }

    public function getFooterData()
    {
        return ['categories' => $this->categoryRepo->findBy([], null, 4)];
    }
}
