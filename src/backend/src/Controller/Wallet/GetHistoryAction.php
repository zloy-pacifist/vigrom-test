<?php

namespace App\Controller\Wallet;

use App\Entity\Wallet;
use App\Entity\WalletHistory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class GetHistoryAction extends AbstractController
{
    #[Route('/wallet/{id}/history', methods: 'get', format: 'json')]
    public function history(
        Wallet $wallet,
        ManagerRegistry $doctrine,
    ): JsonResponse {
        $om = $doctrine->getManager();

        $list = $om->getRepository(WalletHistory::class)->findBy([
            'wallet' => $wallet,
        ], ['id' => 'desc']) ?: [];

        return new JsonResponse(
            array_map(
                static fn (WalletHistory $history) => [
                    'type' => $history->getType() === WalletHistory::TYPE_DEBIT ? 'debit' : 'credit',
                    'reason' => match ($history->getReason()) {
                        WalletHistory::REASON_DEPOSIT => 'deposit',
                        WalletHistory::REASON_WITHDRAW => 'withdraw',
                        WalletHistory::REASON_PAYMENT => 'payment',
                        WalletHistory::REASON_REFUND => 'refund',
                        default => 'unknown',
                    },
                    'value' => (float) number_format($history->getValue(), 4),
                    'currency' => $history->getCurrency()->getCode(),
                    'wallet_value' => (float) number_format($history->getValue() * $history->getRate(), 4),
                    'date' => $history->getCreatedAt()->format('Y-m-d H:i:s'),
                ], $list
            )
        );
    }
}
