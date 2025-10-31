<?php

namespace WechatMiniProgramLogBundle\Tests\Request;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use HttpClientBundle\Tests\Request\RequestTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Request\GetErrorListRequest;

/**
 * @internal
 */
#[CoversClass(GetErrorListRequest::class)]
final class GetErrorListRequestTest extends RequestTestCase
{
    private GetErrorListRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new GetErrorListRequest();
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

    public function testErrType(): void
    {
        $this->request->setErrType('1');
        $this->assertSame('1', $this->request->getErrType());
    }

    public function testAppVersion(): void
    {
        $this->request->setAppVersion('1.0.0');
        $this->assertSame('1.0.0', $this->request->getAppVersion());
    }

    public function testOpenId(): void
    {
        $this->request->setOpenId('openid123');
        $this->assertSame('openid123', $this->request->getOpenId());
    }

    public function testKeyword(): void
    {
        $this->request->setKeyword('error');
        $this->assertSame('error', $this->request->getKeyword());
    }

    public function testOrderBy(): void
    {
        $this->request->setOrderBy('uv');
        $this->assertSame('uv', $this->request->getOrderBy());
    }

    public function testDesc(): void
    {
        $this->request->setDesc('2');
        $this->assertSame('2', $this->request->getDesc());
    }

    public function testOffset(): void
    {
        $this->request->setOffset(0);
        $this->assertSame(0, $this->request->getOffset());
    }

    public function testLimit(): void
    {
        $this->request->setLimit(30);
        $this->assertSame(30, $this->request->getLimit());
    }

    public function testRequestPath(): void
    {
        $this->assertSame('/wxaapi/log/jserr_list', $this->request->getRequestPath());
    }

    public function testRequestOptions(): void
    {
        $time = CarbonImmutable::now();
        $this->request->setStartTime($time);
        $this->request->setEndTime($time);
        $this->request->setErrType('1');
        $this->request->setAppVersion('1.0.0');
        $this->request->setOpenId('openid123');
        $this->request->setKeyword('error');
        $this->request->setOrderBy('uv');
        $this->request->setDesc('2');
        $this->request->setOffset(0);
        $this->request->setLimit(30);

        $options = $this->request->getRequestOptions();

        self::assertNotNull($options);
        $this->assertArrayHasKey('json', $options);

        $jsonOptions = $options['json'];
        self::assertIsArray($jsonOptions);
        $this->assertArrayHasKey('errType', $jsonOptions);
        $this->assertArrayHasKey('orderby', $jsonOptions);
        $this->assertSame('1', $jsonOptions['errType']);
        $this->assertSame('uv', $jsonOptions['orderby']);
    }
}
