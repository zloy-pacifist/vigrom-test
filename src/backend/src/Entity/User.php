<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('login')]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    public const ROLE_USER = 0;
    public const ROLE_ADMIN = 1;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "bigint", options: ["unsigned"=>true])]
    private ?int $id;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $name;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private ?string $login;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $password;

    #[ORM\Column(type: "smallint", nullable: true, options: ["unsigned"=>true, "default"=>self::ROLE_USER])]
    private int $role = self::ROLE_USER;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Wallet::class)]
    private $wallet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(?int $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(?Wallet $wallet): self
    {
        // unset the owning side of the relation if necessary
        if ($wallet === null && $this->wallet !== null) {
            $this->wallet->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($wallet !== null && $wallet->getUser() !== $this) {
            $wallet->setUser($this);
        }

        $this->wallet = $wallet;

        return $this;
    }

    public function getRoles(): array
    {
        return [
            match ($this->getRole()) {
                self::ROLE_USER => 'ROLE_USER',
                self::ROLE_ADMIN => 'ROLE_ADMIN',
                default => 'ROLE_GUEST',
            },
        ];
    }

    public function eraseCredentials()
    {
        // Do nothing
    }

    public function getUserIdentifier(): string
    {
        return $this->getLogin();
    }
}
