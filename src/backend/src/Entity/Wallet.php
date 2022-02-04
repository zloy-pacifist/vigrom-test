<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Wallet
{

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "bigint", options: ["unsigned"=>true])]
    private ?int $id;

    #[ORM\Column(type: "decimal", precision: 15, scale: 4, options: ["default"=>0.0])]
    private $balance = 0.0;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user_id", nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(name: "currency_id", nullable: false)]
    private $currency;

    #[ORM\OneToMany(mappedBy: 'wallet', targetEntity: WalletHistory::class)]
    private $history;

    public function __construct()
    {
        $this->history = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return Collection|WalletHistory[]
     */
    public function getHistory(): Collection
    {
        return $this->history;
    }

    public function addHistory(WalletHistory $history): self
    {
        if (!$this->history->contains($history)) {
            $this->history[] = $history;
            $history->setWallet($this);

            $this->balance += $history->getValue() * $history->getRate() * (
                $history->getType() === WalletHistory::TYPE_DEBIT ? 1 : - 1
            );
        }

        return $this;
    }

    public function removeHistory(WalletHistory $history): self
    {
        if ($this->history->removeElement($history)) {
            // set the owning side to null (unless already changed)
            if ($history->getWallet() === $this) {
                $history->setWallet(null);
            }
        }

        return $this;
    }
}
