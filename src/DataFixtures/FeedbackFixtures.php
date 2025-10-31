<?php

namespace WechatMiniProgramLogBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Entity\Feedback;
use WechatMiniProgramLogBundle\Enum\FeedbackType;

class FeedbackFixtures extends Fixture
{
    public const FEEDBACK_REFERENCE = 'feedback';

    public function load(ObjectManager $manager): void
    {
        $account = new Account();
        $account->setName('测试小程序');
        $account->setValid(true);
        $account->setAppId('TEST2');
        $account->setAppSecret('TEST1');
        $manager->persist($account);

        $feedback = new Feedback();
        $feedback->setAccount($account);
        $feedback->setWxRecordId('wx_record_123456');
        $feedback->setWxCreateTime(new \DateTimeImmutable());
        $feedback->setContent('这是一个测试反馈内容');
        $feedback->setPhone('13800138000');
        $feedback->setOpenid('test_openid_123');
        $feedback->setNickname('测试用户');
        $feedback->setHeadUrl('https://gravatar.com/avatar/test?s=150&d=mp');
        $feedback->setFeedbackType(FeedbackType::TYPE_1);
        $feedback->setMediaIds(['media_id_1', 'media_id_2']);
        $feedback->setSystemInfo('iOS 16.0, iPhone 14 Pro');
        $feedback->setRawData('{"test": "raw_data"}');

        $manager->persist($feedback);
        $manager->flush();

        $this->addReference(self::FEEDBACK_REFERENCE, $feedback);
    }
}
