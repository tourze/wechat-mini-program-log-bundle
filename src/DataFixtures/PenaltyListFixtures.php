<?php

namespace WechatMiniProgramLogBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramLogBundle\Entity\PenaltyList;
use WechatMiniProgramLogBundle\Enum\PenaltyStatus;

class PenaltyListFixtures extends Fixture
{
    public const PENALTY_LIST_REFERENCE = 'penalty-list';

    public function load(ObjectManager $manager): void
    {
        $penaltyList = new PenaltyList();
        $penaltyList->setIllegalOrderId('ORDER_123456');
        $penaltyList->setComplaintOrderId('COMPLAINT_789');
        $penaltyList->setIllegalTime(new \DateTimeImmutable());
        $penaltyList->setIllegalWording('违规商品描述');
        $penaltyList->setPenaltyStatus(PenaltyStatus::TYPE_2);
        $penaltyList->setMinusScore(10);
        $penaltyList->setOrderId('ORDER_MAIN_123');
        $penaltyList->setCurrentScore(90);
        $penaltyList->setRawData('{"penalty": "test_data"}');

        $manager->persist($penaltyList);
        $manager->flush();

        $this->addReference(self::PENALTY_LIST_REFERENCE, $penaltyList);
    }
}
