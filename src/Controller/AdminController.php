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
    #[Route('/admin/add-role/{id}', name: 'admin_add_role')]
    public function addRole(User $user): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $user->setRoles(array_unique(array_merge($user->getRoles(), ['ROLE_ADMIN'])));
        $this->em->persist($user);
        $this->em->flush();

        return new Response('Роль ROLE_ADMIN добавлена пользователю.');
    }

    #[Route('give-all-stocks/', name: 'admin_add_role')]
    public function giveAllStocks(): Response
    {
        $stocks = $this->stockRepository->findAll();
        foreach ($stocks as $stock) {
            foreach ($this->portRepo->findAll() as $port) {
                $depositary = // TODO: Add depositary with big stock quantity;
                $this->$port->addDepositary();
            }
        }

        return Response::HTTP_SEE_OTHER;
    }
}
