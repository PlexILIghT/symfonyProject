<?php

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Fixture\UserFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    private UserRepository $userRepository;
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->em = self::getContainer()->get('doctrine')->getManager();

        $loader = new Loader();
        $loader->addFixture($this->userFixture = new UserFixture());

        $this->executor = new ORMExecutor($this->em, new ORMPurger());
        $this->executor->execute($loader->getFixtures());

        $this->userRepository = $this->em->getRepository(User::class);
    }

    public function testUpgradePassword(): void
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['username' => 'admin']);
        $newPassword = 'new_hashed_password';

        $this->userRepository->upgradePassword($user, $newPassword);

        $this->assertSame($newPassword, $user->getPassword());
    }
}