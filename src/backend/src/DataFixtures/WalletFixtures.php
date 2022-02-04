<?php

namespace App\DataFixtures;

use App\Entity\Currency;
use App\Entity\User;
use App\Entity\Wallet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WalletFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();
        $currencies = $manager->getRepository(Currency::class)->findAll();

        $getCurrency = static function () use ($currencies) {
            static $i = 0;

            $currency = $currencies[$i];

            if (++$i >= count($currencies)) {
                $i = 0;
            }

            return $currency;
        };

        foreach ($users as $user) {
            $manager->persist(
                $this->createWallet(
                    user: $user,
                    currency: $getCurrency(),
                )
            );
        }

        $manager->flush();
    }

    private function createWallet(
        User $user,
        Currency $currency,
    ): Wallet {
        $wallet = new Wallet();
        $wallet->setUser($user);
        $wallet->setCurrency($currency);

        return $wallet;
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CurrencyFixtures::class,
        ];
    }
}
