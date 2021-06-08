<?php

namespace App\Tests\Controller;

use App\Pagination\Paginator;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

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

    public function testCommentFormAccessDeniedForAnonymousUser(): void
    {
        $client = static::createClient();
        $client->request('POST', '/comment/pellentesque-vitae-velit-ex/new');

        // sprawdz czy jest przekierowania na logowanie

        $this->assertResponseRedirects(
            'http://localhost/login',
            Response::HTTP_FOUND,
            'Dodanie komentarza mozliwe tylko dla zalogowanych'
        );
    }

    public function testAjaxSearch(): void
    {
        $client = static::createClient();
        $client->xmlHttpRequest('GET', '/pl/search', ['q' => 'lorem']);

        $results = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertCount(1, $results);
        $this->assertSame('Lorem ipsum dolor sit amet consectetur adipiscing elit', $results[0]['title']);
        $this->assertSame('Jan Kowalski', $results[0]['author']);
    }
}
