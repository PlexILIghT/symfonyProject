<?php

// tests/Repository/HelloRepositoryTest.php
use App\Entity\Hello;
use App\Repository\HelloRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HelloRepositoryTest extends KernelTestCase
{
    private HelloRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->repository = self::getContainer()->get(HelloRepository::class);
    }

    public function testCreateLuckyNumber(): void
    {
        $number = '777';
        $entity = $this->repository->createLuckyNumber($number);

        $this->assertSame($number, $entity->getLuckyNumber());
        $this->assertNotNull($entity->getId());

        $found = $this->repository->find($entity->getId());
        $this->assertEquals($entity->getId(), $found->getId());
    }
}
