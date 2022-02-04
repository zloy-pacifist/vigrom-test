<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity()]
#[ORM\Table(name: "wallet_history")]
class WalletHistory
{
    public const TYPE_CREDIT = 0;
    public const TYPE_DEBIT = 1;

    public const REASON_DEPOSIT = 0;
    public const REASON_WITHDRAW = 1;
    public const REASON_PAYMENT = 2;
    public const REASON_REFUND = 3;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "bigint", options: ["unsigned"=>true])]
    private ?int $id;

    #[ORM\Column(type: "smallint", nullable: true, options: ["unsigned"=>true])]
    #[Assert\Choice(choices: [self::TYPE_DEBIT, self::TYPE_CREDIT])]
    #[Assert\NotBlank]
    private ?int $type = null;

    #[ORM\Column(type: "smallint", nullable: true, options: ["unsigned"=>true])]
    #[Assert\Choice(choices: [self::TYPE_DEBIT, self::REASON_DEPOSIT, self::REASON_WITHDRAW, self::REASON_PAYMENT])]
    #[Assert\NotBlank]
    private ?int $reason = null;

    #[ORM\Column(type: "decimal", precision: 15, scale: 4)]
    #[Assert\Positive]
    #[Assert\NotBlank]
    private ?float $value = null;

    #[ORM\Column(type: "decimal", precision: 15, scale: 4)]
    #[Assert\Positive]
    #[Assert\NotBlank]
    private ?float $rate = null;

    #[ORM\Column(type: "datetimetz_immutable")]
    private ?\DateTimeImmutable $created_at;

    #[ORM\ManyToOne(targetEntity: Wallet::class, inversedBy: 'history')]
    #[ORM\JoinColumn(name: "wallet_id", nullable: false)]
    private ?Wallet $wallet;

    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(name: "currency_id", nullable: false)]
    private ?Currency $currency;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getReason(): ?int
    {
        return $this->reason;
    }

    public function setReason(int $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(?Wallet $wallet): self
    {
        $this->wallet = $wallet;

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
}
