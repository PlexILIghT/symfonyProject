<?php

namespace App\Controller;

use App\Entity\Depositary;
use App\Repository\ApplicationRepository;
use App\Repository\PortfolioRepository;
use App\Repository\StockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
#[Route('admin/')]
class AdminController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly StockRepository $stockRepository,
        private readonly PortfolioRepository $portRepo,
    ) {}

    #[Route('add-role-admin/', name: 'add-role-admin')]
    public function addRole(User $user): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $user->setRoles(array_unique(array_merge($user->getRoles(), ['ROLE_ADMIN', 'ROLE_SUPER_ADMIN'])));
        $this->em->persist($user);
        $this->em->flush();

        return new Response('Роль ROLE_ADMIN добавлена пользователю.');
    }

    #[Route('give-all-stocks/', name: 'give-allstocks')]
    public function giveAllStocks(): Response
    {
        $stocks = $this->stockRepository->findAll();
        foreach ($this->portRepo->findAll() as $port) {
            foreach ($stocks as $stock) {
                $depositary = new Depositary();
                $depositary->setStock($stock);
                $depositary->setPortfolio($port);
                $depositary->setQuantity(10000);
                $this->em->persist($depositary);
                $this->em->flush();
            }
        }

        return new Response('Success', Response::HTTP_OK);
    }

    #[Route('delete-all-stocks/', name: 'delete-all-stocks')]
    public function deleteAllStocks(): Response
    {
        $portfolios = $this->portRepo->findAll();
        foreach ($portfolios as $portfolio) {
            $depositaries = $portfolio->getDepositaries();
            foreach ($depositaries as $depositary) {
                $this->em->remove($depositary);
                $this->em->flush();
            }
        }
        return new Response('Success', Response::HTTP_OK);
    }
}
