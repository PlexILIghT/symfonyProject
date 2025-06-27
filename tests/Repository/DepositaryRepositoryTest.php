<?php

// tests/Repository/DepositaryRepositoryTest.php
use App\Entity\Depositary;
use App\Entity\User;
use App\Repository\DepositaryRepository;
use App\Tests\Fixture\DepositaryFixture;
use App\Tests\Fixture\PortfolioFixture;
use App\Tests\Fixture\StockFixture;
use App\Tests\Fixture\UserFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DepositaryRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private DepositaryRepository $repository;
    private DepositaryFixture $depositaryFixture;
    private UserFixture $userFixture;
    private PortfolioFixture $portfolioFixture;
    private StockFixture $stockFixture;


    protected function setUp(): void
    {
        self::bootKernel();
        $this->em = self::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->em->getRepository(Depositary::class);

        $loader = new Loader();
        $loader->addFixture($this->userFixture = new UserFixture());
        $loader->addFixture($this->portfolioFixture = new PortfolioFixture());
        $loader->addFixture($this->stockFixture = new StockFixture());
        $loader->addFixture($this->depositaryFixture = new DepositaryFixture());

        $this->executor = new ORMExecutor($this->em, new ORMPurger());
        $this->executor->execute($loader->getFixtures());

        $this->userRepository = $this->em->getRepository(User::class);
    }

    public function testRemoveDepositary(): void
    {
        $depositary = $this->depositaryFixture->getReference(DepositaryFixture::DEPOSITARY_ADMIN_REFERENCE, Depositary::class);
        $id = $depositary->getId();

        $this->assertNotNull($this->repository->find($id));

        $this->repository->removeDepositary($depositary);

        $this->assertNull($this->repository->find($id));
    }
}