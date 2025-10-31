<?php

namespace WechatMiniProgramLogBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Entity\Feedback;
use WechatMiniProgramLogBundle\Enum\FeedbackType;
use WechatMiniProgramLogBundle\Repository\FeedbackRepository;

/**
 * @internal
 */
#[CoversClass(FeedbackRepository::class)]
#[RunTestsInSeparateProcesses]
final class FeedbackRepositoryTest extends AbstractRepositoryTestCase
{
    private FeedbackRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(FeedbackRepository::class);
    }

    public function testFindByWithEmptyCriteriaShouldReturnAllEntities(): void
    {
        $entity = $this->createFeedback();
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy([]);

        $this->assertCount(2, $result);  // 已经有一条初始数据了
    }

    public function testFindOneByWithOrderByShouldRespectOrdering(): void
    {
        $entity1 = $this->createFeedback();
        $entity1->setContent('Content 1');
        $entity2 = $this->createFeedback();
        $entity2->setContent('Content 2');
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);

        $result = $this->repository->findOneBy(['content' => 'Content 1'], ['id' => 'DESC']);

        $this->assertNotNull($result);
        $this->assertSame($entity1->getId(), $result->getId());
    }

    public function testFindByWithNullValueShouldFindRecordsWithNullField(): void
    {
        $entity = $this->createFeedback();
        $entity->setHeadUrl(null);
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy(['headUrl' => null]);

        $this->assertCount(1, $result);
        $this->assertInstanceOf(Feedback::class, $result[0]);
        $this->assertNull($result[0]->getHeadUrl());
    }

    public function testFindByWithAccountRelationShouldWork(): void
    {
        $entity = $this->createFeedback();
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy(['account' => $entity->getAccount()]);

        $this->assertCount(1, $result);
        $this->assertInstanceOf(Feedback::class, $result[0]);
        $entityAccount = $entity->getAccount();
        $resultAccount = $result[0]->getAccount();
        $this->assertNotNull($entityAccount);
        $this->assertNotNull($resultAccount);
        $this->assertSame($entityAccount->getId(), $resultAccount->getId());
    }

    public function testFindByWithMultipleFieldsShouldWork(): void
    {
        $entity = $this->createFeedback();
        $entity->setFeedbackType(FeedbackType::TYPE_3);
        $entity->setPhone('13912345678');
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy([
            'feedbackType' => FeedbackType::TYPE_3,
            'phone' => '13912345678',
        ]);

        $this->assertCount(1, $result);
        $this->assertSame($entity->getId(), $result[0]->getId());
    }

    public function testCountWithAccountRelationShouldWork(): void
    {
        $entity = $this->createFeedback();
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['account' => $entity->getAccount()]);

        $this->assertSame(1, $count);
    }

    public function testCountWithNullValueShouldFindRecordsWithNullField(): void
    {
        $entity = $this->createFeedback();
        $entity->setHeadUrl(null);
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['headUrl' => null]);

        $this->assertSame(1, $count);
    }

    public function testSaveShouldPersistEntity(): void
    {
        $entity = $this->createFeedback();

        $this->repository->save($entity);

        $this->assertEntityPersisted($entity);
        $this->assertNotNull($entity->getId());
    }

    public function testSaveWithoutFlushShouldNotPersistImmediately(): void
    {
        $entity = $this->createFeedback();

        $this->repository->save($entity, false);

        $em = self::getEntityManager();
        $em->clear();
        $found = $em->find(Feedback::class, $entity->getId());
        $this->assertNull($found);
    }

    public function testRemoveShouldDeleteEntity(): void
    {
        $entity = $this->createFeedback();
        $this->persistAndFlush($entity);
        $entityId = $entity->getId();

        $this->repository->remove($entity);

        $this->assertEntityNotExists(Feedback::class, $entityId);
        $this->assertNotNull($entityId);
    }

    public function testRemoveWithoutFlushShouldNotDeleteImmediately(): void
    {
        $entity = $this->createFeedback();
        $this->persistAndFlush($entity);

        $this->repository->remove($entity, false);

        $em = self::getEntityManager();
        $em->clear();
        $found = $em->find(Feedback::class, $entity->getId());
        $this->assertNotNull($found);
    }

    public function testFindByWxRecordIdShouldWork(): void
    {
        $entity = $this->createFeedback();
        $entity->setWxRecordId('wx_record_123');
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy(['wxRecordId' => 'wx_record_123']);

        $this->assertCount(1, $result);
        $this->assertSame($entity->getId(), $result[0]->getId());
    }

    public function testFindByPhoneShouldWork(): void
    {
        $entity = $this->createFeedback();
        $entity->setPhone('13812345678');
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy(['phone' => '13812345678']);

        $this->assertCount(1, $result);
        $this->assertSame($entity->getId(), $result[0]->getId());
    }

    public function testFindOneByAssociationAccountShouldReturnMatchingEntity(): void
    {
        $entity = $this->createFeedback();
        $this->persistAndFlush($entity);

        $result = $this->repository->findOneBy(['account' => $entity->getAccount()]);

        $this->assertNotNull($result);
        $this->assertInstanceOf(Feedback::class, $result);
        $entityAccount = $entity->getAccount();
        $resultAccount = $result->getAccount();
        $this->assertNotNull($entityAccount);
        $this->assertNotNull($resultAccount);
        $this->assertSame($entityAccount->getId(), $resultAccount->getId());
    }

    public function testCountByAssociationAccountShouldReturnCorrectNumber(): void
    {
        $entity = $this->createFeedback();
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['account' => $entity->getAccount()]);

        $this->assertGreaterThanOrEqual(1, $count);
    }

    private function createFeedback(): Feedback
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test_app_id_' . uniqid());
        $account->setAppSecret('test_app_secret');

        self::getEntityManager()->persist($account);
        self::getEntityManager()->flush();

        $entity = new Feedback();
        $entity->setAccount($account);
        $entity->setWxRecordId('wx_record_' . uniqid());
        $entity->setWxCreateTime(new \DateTimeImmutable());
        $entity->setContent('Test feedback content');
        $entity->setPhone('13800138000');
        $entity->setOpenid('test_openid_' . uniqid());
        $entity->setNickname('Test User');
        $entity->setHeadUrl('https://example.com/avatar.jpg');
        $entity->setFeedbackType(FeedbackType::TYPE_1);
        $entity->setMediaIds(['media1', 'media2']);
        $entity->setSystemInfo('{"system": "iOS 15.0"}');
        $entity->setRawData('{"raw": "data"}');

        return $entity;
    }

    protected function createNewEntity(): object
    {
        $entity = new Feedback();

        // 创建并设置必填的 Account
        $account = new Account();
        $account->setName('Test Account ' . uniqid());
        $account->setAppId('test_app_id_' . uniqid());
        $account->setAppSecret('test_app_secret');
        self::getEntityManager()->persist($account);

        $entity->setAccount($account);

        // 设置其他必填字段
        $entity->setWxRecordId('wx_record_' . uniqid());
        $entity->setFeedbackType(FeedbackType::TYPE_1);
        $entity->setMediaIds([]);  // 初始化为空数组

        return $entity;
    }

    /**
     * @return FeedbackRepository
     */
    protected function getRepository(): FeedbackRepository
    {
        return $this->repository;
    }
}
