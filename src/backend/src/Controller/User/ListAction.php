<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Entity\Wallet;
use App\Entity\WalletHistory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class ListAction extends AbstractController
{
    #[Route('/user/list', methods: 'get', format: 'json')]
    public function history(
        ManagerRegistry $doctrine,
    ): JsonResponse {
        $om = $doctrine->getManager();

        $list = $om->getRepository(User::class)->findAll();

        return new JsonResponse(
            array_map(
                static fn (User $user) => [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'wallet' => $user->getWallet()->getId(),
                    'role' => $user->getRole(),
                ], $list
            )
        );
    }
}
