<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class AdminController extends AbstractController
{
    #[Route('/admin/add-role/{id}', name: 'admin_add_role')]
    public function addRole(User $user, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $user->setRoles(array_unique(array_merge($user->getRoles(), ['ROLE_ADMIN'])));
        $entityManager->persist($user);
        $entityManager->flush();

        return new Response('Роль ROLE_ADMIN добавлена для ' . $user->getUsername());
    }
}
