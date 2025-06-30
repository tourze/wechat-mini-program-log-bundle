<?php

namespace WechatMiniProgramLogBundle\Tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramLogBundle\Enum\FeedbackType;

class FeedbackTypeTest extends TestCase
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
    
    public function testGetLabel(): void
    {
        self::assertSame('无法打开小程序', FeedbackType::TYPE_1->getLabel());
        self::assertSame('小程序闪退', FeedbackType::TYPE_2->getLabel());
        self::assertSame('卡顿', FeedbackType::TYPE_3->getLabel());
        self::assertSame('黑屏白屏', FeedbackType::TYPE_4->getLabel());
        self::assertSame('死机', FeedbackType::TYPE_5->getLabel());
        self::assertSame('界面错位', FeedbackType::TYPE_6->getLabel());
        self::assertSame('界面加载慢', FeedbackType::TYPE_7->getLabel());
        self::assertSame('其他异常', FeedbackType::TYPE_8->getLabel());
    }
    
    public function testFromValue(): void
    {
        self::assertSame(FeedbackType::TYPE_1, FeedbackType::from('1'));
        self::assertSame(FeedbackType::TYPE_2, FeedbackType::from('2'));
        self::assertSame(FeedbackType::TYPE_3, FeedbackType::from('3'));
        self::assertSame(FeedbackType::TYPE_4, FeedbackType::from('4'));
        self::assertSame(FeedbackType::TYPE_5, FeedbackType::from('5'));
        self::assertSame(FeedbackType::TYPE_6, FeedbackType::from('6'));
        self::assertSame(FeedbackType::TYPE_7, FeedbackType::from('7'));
        self::assertSame(FeedbackType::TYPE_8, FeedbackType::from('8'));
    }
    
    public function testTryFromValue(): void
    {
        self::assertSame(FeedbackType::TYPE_1, FeedbackType::tryFrom('1'));
        self::assertNull(FeedbackType::tryFrom('9'));
        self::assertNull(FeedbackType::tryFrom('invalid'));
    }
    
    public function testToSelectItem(): void
    {
        $item = FeedbackType::TYPE_1->toSelectItem();
        self::assertArrayHasKey('label', $item);
        self::assertArrayHasKey('value', $item);
        self::assertArrayHasKey('text', $item);
        self::assertArrayHasKey('name', $item);
        self::assertSame('无法打开小程序', $item['label']);
        self::assertSame('无法打开小程序', $item['text']);
        self::assertSame('无法打开小程序', $item['name']);
        self::assertSame('1', $item['value']);
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