<?php

namespace WechatMiniProgramLogBundle\Tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramLogBundle\Enum\PenaltyStatus;

class PenaltyStatusTest extends TestCase
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
    
    public function testGetLabel(): void
    {
        self::assertSame('扣分审批通过', PenaltyStatus::TYPE_2->getLabel());
        self::assertSame('申诉中', PenaltyStatus::TYPE_4->getLabel());
        self::assertSame('申述驳回', PenaltyStatus::TYPE_5->getLabel());
        self::assertSame('申诉成功', PenaltyStatus::TYPE_6->getLabel());
    }
    
    public function testFromValue(): void
    {
        self::assertSame(PenaltyStatus::TYPE_2, PenaltyStatus::from('2'));
        self::assertSame(PenaltyStatus::TYPE_4, PenaltyStatus::from('4'));
        self::assertSame(PenaltyStatus::TYPE_5, PenaltyStatus::from('5'));
        self::assertSame(PenaltyStatus::TYPE_6, PenaltyStatus::from('6'));
    }
    
    public function testTryFromValue(): void
    {
        self::assertSame(PenaltyStatus::TYPE_2, PenaltyStatus::tryFrom('2'));
        self::assertNull(PenaltyStatus::tryFrom('1'));
        self::assertNull(PenaltyStatus::tryFrom('3'));
        self::assertNull(PenaltyStatus::tryFrom('invalid'));
    }
    
    public function testToSelectItem(): void
    {
        $item = PenaltyStatus::TYPE_2->toSelectItem();
        self::assertArrayHasKey('label', $item);
        self::assertArrayHasKey('value', $item);
        self::assertArrayHasKey('text', $item);
        self::assertArrayHasKey('name', $item);
        self::assertSame('扣分审批通过', $item['label']);
        self::assertSame('扣分审批通过', $item['text']);
        self::assertSame('扣分审批通过', $item['name']);
        self::assertSame('2', $item['value']);
    }
    
    public function testToArray(): void
    {
        $array = PenaltyStatus::TYPE_2->toArray();
        self::assertArrayHasKey('label', $array);
        self::assertArrayHasKey('value', $array);
        self::assertSame('扣分审批通过', $array['label']);
        self::assertSame('2', $array['value']);
    }
    
    public function testGenOptions(): void
    {
        $options = PenaltyStatus::genOptions();
        self::assertCount(4, $options);
        
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