<?php

declare(strict_types=1);

namespace App\Controller;

use App\Infrastructure\RouteEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('', name: RouteEnum::HOME)]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }
    #[Route('/test', name: 'test')]
    public function test(HubInterface $publisher): Response
    {
        $update = new Update(
            'http://localhost/books/1',
            json_encode(['status' => 'OutOfStock', 'date' => (new \DateTimeImmutable())->format('Y-m-d H:i:s')])
        );

        // The Publisher service is an invokable object
        $publisher->publish($update);

        return new Response('published!');
    }
    #[Route('/test2', name: 'test2')]
    public function test2(HubInterface $publisher): Response
    {
    //    const es = new EventSource('http://localhost:3000/hub?topic=' + encodeURIComponent('http://example.com/books/1'));
    //    es.onmessage = e => {
    //    // Will be called every time an update is published by the server
    //    console.log(JSON.parse(e.data));
    //}
        return $this->render('test.html.twig');
    }
}
