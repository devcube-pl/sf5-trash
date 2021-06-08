<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class BlogController extends AbstractController
{
    /**
     * @Route("/", defaults={"page":"1"}, name="homepage")
     * @Route("/page/{page<[1-9]\d*>}", defaults={"page":"1"}, methods="GET", name="blog_index_paginated")
     * @return Response
     */
    public function index(Request $request, PostRepository $postRepository, TagRepository $tags, $page)
    {
        $tag = null;
        if ($request->query->has('tag')) {
            $tag = $tags->findOneBy(['name' => $request->query->get('tag')]);
        }

        $paginator = $postRepository->findLatest($page, $tag);

        return $this->render(
            'blog/index.html.twig',
            ['paginator' => $paginator]
        );
    }

    /**
     * @Route("/posts/{slug}", name="blog_post")
     */
    public function postShow(PostRepository $postRepository, string $slug): Response
    {
        /*
         * Dwie opcje pobrania encji Post:
         * - metoda w repozytorium
         * - paramConverter https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
         */
        $post = $this->getDoctrine()->getRepository(Post::class)
            ->getPostWithComments($slug);

        //$id = $request->query->get('id'); // pobieranie z query ?id=
        return $this->render('blog/post_show.html.twig', ['post' => $post]);
    }

    /**
     * @Route("/comment/{postSlug}/new", methods="POST", name="comment_new")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @ParamConverter("post", options={"mapping": {"postSlug": "slug"}})
     */
    public function commentNew(Request $request, Post $post)
    {
        $comment = new Comment();
        // za pomoca metody getUser() mozna pobrac z AbstractController aktualnego usera
        $comment->setAuthor($this->getUser());
        $post->addComment($comment);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // entity managera mozna tez pobrac z helpera getDoctrine w AbstractController
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('blog_post', ['slug' => $post->getSlug()]);
        }

        return $this->renderForm(
            'blog/comment_form_error.html.twig',
            [
                'form' => $form,
                'post' => $post
            ]
        );
    }

    public function commentForm(Post $post)
    {
        return $this->renderForm(
            'blog/_comment_form.html.twig',
            [
                'form' => $this->createForm(CommentType::class),
                'post' => $post
            ]
        );
    }

    /**
     * @Route("/search", methods="GET", name="search")
     */
    public function search(Request $request, PostRepository $posts): Response
    {
        $query = $request->query->get('q', '');
        $limit = $request->query->get('l', 10);

        if (!$request->isXmlHttpRequest()) {
            return $this->render('blog/search.html.twig', ['query' => $query]);
        }

        $foundPosts = $posts->findBySearchQuery($query, $limit);

        $results = [];
        foreach ($foundPosts as $post) {
            $results[] = [
                'title' => htmlspecialchars($post->getTitle(), ENT_COMPAT | ENT_HTML5),
                'date' => $post->getPublishedAt()->format('M d, Y'),
                'author' => htmlspecialchars($post->getAuthor()->getFullName(), ENT_COMPAT | ENT_HTML5),
                'summary' => htmlspecialchars($post->getSummary(), ENT_COMPAT | ENT_HTML5),
                'url' => $this->generateUrl('blog_post', ['slug' => $post->getSlug()]),
            ];
        }

        return $this->json($results);
    }
}
