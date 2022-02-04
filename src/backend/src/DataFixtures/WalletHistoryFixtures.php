<?php

namespace App\DataFixtures;

use App\Entity\Currency;
use App\Entity\User;
use App\Entity\Wallet;
use App\Entity\WalletHistory;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WalletHistoryFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var Wallet[] $wallets */
        $wallets = $manager->getRepository(Wallet::class)->findAll();

        /** @var Currency[] $currencies */
        $currencies = $manager->getRepository(Currency::class)->findAll();

        $getCurrency = static function () use ($currencies): Currency {
            static $i = 0;

            $currency = $currencies[$i];

            if (++$i >= count($currencies)) {
                $i = 0;
            }

            return $currency;
        };

        foreach ($wallets as $wallet) {
            for ($i = 0; $i < 100; $i++) {
                $history = $this->createWalletHistory(
                    wallet: $wallet,
                    currency: $getCurrency(),
                    timestamp: time() - ((100 - $i) * 86400),
                );
                $wallet->addHistory($history);
                $manager->persist($history);
            }
            $manager->persist($wallet);
        }

        $manager->flush();
    }

    private function createWalletHistory(
        Wallet $wallet,
        Currency $currency,
        int $timestamp,
    ): WalletHistory {
        $history = new WalletHistory();

        $history->setWallet($wallet);
        $history->setCurrency($currency);

        $type = random_int(0, 2) ? WalletHistory::TYPE_DEBIT : WalletHistory::TYPE_CREDIT;

        $reason = match ($type) {
            WalletHistory::TYPE_DEBIT => random_int(0, 1) ? WalletHistory::REASON_DEPOSIT : WalletHistory::REASON_REFUND,
            WalletHistory::TYPE_CREDIT => random_int(0, 1) ? WalletHistory::REASON_WITHDRAW : WalletHistory::REASON_PAYMENT,
        };

        $history->setType($type);
        $history->setReason($reason);
        $history->setValue(random_int(10, 1000) / 100);
        $history->setRate(random_int(50, 150) / 100);
        $history->setCreatedAt(
            (new DateTimeImmutable())->setTimestamp($timestamp)
        );

        return $history;
    }

    public function getDependencies(): array
    {
        return [
            WalletFixtures::class,
        ];
    }
}
