<?php

namespace WechatMiniProgramLogBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Request\GetWechatPenaltyListRequest;

/**
 * @internal
 */
#[CoversClass(GetWechatPenaltyListRequest::class)]
final class GetWechatPenaltyListRequestTest extends RequestTestCase
{
    private GetWechatPenaltyListRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new GetWechatPenaltyListRequest();
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

    public function testOffset(): void
    {
        $this->request->setOffset(10);
        $this->assertSame(10, $this->request->getOffset());
    }

    public function testLimit(): void
    {
        $this->request->setLimit(20);
        $this->assertSame(20, $this->request->getLimit());
    }

    public function testRequestPath(): void
    {
        $this->assertSame('/wxaapi/wxamptrade/get_penalty_list', $this->request->getRequestPath());
    }

    public function testRequestMethod(): void
    {
        $this->assertSame('GET', $this->request->getRequestMethod());
    }

    public function testRequestOptions(): void
    {
        $this->request->setOffset(5);
        $this->request->setLimit(15);

        $options = $this->request->getRequestOptions();

        self::assertNotNull($options);
        $this->assertArrayHasKey('query', $options);

        $queryOptions = $options['query'];
        self::assertIsArray($queryOptions);
        $this->assertArrayHasKey('offset', $queryOptions);
        $this->assertArrayHasKey('limit', $queryOptions);
        $this->assertSame(5, $queryOptions['offset']);
        $this->assertSame(15, $queryOptions['limit']);
    }
}
