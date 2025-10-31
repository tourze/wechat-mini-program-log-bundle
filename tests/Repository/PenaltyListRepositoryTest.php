<?php

namespace WechatMiniProgramLogBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramLogBundle\Entity\PenaltyList;
use WechatMiniProgramLogBundle\Enum\PenaltyStatus;
use WechatMiniProgramLogBundle\Repository\PenaltyListRepository;

/**
 * @internal
 */
#[CoversClass(PenaltyListRepository::class)]
#[RunTestsInSeparateProcesses]
final class PenaltyListRepositoryTest extends AbstractRepositoryTestCase
{
    private PenaltyListRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(PenaltyListRepository::class);
    }

    public function testFindByWithEmptyCriteriaShouldReturnAllEntities(): void
    {
        $entity = $this->createPenaltyList();
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy([]);

        $this->assertCount(2, $result);  // 已经有一条初始数据了
    }

    public function testFindOneByWithOrderByShouldRespectOrdering(): void
    {
        $entity1 = $this->createPenaltyList();
        $entity1->setMinusScore(5);
        $entity2 = $this->createPenaltyList();
        $entity2->setMinusScore(10);
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);

        $result = $this->repository->findOneBy(['minusScore' => 5], ['id' => 'DESC']);

        $this->assertNotNull($result);
        $this->assertSame($entity1->getId(), $result->getId());
    }

    public function testFindByWithNullValueShouldFindRecordsWithNullField(): void
    {
        $entity = $this->createPenaltyList();
        $entity->setRawData(null);
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy(['rawData' => null]);

        $this->assertCount(1, $result);
        $this->assertInstanceOf(PenaltyList::class, $result[0]);
        $this->assertNull($result[0]->getRawData());
    }

    public function testFindByWithMultipleFieldsShouldWork(): void
    {
        $entity = $this->createPenaltyList();
        $entity->setPenaltyStatus(PenaltyStatus::TYPE_5);
        $entity->setMinusScore(15);
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy([
            'penaltyStatus' => PenaltyStatus::TYPE_5,
            'minusScore' => 15,
        ]);

        $this->assertCount(1, $result);
        $this->assertSame($entity->getId(), $result[0]->getId());
    }

    public function testFindByWithDateTimeCriteriaShouldWork(): void
    {
        $entity = $this->createPenaltyList();
        $specificDate = new \DateTimeImmutable('2023-01-01 10:00:00');
        $entity->setIllegalTime($specificDate);
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy(['illegalTime' => $specificDate]);

        $this->assertCount(1, $result);
        $this->assertSame($entity->getId(), $result[0]->getId());
    }

    public function testCountWithNullValueShouldFindRecordsWithNullField(): void
    {
        $entity = $this->createPenaltyList();
        $entity->setRawData(null);
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['rawData' => null]);

        $this->assertSame(1, $count);
    }

    public function testSaveShouldPersistEntity(): void
    {
        $entity = $this->createPenaltyList();

        $this->repository->save($entity);

        $this->assertEntityPersisted($entity);
        $this->assertNotNull($entity->getId());
    }

    public function testSaveWithoutFlushShouldNotPersistImmediately(): void
    {
        $entity = $this->createPenaltyList();

        $this->repository->save($entity, false);

        $em = self::getEntityManager();
        $em->clear();
        $found = $em->find(PenaltyList::class, $entity->getId());
        $this->assertNull($found);
    }

    public function testRemoveShouldDeleteEntity(): void
    {
        $entity = $this->createPenaltyList();
        $this->persistAndFlush($entity);
        $entityId = $entity->getId();

        $this->repository->remove($entity);

        $this->assertEntityNotExists(PenaltyList::class, $entityId);
        $this->assertNotNull($entityId);
    }

    public function testRemoveWithoutFlushShouldNotDeleteImmediately(): void
    {
        $entity = $this->createPenaltyList();
        $this->persistAndFlush($entity);

        $this->repository->remove($entity, false);

        $em = self::getEntityManager();
        $em->clear();
        $found = $em->find(PenaltyList::class, $entity->getId());
        $this->assertNotNull($found);
    }

    public function testFindByIllegalOrderIdShouldWork(): void
    {
        $entity = $this->createPenaltyList();
        $entity->setIllegalOrderId('illegal_order_123');
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy(['illegalOrderId' => 'illegal_order_123']);

        $this->assertCount(1, $result);
        $this->assertSame($entity->getId(), $result[0]->getId());
    }

    public function testFindByComplaintOrderIdShouldWork(): void
    {
        $entity = $this->createPenaltyList();
        $entity->setComplaintOrderId('complaint_order_456');
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy(['complaintOrderId' => 'complaint_order_456']);

        $this->assertCount(1, $result);
        $this->assertSame($entity->getId(), $result[0]->getId());
    }

    public function testFindByMinusScoreShouldWork(): void
    {
        $entity = $this->createPenaltyList();
        $entity->setMinusScore(15);  // 使用与DataFixtures不同的值
        $this->persistAndFlush($entity);

        $result = $this->repository->findBy(['minusScore' => 15]);

        $this->assertCount(1, $result);
        $this->assertSame($entity->getId(), $result[0]->getId());
    }

    public function testFindOneByWithNullValueAndOrderByShouldWork(): void
    {
        $entity1 = $this->createPenaltyList();
        $entity1->setOrderId('order_z');
        $entity1->setRawData(null);
        $entity2 = $this->createPenaltyList();
        $entity2->setOrderId('order_a');
        $entity2->setRawData(null);
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);

        $result = $this->repository->findOneBy(['rawData' => null], ['orderId' => 'ASC']);

        $this->assertNotNull($result);
        $this->assertInstanceOf(PenaltyList::class, $result);
        $this->assertSame('order_a', $result->getOrderId());
        $this->assertNull($result->getRawData());
    }

    private function createPenaltyList(): PenaltyList
    {
        $entity = new PenaltyList();
        $entity->setIllegalOrderId('illegal_' . uniqid());
        $entity->setComplaintOrderId('complaint_' . uniqid());
        $entity->setIllegalTime(new \DateTimeImmutable());
        $entity->setIllegalWording('违规行为描述');
        $entity->setPenaltyStatus(PenaltyStatus::TYPE_2);
        $entity->setMinusScore(5);
        $entity->setOrderId('order_' . uniqid());
        $entity->setCurrentScore(95);
        $entity->setRawData('{"penalty": "data"}');

        return $entity;
    }

    protected function createNewEntity(): object
    {
        return new PenaltyList();
        // PenaltyList 只有 ID 是必填的，它会自动生成
    }

    /**
     * @return PenaltyListRepository
     */
    protected function getRepository(): PenaltyListRepository
    {
        return $this->repository;
    }
}
