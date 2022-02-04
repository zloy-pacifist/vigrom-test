<?php

namespace App\Controller\Wallet;

use App\Entity\Currency;
use App\Entity\CurrencyRate;
use App\Entity\Wallet;
use App\Entity\WalletHistory;
use App\Exceptions\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;

class AddHistoryAction extends AbstractController
{
    #[Route('/wallet/add', methods: 'post', format: 'json')]
    public function add(
        Request $request,
        ManagerRegistry $doctrine,
    ): Response {
        $om = $doctrine->getManager();

        $parameters = json_decode($request->getContent() ?: '{}', true, 512, JSON_THROW_ON_ERROR);

        /** @var Wallet $wallet */
        $wallet = $om->find(Wallet::class, $parameters['wallet'] ?? null)
            ?: throw new ValidationException('wallet', 'Wrong wallet id')
        ;

        /** @var Currency $currency */
        $currency = $om->getRepository(Currency::class)->findOneByCode($parameters['currency'] ?? null)
            ?: throw new ValidationException('currency', 'Wrong currency code')
        ;

        $rate = 1;

        if ($wallet->getCurrency()->getId() !== $currency->getId()) {
            $rate = $om->getRepository(CurrencyRate::class)->findByFromTo(
                $currency, $wallet->getCurrency()
            )?->getRate() ?: throw new ValidationException('currency', 'Currency converting not available');
        }

        $history = new WalletHistory();
        $history->setWallet($wallet);
        $history->setCurrency($currency);
        $history->setRate($rate);

        if (($value = $parameters['value'] ?? null) !== null && is_numeric($value)) {
            $history->setValue($value);
        }
        if (($type = $parameters['type'] ?? null) !== null && is_numeric($type)) {
            $history->setType($type);
        }
        if (($reason = $parameters['reason'] ?? null) !== null && is_numeric($reason)) {
            $history->setReason($reason);
        }

        $history->setCreatedAt(new \DateTimeImmutable());

        $violations = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator()
            ->validate($history)
        ;

        if ($violations->count()) {
            throw new ValidationFailedException($history, $violations);
        }

        $wallet->addHistory($history);

        $om->persist($history);
        $om->persist($wallet);
        $om->flush();

        return new JsonResponse([
            'status' => 'ok',
        ]);
    }
}
