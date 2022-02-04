<?php

namespace App\Controller\Wallet;

use App\Entity\Wallet;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetRefundValueAction extends AbstractController
{
    #[Route('/wallet/{id}/refunded')]
    public function get(
        Wallet $wallet,
        Connection $connection,
    ): Response {

        $statement = $connection->prepare('
            SELECT SUM(wh.value * wh.rate) from wallet_history wh
            WHERE wh.wallet_id = :id and wh.created_at >= :date
            GROUP BY wh.wallet_id
        ');

        $fromDate = (new \DateTimeImmutable())->setTimestamp(time() - (86400 * 7))->format('Y-m-d H:i:s');

        $statement->bindValue('id', $wallet->getId());
        $statement->bindValue('date', $fromDate);

        $resultSet = $statement->executeQuery()->fetchOne();

        return new JsonResponse([
            'refunded' => (float) number_format($resultSet, 4),
            'from' => $fromDate,
        ]);
    }
}
