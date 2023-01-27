<?php

namespace App\Controller\Blog;

use App\Entity\Blog\BlogPost;
use App\Entity\Blog\BlogCategory;
use App\Repository\Blog\BlogPostRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\Blog\BlogCategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="app_blog")
     */
    public function blog(BlogPostRepository $postRepository, BlogCategoryRepository $categoryRepository): Response
    {
        //$posts = $postRepository->findAll();
        $posts = $postRepository->findByCreatedAt();
        $categories = $categoryRepository->findAll();
        $firstPost = $posts[0]->getSlug();
        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
            'categories' => $categories,
            'current' => $firstPost,
            'menu' => 'blog',
        ]);
    }

    /**
     * @Route("/blog/{slug}", name="post_show")
     */
    public function show(BlogPost $post, BlogCategoryRepository $categoryRepository, BlogPostRepository $postRepository, string $slug): Response
    {
        $categories = $categoryRepository->findAll();
        $current = $postRepository->findBySlug($slug);
        $currentPost = $current[0]->getSlug();
        return $this->render('blog/show.html.twig', [
            'post' => $post,
            'categories' => $categories,
            'current' => $currentPost,
            'menu' => 'blog',
        ]);
    }

    /**
     * @Route("/blog/category/{slug}", name="category_listing_post")
     */
    public function categoryListingPost(BlogCategory $category, BlogPostRepository $postRepository, BlogCategoryRepository $categoryRepository): Response
    {
        $posts = $postRepository->findByCategory($category);
        $categories = $categoryRepository->findAll();
        $firstPost = $posts[0]->getSlug();
        return $this->render('blog/category.html.twig', [
            'category' => $category,
            'posts' => $posts,
            'categories' => $categories,
            'current' => $firstPost,
            'menu' => 'blog',
        ]);
    }
}
