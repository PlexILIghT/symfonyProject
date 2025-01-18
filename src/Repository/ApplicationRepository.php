<?php

namespace App\Repository;

use App\Entity\Application;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @extends ServiceEntityRepository<Application>
 */
class ApplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Application::class);
    }

    public function deleteApplication(Application $application): void
    {
        $this->getEntityManager()->remove($application);
        $this->getEntityManager()->flush();
    }

    public function editApplication(Application $application): void
    {
        $this->getEntityManager()->persist($application);
        $this->getEntityManager()->flush();
    }

    public function findAppropriate(Application $application): ?Application
    {
        return $this
        ->createQueryBuilder('a')
        ->where('a.stock_id = :stock_id')
        ->andWhere('a.quantity = :quantity')
        ->andWhere('a.price = :price')
        ->andWhere('a.action = :action')
        ->andWhere('a.user_id != user_id')
        ->andWhere('a.porfolio_id NOT IN (:porfolios')
        ->setParameters(new ArrayCollection([
            'stock_id' => $application->getStock()->getId(),
            'quantity' => $application->getQuantity(),
            'price' => $application->getPrice(),
            'action' => $application->getAction()->getOppositeAction()->value,
            'user_id' => $application->getUser()->getId(),
        ])
        )
        ->getQuery()
        ->getOneOrNullResult()
    ;
    }

    public function saveChanges() {

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
