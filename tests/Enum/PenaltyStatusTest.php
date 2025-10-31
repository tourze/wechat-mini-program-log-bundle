<?php

namespace WechatMiniProgramLogBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatMiniProgramLogBundle\Enum\PenaltyStatus;

/**
 * @internal
 */
#[CoversClass(PenaltyStatus::class)]
final class PenaltyStatusTest extends AbstractEnumTestCase
{
    public function testCases(): void
    {
        $cases = PenaltyStatus::cases();
        self::assertCount(4, $cases);

        $expectedCases = [
            PenaltyStatus::TYPE_2,
            PenaltyStatus::TYPE_4,
            PenaltyStatus::TYPE_5,
            PenaltyStatus::TYPE_6,
        ];

        foreach ($expectedCases as $case) {
            self::assertContains($case, $cases);
        }
    }

    #[TestWith(['2', PenaltyStatus::TYPE_2, '扣分审批通过'])]
    #[TestWith(['4', PenaltyStatus::TYPE_4, '申诉中'])]
    #[TestWith(['5', PenaltyStatus::TYPE_5, '申述驳回'])]
    #[TestWith(['6', PenaltyStatus::TYPE_6, '申诉成功'])]
    public function testValueAndLabel(string $value, PenaltyStatus $expectedCase, string $expectedLabel): void
    {
        self::assertSame($expectedCase, PenaltyStatus::from($value));
        self::assertSame($expectedLabel, $expectedCase->getLabel());
    }

    public function testTryFromValue(): void
    {
        self::assertSame(PenaltyStatus::TYPE_2, PenaltyStatus::tryFrom('2'));
        self::assertNull(PenaltyStatus::tryFrom('1'));
        self::assertNull(PenaltyStatus::tryFrom('3'));
        self::assertNull(PenaltyStatus::tryFrom('invalid'));
        self::assertNull(PenaltyStatus::tryFrom(''));
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (PenaltyStatus $case) => $case->value, PenaltyStatus::cases());
        $uniqueValues = array_unique($values);
        self::assertCount(count($values), $uniqueValues, 'All values should be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (PenaltyStatus $case) => $case->getLabel(), PenaltyStatus::cases());
        $uniqueLabels = array_unique($labels);
        self::assertCount(count($labels), $uniqueLabels, 'All labels should be unique');
    }

    public function testToArray(): void
    {
        $array = PenaltyStatus::TYPE_2->toArray();
        self::assertArrayHasKey('label', $array);
        self::assertArrayHasKey('value', $array);
        self::assertSame('扣分审批通过', $array['label']);
        self::assertSame('2', $array['value']);
    }
}
