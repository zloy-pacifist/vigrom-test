<?php

namespace App\Controller\Currency;

use App\Entity\Currency;
use App\Entity\User;
use App\Entity\Wallet;
use App\Entity\WalletHistory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class ListAction extends AbstractController
{
    #[Route('/currency/list', methods: 'get', format: 'json')]
    public function history(
        ManagerRegistry $doctrine,
    ): JsonResponse {
        $om = $doctrine->getManager();

        $list = $om->getRepository(Currency::class)->findAll();

        return new JsonResponse(
            array_map(
                static fn (Currency $currency) => [
                    'id' => $currency->getId(),
                    'code' => $currency->getCode(),
                ], $list
            )
        );
    }
}
