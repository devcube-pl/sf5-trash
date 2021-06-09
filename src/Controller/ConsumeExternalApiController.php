<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use Symfony\Component\Routing\Annotation\Route;

class ConsumeExternalApiController extends AbstractController
{
    /**
     * @Route("/test-consume")
     *
     * @param  HttpClientInterface  $client
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function index(HttpClientInterface $client, SerializerInterface $serializer)
    {
//        $response = $client->request(
//            'GET',
//            'http://nginx/api/posts/151',
//            [
//                'headers' => [
//                    'Accept' => 'application/ld+json',
//                    'X-AUTH-TOKEN' => 'bb853616a318271aa1b3e9299c4f5fd4'
//                ]
//            ]
//        );
//
//        if ($response->getStatusCode() == 200) {
//            $post = $serializer->deserialize($response->getContent(), Post::class, 'jsonld');
//            dump($post);
//        }

//        $response = $client->request(
//            'GET',
//            'http://nginx/api/posts',
//            [
//                'headers' => [
//                    'Accept' => 'application/ld+json',
//                    'X-AUTH-TOKEN' => 'bb853616a318271aa1b3e9299c4f5fd4'
//                ]
//            ]
//        );
//
//        $data = json_decode($response->getContent(), true);
//
//        foreach ($data['hydra:member'] as $member) {
//            $post = $serializer->deserialize(json_encode($member), Post::class, 'jsonld');
//            dump($post);
//        }

        return new Response('<body>tu nie ma nic</body>');
    }
}
