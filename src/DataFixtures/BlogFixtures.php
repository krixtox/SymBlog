<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class BlogFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($j = 0; $j < 4; $j++) {
            $category = new Category();
            $category->setName($faker->word);
            $manager->persist($category);

            for ($i = 0; $i < mt_rand(1, 10); $i++) {
                $article = new Article();
                $article->setTitle($faker->sentence(6));
                $article->setContent($faker->paragraph(5, true));
                $article->setCreatedAt(new \DateTimeImmutable());
                $article->setPicture($faker->imageUrl(640, 480, 'animals', true));

                $article->setCategory($category);

                $manager->persist($article);

                // Generate random number of comments for each article
                $numComments = mt_rand(0, 5); // You can adjust the range as needed
                for ($k = 0; $k < $numComments; $k++) {
                    $comment = new Comment();
                    $comment->setAuthor($faker->name());
                    $comment->setContent($faker->paragraph(2));
                    $comment->setCreatedAt(new \DateTimeImmutable());
                    $comment->setArticle($article);

                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}