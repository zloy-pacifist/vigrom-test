<?php

namespace App\Entity;

use App\Repository\CurrencyRateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRateRepository::class)]
#[ORM\Table(name: "currency_rate")]
class CurrencyRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "bigint", options: ["unsigned"=>true])]
    private ?int $id;

    #[ORM\Column(type: "decimal", precision: 15, scale: 4)]
    private ?float $rate;

    #[ORM\ManyToOne(targetEntity: Currency::class, inversedBy: 'rates_from')]
    #[ORM\JoinColumn(name: "currency_from_id", nullable: false)]
    private ?Currency $currency_from;

    #[ORM\ManyToOne(targetEntity: Currency::class, inversedBy: 'rates_to')]
    #[ORM\JoinColumn(name: "currency_to_id", nullable: false)]
    private ?Currency $currency_to;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(string $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getCurrencyFrom(): ?Currency
    {
        return $this->currency_from;
    }

    public function setCurrencyFrom(?Currency $currency_from): self
    {
        $this->currency_from = $currency_from;

        return $this;
    }

    public function getCurrencyTo(): ?Currency
    {
        return $this->currency_to;
    }

    public function setCurrencyTo(?Currency $currency_to): self
    {
        $this->currency_to = $currency_to;

        return $this;
    }

}
