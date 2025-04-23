<?php

namespace WechatMiniProgramLogBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramLogBundle\Entity\PenaltyList;

/**
 * @method PenaltyList|null find($id, $lockMode = null, $lockVersion = null)
 * @method PenaltyList|null findOneBy(array $criteria, array $orderBy = null)
 * @method PenaltyList[]    findAll()
 * @method PenaltyList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PenaltyListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PenaltyList::class);
    }
}
