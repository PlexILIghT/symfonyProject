<?php

namespace App\Controller;

use Deposit;
use Psr\Cache\CacheItemPoolInterface;
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
        private readonly CacheItemPoolInterface $cache,
    ) {}

    #[Route('/external/deposit', name: 'app_external_api')]
    public function index(): Response
    {
        $cacheKey = 'max_datetime_deposits';
        $cacheItem = $this->cache->getItem($cacheKey);

        if (!$cacheItem->isHit()) {
            $deposits = $this->fetchDepositsData();
            $cacheItem->set($deposits);
            $cacheItem->expiresAfter(3600 * 24);
            $this->cache->save($cacheItem);
        } else {
            $deposits = $cacheItem->get();
        }

        return $this->render('external/index.html.twig', [
            'deposits' => $deposits,
        ]);
    }

    private function fetchDepositsData(): array
    {
        $currentDate = new \DateTime();
        $response = $this->client->request(
            'GET',
            "https://www.cbr.ru/dataservice/data?y1={$currentDate->format('Y')}&y2={$currentDate->format('Y')}&publicationId=18&datasetId=37&measureId=2"
        );

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
        foreach ($maxDateTimeData as $data) {
            $deposits[] = new Deposit($data, $response->toArray()['headerData']);
        }

        return $deposits;
    }
}