<?php

namespace WechatMiniProgramLogBundle\Tests\Entity;

use DateTime;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Entity\ErrorDetail;

class ErrorDetailTest extends TestCase
{
    private ErrorDetail $errorDetail;

    protected function setUp(): void
    {
        $this->errorDetail = new ErrorDetail();
    }

    public function testGettersAndSetters_BasicProperties(): void
    {
        // ID属性在Entity中默认为0，不是null
        $this->assertSame(0, $this->errorDetail->getId());
        
        // 测试OpenId属性
        $openId = 'test_open_id';
        $this->errorDetail->setOpenId($openId);
        $this->assertSame($openId, $this->errorDetail->getOpenId());
        
        // 测试ErrorMsgCode属性
        $errorMsgCode = 'error_msg_code_123';
        $this->errorDetail->setErrorMsgCode($errorMsgCode);
        $this->assertSame($errorMsgCode, $this->errorDetail->getErrorMsgCode());
        
        // 测试ErrorMsg属性
        $errorMsg = 'Test error message';
        $this->errorDetail->setErrorMsg($errorMsg);
        $this->assertSame($errorMsg, $this->errorDetail->getErrorMsg());
        
        // 测试ErrorStackCode属性
        $errorStackCode = 'error_stack_code_123';
        $this->errorDetail->setErrorStackCode($errorStackCode);
        $this->assertSame($errorStackCode, $this->errorDetail->getErrorStackCode());
        
        // 测试ErrorStack属性
        $errorStack = 'Test error stack';
        $this->errorDetail->setErrorStack($errorStack);
        $this->assertSame($errorStack, $this->errorDetail->getErrorStack());
        
        // 测试Count属性
        $count = '5';
        $this->errorDetail->setCount($count);
        $this->assertSame($count, $this->errorDetail->getCount());
        
        // 测试SdkVersion属性
        $sdkVersion = '1.0.0';
        $this->errorDetail->setSdkVersion($sdkVersion);
        $this->assertSame($sdkVersion, $this->errorDetail->getSdkVersion());
        
        // 测试ClientVersion属性
        $clientVersion = '2.0.0';
        $this->errorDetail->setClientVersion($clientVersion);
        $this->assertSame($clientVersion, $this->errorDetail->getClientVersion());
        
        // 测试AppVersion属性
        $appVersion = '3.0.0';
        $this->errorDetail->setAppVersion($appVersion);
        $this->assertSame($appVersion, $this->errorDetail->getAppVersion());
        
        // 测试Ds属性
        $ds = 'test_ds';
        $this->errorDetail->setDs($ds);
        $this->assertSame($ds, $this->errorDetail->getDs());
        
        // 测试OsName属性
        $osName = 'iOS';
        $this->errorDetail->setOsName($osName);
        $this->assertSame($osName, $this->errorDetail->getOsName());
        
        // 测试PluginVersion属性
        $pluginVersion = '1.2.3';
        $this->errorDetail->setPluginVersion($pluginVersion);
        $this->assertSame($pluginVersion, $this->errorDetail->getPluginVersion());
        
        // 测试AppId属性
        $appId = 'wx123456789';
        $this->errorDetail->setAppId($appId);
        $this->assertSame($appId, $this->errorDetail->getAppId());
        
        // 测试DeviceModel属性
        $deviceModel = 'iPhone X';
        $this->errorDetail->setDeviceModel($deviceModel);
        $this->assertSame($deviceModel, $this->errorDetail->getDeviceModel());
        
        // 测试Source属性
        $source = 'test_source';
        $this->errorDetail->setSource($source);
        $this->assertSame($source, $this->errorDetail->getSource());
        
        // 测试Route属性
        $route = 'test_route';
        $this->errorDetail->setRoute($route);
        $this->assertSame($route, $this->errorDetail->getRoute());
        
        // 测试Uin属性
        $uin = 'test_uin';
        $this->errorDetail->setUin($uin);
        $this->assertSame($uin, $this->errorDetail->getUin());
        
        // 测试Nickname属性
        $nickname = 'test_nickname';
        $this->errorDetail->setNickname($nickname);
        $this->assertSame($nickname, $this->errorDetail->getNickname());
    }

    public function testGettersAndSetters_DateTimeProperties(): void
    {
        // 测试Date属性
        $date = new DateTime('2023-01-01');
        $this->errorDetail->setDate($date);
        $this->assertSame($date, $this->errorDetail->getDate());
        
        // 测试TimeStamp属性
        $timeStamp = new DateTime('2023-01-01 12:00:00');
        $this->errorDetail->setTimeStamp($timeStamp);
        $this->assertSame($timeStamp, $this->errorDetail->getTimeStamp());
        
        // 测试CreateTime属性
        $createTime = new DateTime('2023-01-01 10:00:00');
        $this->errorDetail->setCreateTime($createTime);
        $this->assertSame($createTime, $this->errorDetail->getCreateTime());
        
        // 测试UpdateTime属性
        $updateTime = new DateTime('2023-01-01 11:00:00');
        $this->errorDetail->setUpdateTime($updateTime);
        $this->assertSame($updateTime, $this->errorDetail->getUpdateTime());
    }

    public function testGettersAndSetters_RelationProperties(): void
    {
        // 测试Account关联属性
        $account = $this->createMock(Account::class);
        $this->errorDetail->setAccount($account);
        $this->assertSame($account, $this->errorDetail->getAccount());
    }

    public function testCreateNewInstance_WithDefaultValues(): void
    {
        $errorDetail = new ErrorDetail();
        
        // 验证默认值
        $this->assertSame(0, $errorDetail->getId()); // ID默认为0，不是null
        $this->assertNull($errorDetail->getOpenId());
        $this->assertNull($errorDetail->getErrorMsgCode());
        $this->assertNull($errorDetail->getErrorMsg());
        $this->assertNull($errorDetail->getErrorStackCode());
        $this->assertNull($errorDetail->getErrorStack());
        $this->assertNull($errorDetail->getCount());
        $this->assertNull($errorDetail->getSdkVersion());
        $this->assertNull($errorDetail->getClientVersion());
        $this->assertNull($errorDetail->getTimeStamp());
        $this->assertNull($errorDetail->getAppVersion());
        $this->assertNull($errorDetail->getDs());
        $this->assertNull($errorDetail->getOsName());
        $this->assertNull($errorDetail->getPluginVersion());
        $this->assertNull($errorDetail->getAppId());
        $this->assertNull($errorDetail->getDeviceModel());
        $this->assertNull($errorDetail->getSource());
        $this->assertNull($errorDetail->getRoute());
        $this->assertNull($errorDetail->getUin());
        $this->assertNull($errorDetail->getNickname());
        $this->assertNull($errorDetail->getDate());
        $this->assertNull($errorDetail->getAccount());
        $this->assertNull($errorDetail->getCreateTime());
        $this->assertNull($errorDetail->getUpdateTime());
    }

    public function testSetInvalidValues_ShouldThrowTypeError(): void
    {
        $this->expectException(\TypeError::class);
        $this->errorDetail->setDate('not-a-date');
    }
} 