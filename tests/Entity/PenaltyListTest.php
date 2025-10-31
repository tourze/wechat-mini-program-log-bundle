<?php

namespace WechatMiniProgramLogBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramLogBundle\Entity\PenaltyList;
use WechatMiniProgramLogBundle\Enum\PenaltyStatus;

/**
 * @internal
 */
#[CoversClass(PenaltyList::class)]
final class PenaltyListTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new PenaltyList();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'id' => ['id', '1'],
            'createTime' => ['createTime', new \DateTimeImmutable()],
            'updateTime' => ['updateTime', new \DateTimeImmutable()],
        ];
    }

    private PenaltyList $penaltyList;

    protected function setUp(): void
    {
        parent::setUp();

        $this->penaltyList = new PenaltyList();
    }

    public function testGettersAndSettersBasicStringProperties(): void
    {
        // 测试违规记录ID属性
        $illegalOrderId = 'illegal_order_123';
        $this->penaltyList->setIllegalOrderId($illegalOrderId);
        $this->assertSame($illegalOrderId, $this->penaltyList->getIllegalOrderId());

        // 测试投诉单ID属性
        $complaintOrderId = 'complaint_123';
        $this->penaltyList->setComplaintOrderId($complaintOrderId);
        $this->assertSame($complaintOrderId, $this->penaltyList->getComplaintOrderId());

        // 测试违规行为属性
        $illegalWording = '商品信息不符';
        $this->penaltyList->setIllegalWording($illegalWording);
        $this->assertSame($illegalWording, $this->penaltyList->getIllegalWording());

        // 测试订单号属性（可为null）
        $orderId = 'order_123';
        $this->penaltyList->setOrderId($orderId);
        $this->assertSame($orderId, $this->penaltyList->getOrderId());

        $this->penaltyList->setOrderId(null);
        $this->assertNull($this->penaltyList->getOrderId());

        // 测试原始数据属性
        $rawData = '{"key": "value"}';
        $this->penaltyList->setRawData($rawData);
        $this->assertSame($rawData, $this->penaltyList->getRawData());
    }

    public function testGettersAndSettersNumericProperties(): void
    {
        // 测试扣除分数属性
        $minusScore = 5;
        $this->penaltyList->setMinusScore($minusScore);
        $this->assertSame($minusScore, $this->penaltyList->getMinusScore());

        // 测试当前分数属性（可为null）
        $currentScore = 85;
        $this->penaltyList->setCurrentScore($currentScore);
        $this->assertSame($currentScore, $this->penaltyList->getCurrentScore());

        $this->penaltyList->setCurrentScore(null);
        $this->assertNull($this->penaltyList->getCurrentScore());
    }

    public function testGettersAndSettersDateTimeProperties(): void
    {
        // 测试违规时间属性
        $illegalTime = new \DateTimeImmutable('2023-01-01 12:00:00');
        $this->penaltyList->setIllegalTime($illegalTime);
        $this->assertSame($illegalTime, $this->penaltyList->getIllegalTime());

        // 测试创建时间属性
        $createTime = new \DateTimeImmutable('2023-01-01 13:00:00');
        $this->penaltyList->setCreateTime($createTime);
        $this->assertSame($createTime, $this->penaltyList->getCreateTime());

        // 测试更新时间属性
        $updateTime = new \DateTimeImmutable('2023-01-01 14:00:00');
        $this->penaltyList->setUpdateTime($updateTime);
        $this->assertSame($updateTime, $this->penaltyList->getUpdateTime());
    }

    public function testGettersAndSettersEnumProperties(): void
    {
        // 测试状态枚举属性
        $penaltyStatus = PenaltyStatus::TYPE_2;
        $this->penaltyList->setPenaltyStatus($penaltyStatus);
        $this->assertSame($penaltyStatus, $this->penaltyList->getPenaltyStatus());

        // 测试枚举值应正确反映其定义的值
        $status = $this->penaltyList->getPenaltyStatus();
        $this->assertNotNull($status);
        $this->assertSame('2', $status->value);
        $this->assertSame('扣分审批通过', $status->getLabel());
    }

    public function testCreateNewInstanceWithDefaultValues(): void
    {
        $penaltyList = new PenaltyList();

        // 验证默认值
        $this->assertNull($penaltyList->getId());
        $this->assertNull($penaltyList->getIllegalOrderId());
        $this->assertNull($penaltyList->getComplaintOrderId());
        $this->assertNull($penaltyList->getIllegalTime());
        $this->assertNull($penaltyList->getIllegalWording());
        $this->assertNull($penaltyList->getPenaltyStatus());
        $this->assertNull($penaltyList->getMinusScore());
        $this->assertNull($penaltyList->getOrderId());
        $this->assertNull($penaltyList->getCurrentScore());
        $this->assertNull($penaltyList->getRawData());
        $this->assertNull($penaltyList->getCreateTime());
        $this->assertNull($penaltyList->getUpdateTime());
    }

    public function testSetInvalidValuesShouldThrowTypeError(): void
    {
        $this->expectException(\TypeError::class);
        /* @phpstan-ignore-next-line */
        $this->penaltyList->setIllegalTime('not-a-date');
    }
}
