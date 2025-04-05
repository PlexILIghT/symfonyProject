<?php

namespace App\Entity;

use App\Repository\DealLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DealLogRepository::class)]
class DealLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $timestamp = null;

    #[ORM\ManyToOne(inversedBy: 'dealLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Stock $stock = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'sellDealLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Portfolio $sellPortfolio = null;

    #[ORM\ManyToOne(inversedBy: 'buyDealLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Portfolio $buyPorfolio = null;

    #[ORM\Column]
    private ?int $quantity = null;

    public function __construct()
    {
        $this->timestamp = new \DateTimeImmutable('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimestamp(): ?\DateTimeImmutable
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeImmutable $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getSellPortfolio(): ?Portfolio
    {
        return $this->sellPortfolio;
    }

    public function setSellPortfolio(?Portfolio $sellPortfolio): static
    {
        $this->sellPortfolio = $sellPortfolio;

        return $this;
    }

    public function getBuyPorfolio(): ?Portfolio
    {
        return $this->buyPorfolio;
    }

    public function setBuyPorfolio(?Portfolio $buyPorfolio): static
    {
        $this->buyPorfolio = $buyPorfolio;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }
}
