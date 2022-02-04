<?php

namespace App\Repository;

use App\Entity\Currency;
use App\Entity\CurrencyRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CurrencyRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method CurrencyRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method CurrencyRate[]    findAll()
 * @method CurrencyRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyRate::class);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByFromTo(
        Currency $currencyFrom,
        Currency $currencyTo,
    ): ?CurrencyRate {
        return $this->createQueryBuilder('cr')
            ->andWhere('cr.currency_from = :from')
            ->andWhere('cr.currency_to = :to')
            ->setParameter('from', $currencyFrom)
            ->setParameter('to', $currencyTo)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    // /**
    //  * @return CurrencyRate[] Returns an array of CurrencyRate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
