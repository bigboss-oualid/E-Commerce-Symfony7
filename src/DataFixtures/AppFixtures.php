<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Bezhanov\Faker\Provider\Commerce;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Liior\Faker\Prices;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    final public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('de_DE');
        $faker->addProvider(new Prices($faker));
        $faker->addProvider(new Commerce($faker));
        $faker->addProvider(new PicsumPhotosProvider($faker));

        // Create Categories
        for ($c = 0; $c < 3; $c++) {
            $category = new Category();
            $category->setName($faker->department)
                ->setSlug(\strtolower($this->slugger->slug($category->getName())));

            $manager->persist($category);

            // Create Products & add category
            for ($p = 0; $p < \mt_rand(15, 20); $p++) {
                $product = new Product();
                $product->setName($faker->productName())
                    ->setPrice($faker->price(4000, 20000))
                    ->setSlug(\strtolower($this->slugger->slug($product->getName())))
                    ->setCategory($category)
                    ->setShortDescription($faker->paragraph())
                    ->setMainPicture($faker->imageUrl(400, 400, true));

                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
