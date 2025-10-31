<?php

namespace WechatMiniProgramLogBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Entity\ErrorListData;
use WechatMiniProgramLogBundle\Repository\ErrorListDataRepository;

/**
 * @internal
 */
#[CoversClass(ErrorListDataRepository::class)]
#[RunTestsInSeparateProcesses]
final class ErrorListDataRepositoryTest extends AbstractRepositoryTestCase
{
    private ErrorListDataRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(ErrorListDataRepository::class);
    }

    public function testFindByWithEmptyCriteriaShouldReturnAllEntities(): void
    {
        $entity = $this->createErrorListData();
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy([]);

        $this->assertCount(2, $result);  // 已经有一条初始数据了
    }

    public function testFindOneByWithOrderByShouldRespectOrdering(): void
    {
        $entity1 = $this->createErrorListData();
        $entity1->setErrorMsgCode('API_ERROR_001');
        $entity2 = $this->createErrorListData();
        $entity2->setErrorMsgCode('API_ERROR_002');
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);

        // Test single ordering
        $result = $this->repository->findOneBy(['errorMsgCode' => 'API_ERROR_001'], ['id' => 'DESC']);
        $this->assertNotNull($result);
        $this->assertSame($entity1->getId(), $result->getId());

        // Test multiple ordering fields
        $result = $this->repository->findOneBy([], ['id' => 'DESC', 'errorMsgCode' => 'ASC']);
        $this->assertNotNull($result);
        // Should return an entity (the exact one depends on the data and ordering)
        $this->assertTrue($result->getId() === $entity1->getId() || $result->getId() === $entity2->getId());
    }

    public function testFindByWithNullValueShouldFindRecordsWithNullField(): void
    {
        $entity = $this->createErrorListData();
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy(['errorStackCode' => null]);

        $this->assertCount(1, $result);  // 只有新创建的实体 errorStackCode 为 null
        foreach ($result as $item) {
            $this->assertInstanceOf(ErrorListData::class, $item);
            $this->assertNull($item->getErrorStackCode());
        }
    }

    public function testFindByWithAccountRelationShouldWork(): void
    {
        $entity = $this->createErrorListData();
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy(['account' => $entity->getAccount()]);

        $this->assertCount(1, $result);
        $this->assertInstanceOf(ErrorListData::class, $result[0]);
        $entityAccount = $entity->getAccount();
        $resultAccount = $result[0]->getAccount();
        $this->assertNotNull($entityAccount);
        $this->assertNotNull($resultAccount);
        $this->assertSame($entityAccount->getId(), $resultAccount->getId());
    }

    public function testFindByWithMultipleFieldsShouldWork(): void
    {
        $entity = $this->createErrorListData();
        $entity->setErrorMsgCode('MULTI_TEST');
        $entity->setOpenId('multi_openid');
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy([
            'errorMsgCode' => 'MULTI_TEST',
            'openId' => 'multi_openid',
        ]);

        $this->assertCount(1, $result);
        $this->assertSame($entity->getId(), $result[0]->getId());
    }

    public function testCountWithAccountRelationShouldWork(): void
    {
        $entity = $this->createErrorListData();
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['account' => $entity->getAccount()]);

        $this->assertSame(1, $count);
    }

    public function testCountWithNullValueShouldFindRecordsWithNullField(): void
    {
        $entity = $this->createErrorListData();
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['errorStackCode' => null]);

        $this->assertSame(1, $count);  // 只有新创建的实体 errorStackCode 为 null
    }

    public function testSaveShouldPersistEntity(): void
    {
        $entity = $this->createErrorListData();

        $this->repository->save($entity);

        $this->assertEntityPersisted($entity);
        $this->assertNotNull($entity->getId());
    }

    public function testSaveWithoutFlushShouldNotPersistImmediately(): void
    {
        $entity = $this->createErrorListData();

        $this->repository->save($entity, false);

        $em = self::getEntityManager();
        $em->clear();
        $found = $em->find(ErrorListData::class, $entity->getId());
        $this->assertNull($found);
    }

    public function testRemoveShouldDeleteEntity(): void
    {
        $entity = $this->createErrorListData();
        $this->persistAndFlush($entity);
        $entityId = $entity->getId();

        $this->repository->remove($entity);

        $this->assertEntityNotExists(ErrorListData::class, $entityId);
        $this->assertNotNull($entityId);
    }

    public function testRemoveWithoutFlushShouldNotDeleteImmediately(): void
    {
        $entity = $this->createErrorListData();
        $this->persistAndFlush($entity);

        $this->repository->remove($entity, false);

        $em = self::getEntityManager();
        $em->clear();
        $found = $em->find(ErrorListData::class, $entity->getId());
        $this->assertNotNull($found);
    }

    private function createErrorListData(): ErrorListData
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test_app_id_' . uniqid());
        $account->setAppSecret('test_app_secret');

        self::getEntityManager()->persist($account);
        self::getEntityManager()->flush();

        $entity = new ErrorListData();
        $entity->setAccount($account);
        $entity->setDate(new \DateTimeImmutable());
        $entity->setOpenId('test_openid_' . uniqid());
        $entity->setErrorMsgCode('E' . rand(100, 999));
        $entity->setErrorMsg('Test error message');
        $entity->setUv(rand(1, 100));
        $entity->setPv(rand(100, 1000));
        $entity->setPvPercent('10.5%');
        $entity->setUvPercent('5.2%');

        return $entity;
    }

    public function testFindOneByAssociationAccountShouldReturnMatchingEntity(): void
    {
        $entity = $this->createErrorListData();
        $account = $entity->getAccount();
        $this->assertNotNull($account);
        $this->persistAndFlush($entity);

        $result = $this->repository->findOneBy(['account' => $account]);

        $this->assertNotNull($result);
        $this->assertInstanceOf(ErrorListData::class, $result);
        $this->assertSame($entity->getId(), $result->getId());
        $resultAccount = $result->getAccount();
        $this->assertNotNull($resultAccount);
        $this->assertSame($account->getId(), $resultAccount->getId());
    }

    public function testCountByAssociationAccountShouldReturnCorrectNumber(): void
    {
        $entity1 = $this->createErrorListData();
        $entity2 = $this->createErrorListData();
        $account = $entity1->getAccount();
        $entity2->setAccount($account); // Same account
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);

        $count = $this->repository->count(['account' => $account]);

        $this->assertSame(2, $count);
    }

    protected function createNewEntity(): object
    {
        $entity = new ErrorListData();

        // 创建并设置必填的 Account
        $account = new Account();
        $account->setName('Test Account ' . uniqid());
        $account->setAppId('test_app_id_' . uniqid());
        $account->setAppSecret('test_app_secret');
        self::getEntityManager()->persist($account);

        $entity->setAccount($account);

        return $entity;
    }

    /**
     * @return ErrorListDataRepository
     */
    protected function getRepository(): ErrorListDataRepository
    {
        return $this->repository;
    }
}
