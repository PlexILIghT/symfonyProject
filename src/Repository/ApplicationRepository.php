<?php

namespace App\Repository;

use App\Entity\Application;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Application>
 */
class ApplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Application::class);

    }

    public function findAllByUser(UserInterface $user): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.portfolio IN (:portfolios)')
            ->setParameter('portfolios', $user->getPortfolios())
            ->getQuery()
            ->getResult()
            ;
    }

    public function saveApplication(Application $application): void
    {
        $this->getEntityManager()->persist($application);
        $this->getEntityManager()->flush();
    }

    public function removeApplication(Application $application): void
    {
        $this->getEntityManager()->remove($application);
        $this->getEntityManager()->flush();
    }

    public function saveChanges(): void
    {
        $this->getEntityManager()->flush();
    }

    public function findAppropriate(Application $application): ?Application
    {
        return $this
            ->createQueryBuilder('a')
            ->where('a.stock = :stock')
            ->andWhere('a.quantity = :quantity')
            ->andWhere('a.price = :price')
            ->andWhere('a.action = :action')
            ->andWhere('a.portfolio NOT IN (:portfolios)')
            ->setParameter('stock', $application->getStock())
            ->setParameter('quantity', $application->getQuantity())
            ->setParameter('price', $application->getPrice())
            ->setParameter('action', $application->getAction()->getOpposite())
            ->setParameter('portfolios', $application->getPortfolio()->getUser()->getPortfolios())
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
    //    /**
    //     * @return Application[] Returns an array of Application objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Application
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
