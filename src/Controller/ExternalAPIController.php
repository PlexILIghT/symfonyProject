<?php

namespace App\Controller;

use Deposit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExternalAPIController extends AbstractController
{

    public function __construct(
        private readonly HttpClientInterface $client,
    ) {}

    #[Route('/external/deposit', name: 'app_external_api')]
    public function index(): Response
    {
        $cache = new FilesystemAdapter();
        $deposits = $cache->get('max_datetime_deposits', function (ItemInterface $item) {
            $item->expiresAfter(3600 * 24);

            $currentDate = new \DateTime();
            $response = $this->client->request(
                'GET',
                "https://www.cbr.ru/dataservice/data?y1={$currentDate->format('Y')}&y2={$currentDate->format('Y')}&publicationId=18&datasetId=37&measureId=2");

            $rawData = $response->toArray()['RawData'];
            $maxDateTime = new \DateTime('@0');

            array_walk($rawData, function ($item) use (&$maxDateTime) {
                $date = new \DateTime($item['date']);
                if ($maxDateTime < $date) {
                    $maxDateTime = $date;
                }
            });

            $maxDateTimeData = array_filter($rawData, function ($item) use ($maxDateTime) {
                return $maxDateTime->format("Y-m-d\TH:i:s") === $item['date'];
            });
            $deposits = [];
            //dd($response->toArray());
            foreach ($maxDateTimeData as $data) {
                $deposits[] = new Deposit($data, $response->toArray()['headerData']);
            }
            $item->set($deposits);
            return $deposits;
        });

        return $this->render('external/index.html.twig', [
            'deposits' => $deposits,
        ]);

    }
}
