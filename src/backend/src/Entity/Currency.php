<?php

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "bigint", options: ["unsigned"=>true])]
    private ?int $id;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $code;

    #[ORM\OneToMany(mappedBy: 'currency_from', targetEntity: CurrencyRate::class)]
    private $rates_from;

    #[ORM\OneToMany(mappedBy: 'currency_to', targetEntity: CurrencyRate::class)]
    private $rates_to;

    public function __construct()
    {
        $this->rates_from = new ArrayCollection();
        $this->rates_to = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|CurrencyRate[]
     */
    public function getRatesFrom(): Collection
    {
        return $this->rates_from;
    }

    public function addRatesFrom(CurrencyRate $ratesFrom): self
    {
        if (!$this->rates_from->contains($ratesFrom)) {
            $this->rates_from[] = $ratesFrom;
            $ratesFrom->setCurrencyFrom($this);
        }

        return $this;
    }

    public function removeRatesFrom(CurrencyRate $ratesFrom): self
    {
        if ($this->rates_from->removeElement($ratesFrom)) {
            // set the owning side to null (unless already changed)
            if ($ratesFrom->getCurrencyFrom() === $this) {
                $ratesFrom->setCurrencyFrom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CurrencyRate[]
     */
    public function getRatesTo(): Collection
    {
        return $this->rates_to;
    }

    public function addRatesTo(CurrencyRate $ratesTo): self
    {
        if (!$this->rates_to->contains($ratesTo)) {
            $this->rates_to[] = $ratesTo;
            $ratesTo->setCurrencyTo($this);
        }

        return $this;
    }

    public function removeRatesTo(CurrencyRate $ratesTo): self
    {
        if ($this->rates_to->removeElement($ratesTo)) {
            // set the owning side to null (unless already changed)
            if ($ratesTo->getCurrencyTo() === $this) {
                $ratesTo->setCurrencyTo(null);
            }
        }

        return $this;
    }
}
