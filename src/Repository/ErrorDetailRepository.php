<?php

namespace WechatMiniProgramLogBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramLogBundle\Entity\ErrorDetail;

/**
 * @method ErrorDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method ErrorDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method ErrorDetail[]    findAll()
 * @method ErrorDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ErrorDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ErrorDetail::class);
    }
}
