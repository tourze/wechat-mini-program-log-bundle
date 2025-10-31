<?php

namespace WechatMiniProgramLogBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Entity\ErrorListData;

class ErrorListDataFixtures extends Fixture
{
    public const ERROR_LIST_DATA_REFERENCE = 'error-list-data';

    public function load(ObjectManager $manager): void
    {
        $account = new Account();
        $account->setName('测试小程序');
        $account->setValid(true);
        $account->setAppId('TEST2');
        $account->setAppSecret('TEST1');
        $manager->persist($account);

        $errorListData = new ErrorListData();
        $errorListData->setAccount($account);
        $errorListData->setDate(new \DateTimeImmutable('2024-01-01'));
        $errorListData->setOpenId('test_open_id');
        $errorListData->setErrorMsgCode('ERROR_001');
        $errorListData->setErrorMsg('Test error message');
        $errorListData->setUv(100);
        $errorListData->setPv(500);
        $errorListData->setErrorStackCode('STACK_001');
        $errorListData->setErrorStack('Test error stack trace');
        $errorListData->setPvPercent('2.5%');
        $errorListData->setUvPercent('5.0%');

        $manager->persist($errorListData);
        $manager->flush();

        $this->addReference(self::ERROR_LIST_DATA_REFERENCE, $errorListData);
    }
}
