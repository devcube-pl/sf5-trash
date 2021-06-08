<?php

namespace App\Tests\Controller;

use App\Pagination\Paginator;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $this->assertCount(
            Paginator::PAGE_SIZE,
            $crawler->filter('article.post'),
            'Strona glowna wyswietla wlasciwa liczbe postow'
        );
    }

    public function testNewComment()
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('jane_admin@example.com');

        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/');
        $postLink = $crawler->filter('article.post > h2 a')->link();

        $client->click($postLink);

        $commentText = 'Siema, to jest mój komentarz';

        $crawler = $client->submitForm(
            'Skomentuj',
            [
                'comment[content]' => $commentText
            ]
        );

        $newComment = $crawler->filter('.post-comment')->last()->filter('div > p')->text();

        $this->assertSame($commentText, $newComment);
    }
}
