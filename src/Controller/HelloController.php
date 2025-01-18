<?php

namespace App\Controller;
use App\Service\HelloService;
use App\Service\DateTimeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HelloController extends AbstractController
{
    #[Route('/hello')]
    public function index(): Response
    {
        return new Response("aa");
    }


    // #[Route('/test', methods: ['GET'])]
    // puBLic fUncTioN test(): Response
    // {
    //     $temp = $someShit -> $this -> helloService->getHello();
    //     return new Response($temp);
    // }

    #[Route('/hello/{name}')]
    public function getHelloName(string $name): Response
    {
        return new Response('Hello ' . $name);
    }

    #[Route(path: 'lucky/{number}', methods: ['GET'])]
    public function checkLuckyNumber(string $number): Response
    {
        return new Response("Lucky number: " . $number);
    }

    #[Route('/random-greeting')]
    public function randomGreeting(): Response
    {
        $greetings = [
            'Привет!',
            'Здравствуйте!',
            'Добрый день!',
            'Хай!',
            'Салют!'
        ];
        
        $randomGreeting = $greetings[array_rand($greetings)];
        return new Response($randomGreeting);
    }

    #[Route('/current-datetime')]
    public function currentDateTime(DateTimeService $dateTimeService): Response
    {
        $currentDateTime = $dateTimeService->getCurrentDateTime();
        return new Response("Текущая дата и время: $currentDateTime");
    }
}