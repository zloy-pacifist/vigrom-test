<?php

namespace App\DataFixtures;

use App\Entity\Currency;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CurrencyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist(
            $this->createCurrency(
                code: 'RUB',
            )
        );
        $manager->persist(
            $this->createCurrency(
                code: 'USD',
            )
        );

        $manager->flush();
    }

    private function createCurrency(
        string $code,
    ): Currency {
        $currency = new Currency();
        $currency->setCode($code);

        return $currency;
    }
}
