<?php

namespace WechatMiniProgramLogBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Entity\ErrorDetail;

/**
 * @internal
 */
#[CoversClass(ErrorDetail::class)]
final class ErrorDetailTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new ErrorDetail();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'errorMsgCode' => ['errorMsgCode', 'test_error_code'],
            'errorMsg' => ['errorMsg', 'Test error message'],
            'errorStackCode' => ['errorStackCode', 'stack_code_123'],
            'errorStack' => ['errorStack', 'Error stack trace'],
            'count' => ['count', '10'],
            'sdkVersion' => ['sdkVersion', '2.0.0'],
            'clientVersion' => ['clientVersion', '1.5.0'],
            'timeStamp' => ['timeStamp', new \DateTimeImmutable()],
            'appVersion' => ['appVersion', '3.1.0'],
            'ds' => ['ds', 'test_ds'],
            'osName' => ['osName', 'iOS'],
            'pluginVersion' => ['pluginVersion', '1.0.0'],
            'appId' => ['appId', 'wx123456'],
            'deviceModel' => ['deviceModel', 'iPhone 13'],
            'source' => ['source', 'app_store'],
            'route' => ['route', '/api/test'],
            'uin' => ['uin', '123456'],
            'nickname' => ['nickname', 'Test User'],
            'openId' => ['openId', 'openid123'],
        ];
    }

    public function testCreateNewInstanceWithDefaultValues(): void
    {
        $errorDetail = new ErrorDetail();

        // 验证默认值
        $this->assertSame(0, $errorDetail->getId());
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
        $this->assertNull($errorDetail->getOpenId());
        $this->assertNull($errorDetail->getAccount());
    }
}
