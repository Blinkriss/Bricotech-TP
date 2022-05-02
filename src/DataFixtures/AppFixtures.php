<?php

namespace App\DataFixtures;

use App\Entity\BlogArticle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $blogArticleTitles = [
            
        ];

        $blogArticles = [];

        foreach ($blogArticleTitles as $blogArticleTitle) {
            $blogArticle = new BlogArticle();
            $blogArticle->setTitle($blogArticleTitle);
            $manager->persist($blogArticle);

            // Pour chaque film créé, on le place dans un tableau $movies
            $blogArticles[] = $blogArticle;
        }

        $manager->flush();
    }
}
