<?php

namespace App\Controller;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use App\Entity\Post;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostApiController extends AbstractController
{
    public function __invoke(
        Request $request,
        ValidatorInterface $validator,
        SluggerInterface $slugger,
        $data
    ) {
        /**
         * @var $post Post
         */
        $post = $data;
        $post->setAuthor($this->getUser());
        $post->setPublishedAt(new \DateTime());

        $form = $this->createForm(PostType::class, $post, ['isHtml' => false, 'csrf_protection' => false]);
        $form->submit($post->getFormArray());

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $post;
        }

        throw new ValidationException($validator->validate($post));

        // inny sposob, bezposredni
//        $post->setSlug($slugger->slug($post->getTitle()));
//
//        $errors = $validator->validate($post);
//
//        if ($errors->count()) {
//            throw new ValidationException($errors);
//        }
    }
}
