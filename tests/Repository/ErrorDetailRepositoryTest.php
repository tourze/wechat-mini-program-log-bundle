<?php

namespace WechatMiniProgramLogBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Entity\ErrorDetail;
use WechatMiniProgramLogBundle\Repository\ErrorDetailRepository;

/**
 * @internal
 */
#[CoversClass(ErrorDetailRepository::class)]
#[RunTestsInSeparateProcesses]
final class ErrorDetailRepositoryTest extends AbstractRepositoryTestCase
{
    private ErrorDetailRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(ErrorDetailRepository::class);
    }

    public function testFindByWithEmptyCriteriaShouldReturnAllEntities(): void
    {
        $entity = $this->createErrorDetail();
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy([]);

        $this->assertCount(2, $result);  // 已经有一条初始数据了
    }

    public function testFindByWithMatchingCriteriaShouldReturnCorrectEntities(): void
    {
        $entity = $this->createErrorDetail();
        $entity->setErrorMsgCode('E001');
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy(['errorMsgCode' => 'E001']);

        $this->assertCount(1, $result);
        $this->assertSame($entity->getId(), $result[0]->getId());
    }

    public function testFindOneByWithOrderByOnAccountAssociation(): void
    {
        $account1 = new Account();
        $account1->setName('Account A');
        $account1->setAppId('app_id_a');
        $account1->setAppSecret('secret_a');
        self::getEntityManager()->persist($account1);

        $account2 = new Account();
        $account2->setName('Account B');
        $account2->setAppId('app_id_b');
        $account2->setAppSecret('secret_b');
        self::getEntityManager()->persist($account2);

        self::getEntityManager()->flush();

        $entity1 = new ErrorDetail();
        $entity1->setAccount($account1);
        $entity1->setDate(new \DateTimeImmutable());
        $entity1->setOpenId('test_openid_1');
        $entity1->setErrorMsgCode('E001');
        $entity1->setErrorMsg('Test error message');
        $entity1->setTimeStamp(new \DateTimeImmutable());

        $entity2 = new ErrorDetail();
        $entity2->setAccount($account2);
        $entity2->setDate(new \DateTimeImmutable());
        $entity2->setOpenId('test_openid_2');
        $entity2->setErrorMsgCode('E001');
        $entity2->setErrorMsg('Test error message');
        $entity2->setTimeStamp(new \DateTimeImmutable());

        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);

        $result = $this->repository->findOneBy(['errorMsgCode' => 'E001'], ['account' => 'DESC']);

        $this->assertNotNull($result);
        $this->assertSame($entity2->getId(), $result->getId());
    }

    public function testSaveShouldPersistEntity(): void
    {
        $entity = $this->createErrorDetail();

        $this->repository->save($entity);

        $this->assertEntityPersisted($entity);
        $this->assertNotNull($entity->getId());
    }

    public function testSaveWithoutFlushShouldNotPersistImmediately(): void
    {
        $entity = $this->createErrorDetail();

        $this->repository->save($entity, false);

        $em = self::getEntityManager();
        $em->clear();
        $found = $em->find(ErrorDetail::class, $entity->getId());
        $this->assertNull($found);
    }

    public function testRemoveShouldDeleteEntity(): void
    {
        $entity = $this->createErrorDetail();
        $this->persistAndFlush($entity);
        $entityId = $entity->getId();

        $this->repository->remove($entity);

        $this->assertEntityNotExists(ErrorDetail::class, $entityId);
        $this->assertNotNull($entityId);
    }

    public function testRemoveWithoutFlushShouldNotDeleteImmediately(): void
    {
        $entity = $this->createErrorDetail();
        $this->persistAndFlush($entity);

        $this->repository->remove($entity, false);

        $em = self::getEntityManager();
        $em->clear();
        $found = $em->find(ErrorDetail::class, $entity->getId());
        $this->assertNotNull($found);
    }

    private function createErrorDetail(): ErrorDetail
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test_app_id_' . uniqid());
        $account->setAppSecret('test_app_secret');

        self::getEntityManager()->persist($account);
        self::getEntityManager()->flush();

        $entity = new ErrorDetail();
        $entity->setAccount($account);
        $entity->setDate(new \DateTimeImmutable());
        $entity->setOpenId('test_openid_' . uniqid());
        $entity->setErrorMsgCode('E' . rand(100, 999));
        $entity->setErrorMsg('Test error message');
        $entity->setTimeStamp(new \DateTimeImmutable());

        return $entity;
    }

    public function testFindByWithAccountAssociation(): void
    {
        $entity = $this->createErrorDetail();
        $account = $entity->getAccount();
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy(['account' => $account]);

        $this->assertCount(1, $result);
        $this->assertSame($entity->getId(), $result[0]->getId());
    }

    public function testCountWithAccountAssociation(): void
    {
        $entity = $this->createErrorDetail();
        $account = $entity->getAccount();
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['account' => $account]);

        $this->assertSame(1, $count);
    }

    public function testFindByWithNullableFieldIsNull(): void
    {
        $entity = $this->createErrorDetail();
        // 不设置 errorStackCode，它默认为 null
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy(['errorStackCode' => null]);

        $this->assertCount(1, $result);  // 只有新创建的实体 errorStackCode 为 null
        // 验证新创建的实体在结果中
        $this->assertSame($entity->getId(), $result[0]->getId());
    }

    public function testCountWithNullableFieldIsNull(): void
    {
        $entity = $this->createErrorDetail();
        // 不设置 errorStack，它默认为 null
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['errorStack' => null]);

        $this->assertSame(1, $count);  // 只有新创建的实体 errorStack 为 null
    }

    public function testFindByWithNullableDateFieldIsNull(): void
    {
        $entity = $this->createErrorDetailWithNullDate();
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy(['date' => null]);

        $this->assertCount(1, $result);
        $this->assertSame($entity->getId(), $result[0]->getId());
    }

    public function testCountWithNullableDateFieldIsNull(): void
    {
        $entity = $this->createErrorDetailWithNullTimestamp();
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['timeStamp' => null]);

        $this->assertSame(1, $count);
    }

    public function testFindByWithMoreNullableFields(): void
    {
        $entity = $this->createErrorDetailWithMultipleNullFields();
        $this->persistAndFlush($entity);

        // Test openId null
        $result = $this->repository->findBy(['openId' => null]);
        $this->assertCount(1, $result);  // openId 在初始数据中有值

        // Test errorMsg null
        $result = $this->repository->findBy(['errorMsg' => null]);
        $this->assertCount(1, $result);  // errorMsg 在初始数据中有值

        // Test count field null
        $result = $this->repository->findBy(['count' => null]);
        $this->assertCount(1, $result);  // 只有新创建的实体 count 为 null，初始数据中有值
    }

    public function testCountWithMoreNullableFields(): void
    {
        $entity = $this->createErrorDetailWithMultipleNullFields();
        $this->persistAndFlush($entity);

        // Test openId null count
        $count = $this->repository->count(['openId' => null]);
        $this->assertSame(1, $count);

        // Test errorMsg null count
        $count = $this->repository->count(['errorMsg' => null]);
        $this->assertSame(1, $count);

        // Test sdkVersion null count
        $count = $this->repository->count(['sdkVersion' => null]);
        $this->assertSame(1, $count);  // 只有新创建的实体 sdkVersion 为 null

        // Test clientVersion null count
        $count = $this->repository->count(['clientVersion' => null]);
        $this->assertSame(1, $count);  // 只有新创建的实体 clientVersion 为 null

        // Test appVersion null count
        $count = $this->repository->count(['appVersion' => null]);
        $this->assertSame(1, $count);  // 只有新创建的实体 appVersion 为 null

        // Test ds null count
        $count = $this->repository->count(['ds' => null]);
        $this->assertSame(1, $count);  // 只有新创建的实体 ds 为 null

        // Test osName null count
        $count = $this->repository->count(['osName' => null]);
        $this->assertSame(1, $count);  // 只有新创建的实体 osName 为 null

        // Test pluginVersion null count
        $count = $this->repository->count(['pluginVersion' => null]);
        $this->assertSame(1, $count);  // 只有新创建的实体 pluginVersion 为 null

        // Test appId null count
        $count = $this->repository->count(['appId' => null]);
        $this->assertSame(1, $count);  // 只有新创建的实体 appId 为 null

        // Test deviceModel null count
        $count = $this->repository->count(['deviceModel' => null]);
        $this->assertSame(1, $count);  // 只有新创建的实体 deviceModel 为 null

        // Test source null count
        $count = $this->repository->count(['source' => null]);
        $this->assertSame(1, $count);  // 只有新创建的实体 source 为 null

        // Test route null count
        $count = $this->repository->count(['route' => null]);
        $this->assertSame(1, $count);  // 只有新创建的实体 route 为 null

        // Test uin null count
        $count = $this->repository->count(['uin' => null]);
        $this->assertSame(1, $count);  // 只有新创建的实体 uin 为 null

        // Test nickname null count
        $count = $this->repository->count(['nickname' => null]);
        $this->assertSame(1, $count);  // 只有新创建的实体 nickname 为 null
    }

    public function testFindOneByWithAccountAssociation(): void
    {
        $entity = $this->createErrorDetail();
        $account = $entity->getAccount();
        $this->assertNotNull($account);
        $this->persistAndFlush($entity);

        $result = $this->repository->findOneBy(['account' => $account]);

        $this->assertNotNull($result);
        $this->assertInstanceOf(ErrorDetail::class, $result);
        $this->assertSame($entity->getId(), $result->getId());
        $resultAccount = $result->getAccount();
        $this->assertNotNull($resultAccount);
        $this->assertSame($account->getId(), $resultAccount->getId());
    }

    public function testFindOneByWithAccountAssociationAndSorting(): void
    {
        $account1 = new Account();
        $account1->setName('Account 1');
        $account1->setAppId('app_id_1_' . uniqid());
        $account1->setAppSecret('secret_1');
        self::getEntityManager()->persist($account1);

        $account2 = new Account();
        $account2->setName('Account 2');
        $account2->setAppId('app_id_2_' . uniqid());
        $account2->setAppSecret('secret_2');
        self::getEntityManager()->persist($account2);

        self::getEntityManager()->flush();

        $entity1 = new ErrorDetail();
        $entity1->setAccount($account1);
        $entity1->setDate(new \DateTimeImmutable());
        $entity1->setOpenId('test_openid_1');
        $entity1->setErrorMsgCode('E001');
        $entity1->setErrorMsg('Test error message 1');
        $entity1->setTimeStamp(new \DateTimeImmutable());

        $entity2 = new ErrorDetail();
        $entity2->setAccount($account1);
        $entity2->setDate(new \DateTimeImmutable());
        $entity2->setOpenId('test_openid_2');
        $entity2->setErrorMsgCode('E002');
        $entity2->setErrorMsg('Test error message 2');
        $entity2->setTimeStamp(new \DateTimeImmutable());

        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);

        $result = $this->repository->findOneBy(['account' => $account1], ['id' => 'DESC']);

        $this->assertNotNull($result);
        $this->assertSame($entity2->getId(), $result->getId());
    }

    public function testFindByWithAllNullableFields(): void
    {
        $entity1 = $this->createErrorDetailWithMultipleNullFields();
        $entity2 = $this->createErrorDetail();
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);

        // Test all specific nullable fields individually
        $nullFields = [
            'openId', 'errorMsgCode', 'errorMsg', 'errorStackCode', 'errorStack',
            'count', 'sdkVersion', 'clientVersion', 'appVersion', 'ds',
            'osName', 'pluginVersion', 'appId', 'deviceModel', 'source',
            'route', 'uin', 'nickname',
        ];

        foreach ($nullFields as $field) {
            $result = $this->repository->findBy([$field => null]);
            $this->assertGreaterThanOrEqual(1, count($result));
        }
    }

    public function testCountWithAllNullableFields(): void
    {
        $entity = $this->createErrorDetailWithMultipleNullFields();
        $this->persistAndFlush($entity);

        // Test all specific nullable fields individually
        $nullFields = [
            'openId', 'errorMsgCode', 'errorMsg', 'errorStackCode', 'errorStack',
            'count', 'sdkVersion', 'clientVersion', 'appVersion', 'ds',
            'osName', 'pluginVersion', 'appId', 'deviceModel', 'source',
            'route', 'uin', 'nickname',
        ];

        foreach ($nullFields as $field) {
            $count = $this->repository->count([$field => null]);
            $this->assertGreaterThanOrEqual(1, $count);
        }
    }

    // PHPStan 要求的特定方法命名

    public function testCountByAssociationAccountShouldReturnCorrectNumber(): void
    {
        $entity = $this->createErrorDetail();
        $account = $entity->getAccount();
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['account' => $account]);

        $this->assertSame(1, $count);
    }

    public function testFindOneByAssociationAccountShouldReturnMatchingEntity(): void
    {
        $entity = $this->createErrorDetail();
        $account = $entity->getAccount();
        $this->assertNotNull($account);
        $this->persistAndFlush($entity);

        $result = $this->repository->findOneBy(['account' => $account]);

        $this->assertNotNull($result);
        $this->assertInstanceOf(ErrorDetail::class, $result);
        $this->assertSame($entity->getId(), $result->getId());
        $resultAccount = $result->getAccount();
        $this->assertNotNull($resultAccount);
        $this->assertSame($account->getId(), $resultAccount->getId());
    }

    private function createErrorDetailWithNullDate(): ErrorDetail
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test_app_id_' . uniqid());
        $account->setAppSecret('test_app_secret');

        self::getEntityManager()->persist($account);
        self::getEntityManager()->flush();

        $entity = new ErrorDetail();
        $entity->setAccount($account);
        // 不设置 date，保持为 null
        $entity->setOpenId('test_openid_' . uniqid());
        $entity->setErrorMsgCode('E' . rand(100, 999));
        $entity->setErrorMsg('Test error message');
        $entity->setTimeStamp(new \DateTimeImmutable());

        return $entity;
    }

    private function createErrorDetailWithNullTimestamp(): ErrorDetail
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test_app_id_' . uniqid());
        $account->setAppSecret('test_app_secret');

        self::getEntityManager()->persist($account);
        self::getEntityManager()->flush();

        $entity = new ErrorDetail();
        $entity->setAccount($account);
        $entity->setDate(new \DateTimeImmutable());
        $entity->setOpenId('test_openid_' . uniqid());
        $entity->setErrorMsgCode('E' . rand(100, 999));
        $entity->setErrorMsg('Test error message');
        // 不设置 timeStamp，保持为 null

        return $entity;
    }

    private function createErrorDetailWithMultipleNullFields(): ErrorDetail
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test_app_id_' . uniqid());
        $account->setAppSecret('test_app_secret');

        self::getEntityManager()->persist($account);
        self::getEntityManager()->flush();

        $entity = new ErrorDetail();
        $entity->setAccount($account);
        $entity->setDate(new \DateTimeImmutable());
        // 不设置以下字段，保持为 null
        // openId, errorMsg, errorMsgCode, errorStackCode, errorStack
        // count, sdkVersion, clientVersion, timeStamp, appVersion
        // ds, osName, pluginVersion, appId, deviceModel
        // source, route, uin, nickname
        $entity->setTimeStamp(new \DateTimeImmutable());

        return $entity;
    }

    protected function createNewEntity(): object
    {
        $entity = new ErrorDetail();

        // 创建并设置必填的 Account
        $account = new Account();
        $account->setName('Test Account ' . uniqid());
        $account->setAppId('test_app_id_' . uniqid());
        $account->setAppSecret('test_app_secret');
        self::getEntityManager()->persist($account);

        $entity->setAccount($account);

        return $entity;
    }

    protected function getRepository(): ErrorDetailRepository
    {
        return $this->repository;
    }
}
