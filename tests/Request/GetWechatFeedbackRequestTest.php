<?php

namespace WechatMiniProgramLogBundle\Tests\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramLogBundle\Request\GetWechatFeedbackRequest;
use WechatMiniProgramBundle\Entity\Account;

class GetWechatFeedbackRequestTest extends TestCase
{
    private GetWechatFeedbackRequest $request;

    protected function setUp(): void
    {
        $this->request = new GetWechatFeedbackRequest();
    }

    public function testAccount(): void
    {
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
        
        $this->assertIsArray($options);
        $this->assertArrayHasKey('query', $options);
        $this->assertArrayHasKey('page', $options['query']);
        $this->assertArrayHasKey('num', $options['query']);
        $this->assertSame(3, $options['query']['page']);
        $this->assertSame(30, $options['query']['num']);
    }
}