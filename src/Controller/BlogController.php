<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog_all')]
    public function index(ArticleRepository $artRepo): Response
    {

        $articles = $artRepo->findAll();
        return $this->render('blog/index.html.twig', [
            'pageTitle' => "Bienvenue sur mon Blog Symfony!!!",
            'articles' => $articles
        ]);
    }

    #[Route('/blog/new', name: 'blog_add')]
    #[Route('/blog/edit/{id}', name: 'blog_edit')]
    public function addArticle(Article $article = null, Request $req, EntityManagerInterface $em): Response
    {

        if (!$article) {
            $article = new Article();
            $article->setCreatedAt(new \DateTimeImmutable());
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('blog_art', [
                'id' => $article->getId()
            ]);

        }

        return $this->render('blog/blog_add_art.html.twig', [
            'pageTitle' => "Créer article",
            'formArt' => $form->createView(),
            'mode' => $article->getId() !== null
        ]);
    }

    #[Route('/blog/{id}', name: 'blog_art')]
    public function showArticle(Article $article): Response
    {
        return $this->render('blog/art_detail.html.twig', [
            'pageTitle' => "Detail article",
            'article' => $article
        ]);
    }

    #[Route('/home', name: 'home')]
    public function home()
    {
        return $this->render('blog/home.html.twig', [
            'pageTitle' => "Accueil",
        ]);
    }

}