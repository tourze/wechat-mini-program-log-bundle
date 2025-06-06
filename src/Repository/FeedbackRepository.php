<?php

namespace WechatMiniProgramLogBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramLogBundle\Entity\Feedback;

/**
 * @method Feedback|null find($id, $lockMode = null, $lockVersion = null)
 * @method Feedback|null findOneBy(array $criteria, array $orderBy = null)
 * @method Feedback[]    findAll()
 * @method Feedback[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedbackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Feedback::class);
    }
}
