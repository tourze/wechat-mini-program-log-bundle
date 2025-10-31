<?php

namespace WechatMiniProgramLogBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatMiniProgramLogBundle\Enum\FeedbackType;

/**
 * @internal
 */
#[CoversClass(FeedbackType::class)]
final class FeedbackTypeTest extends AbstractEnumTestCase
{
    public function testCases(): void
    {
        $cases = FeedbackType::cases();
        self::assertCount(8, $cases);

        $expectedCases = [
            FeedbackType::TYPE_1,
            FeedbackType::TYPE_2,
            FeedbackType::TYPE_3,
            FeedbackType::TYPE_4,
            FeedbackType::TYPE_5,
            FeedbackType::TYPE_6,
            FeedbackType::TYPE_7,
            FeedbackType::TYPE_8,
        ];

        foreach ($expectedCases as $case) {
            self::assertContains($case, $cases);
        }
    }

    #[TestWith(['1', FeedbackType::TYPE_1, '无法打开小程序'])]
    #[TestWith(['2', FeedbackType::TYPE_2, '小程序闪退'])]
    #[TestWith(['3', FeedbackType::TYPE_3, '卡顿'])]
    #[TestWith(['4', FeedbackType::TYPE_4, '黑屏白屏'])]
    #[TestWith(['5', FeedbackType::TYPE_5, '死机'])]
    #[TestWith(['6', FeedbackType::TYPE_6, '界面错位'])]
    #[TestWith(['7', FeedbackType::TYPE_7, '界面加载慢'])]
    #[TestWith(['8', FeedbackType::TYPE_8, '其他异常'])]
    public function testValueAndLabel(string $value, FeedbackType $expectedCase, string $expectedLabel): void
    {
        self::assertSame($expectedCase, FeedbackType::from($value));
        self::assertSame($expectedLabel, $expectedCase->getLabel());
    }

    public function testTryFromValue(): void
    {
        self::assertSame(FeedbackType::TYPE_1, FeedbackType::tryFrom('1'));
        self::assertNull(FeedbackType::tryFrom('9'));
        self::assertNull(FeedbackType::tryFrom('invalid'));
        self::assertNull(FeedbackType::tryFrom(''));
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (FeedbackType $case) => $case->value, FeedbackType::cases());
        $uniqueValues = array_unique($values);
        self::assertCount(count($values), $uniqueValues, 'All values should be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (FeedbackType $case) => $case->getLabel(), FeedbackType::cases());
        $uniqueLabels = array_unique($labels);
        self::assertCount(count($labels), $uniqueLabels, 'All labels should be unique');
    }

    public function testToArray(): void
    {
        $array = FeedbackType::TYPE_1->toArray();
        self::assertArrayHasKey('label', $array);
        self::assertArrayHasKey('value', $array);
        self::assertSame('无法打开小程序', $array['label']);
        self::assertSame('1', $array['value']);
    }

    public function testGenOptions(): void
    {
        $options = FeedbackType::genOptions();
        self::assertCount(8, $options);

        foreach ($options as $option) {
            self::assertArrayHasKey('label', $option);
            self::assertArrayHasKey('value', $option);
            self::assertArrayHasKey('text', $option);
            self::assertArrayHasKey('name', $option);
            self::assertIsString($option['label']);
            self::assertIsString($option['value']);
        }
    }
}
