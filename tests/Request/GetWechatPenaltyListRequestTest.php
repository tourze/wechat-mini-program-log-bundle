<?php

namespace WechatMiniProgramLogBundle\Tests\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramLogBundle\Request\GetWechatPenaltyListRequest;
use WechatMiniProgramBundle\Entity\Account;

class GetWechatPenaltyListRequestTest extends TestCase
{
    private GetWechatPenaltyListRequest $request;

    protected function setUp(): void
    {
        $this->request = new GetWechatPenaltyListRequest();
    }

    public function testAccount(): void
    {
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
        
        $this->assertIsArray($options);
        $this->assertArrayHasKey('query', $options);
        $this->assertArrayHasKey('offset', $options['query']);
        $this->assertArrayHasKey('limit', $options['query']);
        $this->assertSame(5, $options['query']['offset']);
        $this->assertSame(15, $options['query']['limit']);
    }
}