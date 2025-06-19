<?php

namespace WechatMiniProgramLogBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Entity\ErrorListData;

class ErrorListDataTest extends TestCase
{
    private ErrorListData $errorListData;

    protected function setUp(): void
    {
        $this->errorListData = new ErrorListData();
    }

    public function testGettersAndSetters_BasicProperties(): void
    {
        // ID属性在Entity中默认为0，不是null
        $this->assertSame(0, $this->errorListData->getId());
        
        // 测试OpenId属性
        $openId = 'test_open_id';
        $this->errorListData->setOpenId($openId);
        $this->assertSame($openId, $this->errorListData->getOpenId());
        
        // 测试ErrorMsgCode属性
        $errorMsgCode = 'error_msg_code_123';
        $this->errorListData->setErrorMsgCode($errorMsgCode);
        $this->assertSame($errorMsgCode, $this->errorListData->getErrorMsgCode());
        
        // 测试ErrorMsg属性
        $errorMsg = 'Test error message';
        $this->errorListData->setErrorMsg($errorMsg);
        $this->assertSame($errorMsg, $this->errorListData->getErrorMsg());
        
        // 测试Uv属性
        $uv = 100;
        $this->errorListData->setUv($uv);
        $this->assertSame(100, $this->errorListData->getUv());
        
        // 测试Pv属性
        $pv = 200;
        $this->errorListData->setPv($pv);
        $this->assertSame(200, $this->errorListData->getPv());
        
        // 测试ErrorStackCode属性
        $errorStackCode = 'error_stack_code_123';
        $this->errorListData->setErrorStackCode($errorStackCode);
        $this->assertSame($errorStackCode, $this->errorListData->getErrorStackCode());
        
        // 测试ErrorStack属性
        $errorStack = 'Test error stack';
        $this->errorListData->setErrorStack($errorStack);
        $this->assertSame($errorStack, $this->errorListData->getErrorStack());
        
        // 测试PvPercent属性
        $pvPercent = '50%';
        $this->errorListData->setPvPercent($pvPercent);
        $this->assertSame($pvPercent, $this->errorListData->getPvPercent());
        
        // 测试UvPercent属性
        $uvPercent = '25%';
        $this->errorListData->setUvPercent($uvPercent);
        $this->assertSame($uvPercent, $this->errorListData->getUvPercent());
    }

    public function testGettersAndSetters_DateTimeProperties(): void
    {
        // 测试Date属性
        $date = new DateTimeImmutable('2023-01-01');
        $this->errorListData->setDate($date);
        $this->assertSame($date, $this->errorListData->getDate());
        
        // 测试CreateTime属性
        $createTime = new DateTimeImmutable('2023-01-01 10:00:00');
        $this->errorListData->setCreateTime($createTime);
        $this->assertSame($createTime, $this->errorListData->getCreateTime());
        
        // 测试UpdateTime属性
        $updateTime = new DateTimeImmutable('2023-01-01 11:00:00');
        $this->errorListData->setUpdateTime($updateTime);
        $this->assertSame($updateTime, $this->errorListData->getUpdateTime());
    }

    public function testGettersAndSetters_RelationProperties(): void
    {
        // 测试Account关联属性
        $account = $this->createMock(Account::class);
        $this->errorListData->setAccount($account);
        $this->assertSame($account, $this->errorListData->getAccount());
    }

    public function testCreateNewInstance_WithDefaultValues(): void
    {
        $errorListData = new ErrorListData();
        
        // 验证默认值
        $this->assertSame(0, $errorListData->getId()); // ID默认为0，不是null
        $this->assertNull($errorListData->getOpenId());
        $this->assertNull($errorListData->getErrorMsgCode());
        $this->assertNull($errorListData->getErrorMsg());
        $this->assertNull($errorListData->getUv());
        $this->assertNull($errorListData->getPv());
        $this->assertNull($errorListData->getErrorStackCode());
        $this->assertNull($errorListData->getErrorStack());
        $this->assertNull($errorListData->getPvPercent());
        $this->assertNull($errorListData->getUvPercent());
        $this->assertNull($errorListData->getDate());
        $this->assertNull($errorListData->getAccount());
        $this->assertNull($errorListData->getCreateTime());
        $this->assertNull($errorListData->getUpdateTime());
    }

    public function testSetInvalidValues_ShouldThrowTypeError(): void
    {
        $this->expectException(\TypeError::class);
        /** @phpstan-ignore-next-line */
        $this->errorListData->setDate('not-a-date');
    }
} 