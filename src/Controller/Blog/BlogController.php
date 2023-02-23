<?php

namespace App\Controller\Blog;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="app_blog")
     */
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     * @Route("/comment-résilier/{slug}", name="app_show")
     */
    public function show(): Response
    {
        return $this->render('blog/show.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     * @Route("/category/{slug}", name="app_blog_category")
     */
    public function blogCategory(): Response
    {
        return $this->render('blog/category.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }
}
