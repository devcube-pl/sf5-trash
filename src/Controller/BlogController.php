<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @Route("/page/{page<[1-9]\d*>}", defaults={"_format"="html"}, methods="GET", name="blog_index_paginated")
     * @return Response
     */
    public function index(PostRepository $postRepository)
    {
        $posts = $postRepository->findLatest();
//        dump($posts);

        return $this->render(
            'blog/index.html.twig',
            ['posts' => $posts]
        );
    }

    /**
     * @Route("/posts/{slug}", name="blog_post")
     */
    public function postShow($slug)
    {
        // tutaj mozemy jakos wykorzystac $id
        return $this->render(
            'blog/post_show.html.twig'
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
