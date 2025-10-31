<?php

namespace WechatMiniProgramLogBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Entity\ErrorDetail;

class ErrorDetailFixtures extends Fixture
{
    public const ERROR_DETAIL_REFERENCE = 'error-detail';

    public function load(ObjectManager $manager): void
    {
        $account = new Account();
        $account->setName('测试小程序');
        $account->setValid(true);
        $account->setAppId('TEST2');
        $account->setAppSecret('TEST1');
        $manager->persist($account);

        $errorDetail = new ErrorDetail();
        $errorDetail->setAccount($account);
        $errorDetail->setDate(new \DateTimeImmutable('2024-01-01'));
        $errorDetail->setOpenId('test_open_id');
        $errorDetail->setErrorMsgCode('ERROR_001');
        $errorDetail->setErrorMsg('Test error message');
        $errorDetail->setErrorStackCode('STACK_001');
        $errorDetail->setErrorStack('Test error stack trace');
        $errorDetail->setCount('5');
        $errorDetail->setSdkVersion('2.0.0');
        $errorDetail->setClientVersion('1.5.0');
        $errorDetail->setTimeStamp(new \DateTimeImmutable());
        $errorDetail->setAppVersion('1.0.0');
        $errorDetail->setDs('test_ds');
        $errorDetail->setOsName('iOS');
        $errorDetail->setPluginVersion('1.0.0');
        $errorDetail->setAppId('test_app_id');
        $errorDetail->setDeviceModel('iPhone 12');
        $errorDetail->setSource('miniprogram');
        $errorDetail->setRoute('/pages/index/index');
        $errorDetail->setUin('test_uin');
        $errorDetail->setNickname('测试用户');

        $manager->persist($errorDetail);
        $manager->flush();

        $this->addReference(self::ERROR_DETAIL_REFERENCE, $errorDetail);
    }
}
