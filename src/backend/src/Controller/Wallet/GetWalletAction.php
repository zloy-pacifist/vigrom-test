<?php

namespace App\Controller\Wallet;

use App\Entity\Wallet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetWalletAction extends AbstractController
{
    #[Route('/wallet/{id}/get')]
    public function get(
        Wallet $wallet,
    ): Response {
        return new JsonResponse([
            'currency' => $wallet->getCurrency()->getCode(),
            'balance' => (float) number_format($wallet->getBalance(), 4),
        ]);
    }
}
