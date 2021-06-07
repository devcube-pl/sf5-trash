<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/", defaults={"page":"1"}, name="homepage")
     * @Route("/page/{page<[1-9]\d*>}", defaults={"page":"1"}, methods="GET", name="blog_index_paginated")
     * @return Response
     */
    public function index(PostRepository $postRepository, $page)
    {
        $paginator = $postRepository->findLatest($page);
//        dump($posts);

        return $this->render(
            'blog/index.html.twig',
            ['paginator' => $paginator]
        );
    }

    /**
     * @Route("/posts/{slug}", name="blog_post")
     */
    public function postShow(Post $post)
    {
        return $this->render(
            'blog/post_show.html.twig',
            ['post' => $post]
        );
    }

    /**
     * @Route("/search")
     * @return Response
     */
    public function search()
    {
        return $this->render('blog/search.html.twig');
    }
}
