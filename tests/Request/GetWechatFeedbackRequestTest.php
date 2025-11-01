<?php

namespace WechatMiniProgramLogBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Request\GetWechatFeedbackRequest;

/**
 * @internal
 */
#[CoversClass(GetWechatFeedbackRequest::class)]
final class GetWechatFeedbackRequestTest extends RequestTestCase
{
    private GetWechatFeedbackRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new GetWechatFeedbackRequest();
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

    public function testPage(): void
    {
        $this->request->setPage(2);
        $this->assertSame(2, $this->request->getPage());
    }

    public function testNum(): void
    {
        $this->request->setNum(20);
        $this->assertSame(20, $this->request->getNum());
    }

    public function testRequestPath(): void
    {
        $this->assertSame('/wxaapi/feedback/list', $this->request->getRequestPath());
    }

    public function testRequestMethod(): void
    {
        $this->assertSame('GET', $this->request->getRequestMethod());
    }

    public function testRequestOptions(): void
    {
        $this->request->setPage(3);
        $this->request->setNum(30);

        $options = $this->request->getRequestOptions();

        self::assertNotNull($options);
        $this->assertArrayHasKey('query', $options);

        $queryOptions = $options['query'];
        self::assertIsArray($queryOptions);
        $this->assertArrayHasKey('page', $queryOptions);
        $this->assertArrayHasKey('num', $queryOptions);
        $this->assertSame(3, $queryOptions['page']);
        $this->assertSame(30, $queryOptions['num']);
    }
}
