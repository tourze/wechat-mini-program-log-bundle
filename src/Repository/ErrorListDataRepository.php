<?php

namespace WechatMiniProgramLogBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramLogBundle\Entity\ErrorListData;

/**
 * @method ErrorListData|null find($id, $lockMode = null, $lockVersion = null)
 * @method ErrorListData|null findOneBy(array $criteria, array $orderBy = null)
 * @method ErrorListData[]    findAll()
 * @method ErrorListData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ErrorListDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ErrorListData::class);
    }
}
