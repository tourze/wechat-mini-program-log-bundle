<?php

namespace WechatMiniProgramLogBundle\Tests\Request;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use HttpClientBundle\Tests\Request\RequestTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Request\GetErrorDetailRequest;

/**
 * @internal
 */
#[CoversClass(GetErrorDetailRequest::class)]
final class GetErrorDetailRequestTest extends RequestTestCase
{
    private GetErrorDetailRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new GetErrorDetailRequest();
    }

    public function testAccount(): void
    {
        /*
         * 使用具体类 Account 进行 Mock，因为：
         * 1) Account是业务实体，没有对应的接口
         * 2) 在测试中需要模拟实体的属性和方法
         * 3) 这是Entity测试的标准做法
         */
        $account = $this->createMock(Account::class);
        $this->request->setAccount($account);
        $this->assertSame($account, $this->request->getAccount());
    }

    public function testStartTime(): void
    {
        $time = CarbonImmutable::now();
        $this->request->setStartTime($time);
        $this->assertSame($time, $this->request->getStartTime());
    }

    public function testEndTime(): void
    {
        $time = CarbonImmutable::now();
        $this->request->setEndTime($time);
        $this->assertSame($time, $this->request->getEndTime());
    }

    public function testErrorStackCode(): void
    {
        $this->request->setErrorStackCode('stack123');
        $this->assertSame('stack123', $this->request->getErrorStackCode());
    }

    public function testErrorMsgCode(): void
    {
        $this->request->setErrorMsgCode('msg123');
        $this->assertSame('msg123', $this->request->getErrorMsgCode());
    }

    public function testAppVersion(): void
    {
        $this->request->setAppVersion('1.0.0');
        $this->assertSame('1.0.0', $this->request->getAppVersion());
    }

    public function testSdkVersion(): void
    {
        $this->request->setSdkVersion('2.0.0');
        $this->assertSame('2.0.0', $this->request->getSdkVersion());
    }

    public function testOsName(): void
    {
        $this->request->setOsName('iOS');
        $this->assertSame('iOS', $this->request->getOsName());
    }

    public function testClientVersion(): void
    {
        $this->request->setClientVersion('7.0.0');
        $this->assertSame('7.0.0', $this->request->getClientVersion());
    }

    public function testOpenId(): void
    {
        $this->request->setOpenId('openid123');
        $this->assertSame('openid123', $this->request->getOpenId());
    }

    public function testOffset(): void
    {
        $this->request->setOffset(10);
        $this->assertSame(10, $this->request->getOffset());
    }

    public function testLimit(): void
    {
        $this->request->setLimit(30);
        $this->assertSame(30, $this->request->getLimit());
    }

    public function testDesc(): void
    {
        $this->request->setDesc('1');
        $this->assertSame('1', $this->request->getDesc());
    }

    public function testRequestPath(): void
    {
        $this->assertSame('/wxaapi/log/jserr_detail', $this->request->getRequestPath());
    }

    public function testRequestOptions(): void
    {
        $time = CarbonImmutable::now();
        $this->request->setStartTime($time);
        $this->request->setEndTime($time);
        $this->request->setErrorStackCode('stack123');
        $this->request->setErrorMsgCode('msg123');
        $this->request->setAppVersion('1.0.0');
        $this->request->setSdkVersion('2.0.0');
        $this->request->setOsName('iOS');
        $this->request->setClientVersion('7.0.0');
        $this->request->setOpenId('openid123');
        $this->request->setOffset(10);
        $this->request->setLimit(30);
        $this->request->setDesc('1');

        $options = $this->request->getRequestOptions();

        self::assertNotNull($options);
        $this->assertArrayHasKey('json', $options);

        $jsonOptions = $options['json'];
        self::assertIsArray($jsonOptions);
        $this->assertArrayHasKey('errorMsgMd5', $jsonOptions);
        $this->assertArrayHasKey('errorStackMd5', $jsonOptions);
        $this->assertSame('msg123', $jsonOptions['errorMsgMd5']);
        $this->assertSame('stack123', $jsonOptions['errorStackMd5']);
    }
}
