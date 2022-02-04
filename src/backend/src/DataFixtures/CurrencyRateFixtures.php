<?php

namespace App\DataFixtures;

use App\Entity\Currency;
use App\Entity\CurrencyRate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CurrencyRateFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $currencies = $manager->getRepository(Currency::class)->findAll();

        $cnt = count($currencies);
        foreach ($currencies as $n => $currency1) {
            for ($i = $n + 1 ; $i < $cnt; $i++) {
                $rate = random_int(50, 150) / 100;
                $currency2 = $currencies[$i];

                $manager->persist(
                    $this->createCurrencyRate(
                        from: $currency1,
                        to: $currency2,
                        rate: $rate,
                    )
                );
                $manager->persist(
                    $this->createCurrencyRate(
                        from: $currency2,
                        to: $currency1,
                        rate: 1 / $rate,
                    )
                );
            }
        }

        $manager->flush();
    }

    private function createCurrencyRate(
        Currency $from,
        Currency $to,
        float $rate,
    ): CurrencyRate {
        $currencyRate = new CurrencyRate();
        $currencyRate->setCurrencyFrom($from);
        $currencyRate->setCurrencyTo($to);
        $currencyRate->setRate($rate);

        return $currencyRate;
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CurrencyFixtures::class,
        ];
    }
}
