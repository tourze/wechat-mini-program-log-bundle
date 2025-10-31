<?php

namespace WechatMiniProgramLogBundle\Tests\Entity;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Entity\Feedback;
use WechatMiniProgramLogBundle\Enum\FeedbackType;

/**
 * @internal
 */
#[CoversClass(Feedback::class)]
final class FeedbackTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new Feedback();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'mediaIds' => ['mediaIds', ['key' => 'value']],
        ];
    }

    private Feedback $feedback;

    protected function setUp(): void
    {
        parent::setUp();

        $this->feedback = new Feedback();
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
        $this->feedback->setAccount($account);
        $this->assertSame($account, $this->feedback->getAccount());
    }

    public function testWxRecordId(): void
    {
        $this->feedback->setWxRecordId('record123');
        $this->assertSame('record123', $this->feedback->getWxRecordId());
    }

    public function testWxCreateTime(): void
    {
        $time = CarbonImmutable::now();
        $this->feedback->setWxCreateTime($time);
        $this->assertSame($time, $this->feedback->getWxCreateTime());
    }

    public function testContent(): void
    {
        $this->feedback->setContent('Test content');
        $this->assertSame('Test content', $this->feedback->getContent());
    }

    public function testPhone(): void
    {
        $this->feedback->setPhone('13800138000');
        $this->assertSame('13800138000', $this->feedback->getPhone());
    }

    public function testOpenid(): void
    {
        $this->feedback->setOpenid('openid123');
        $this->assertSame('openid123', $this->feedback->getOpenid());

        $this->feedback->setOpenid(null);
        $this->assertNull($this->feedback->getOpenid());
    }

    public function testNickname(): void
    {
        $this->feedback->setNickname('Test User');
        $this->assertSame('Test User', $this->feedback->getNickname());

        $this->feedback->setNickname(null);
        $this->assertNull($this->feedback->getNickname());
    }

    public function testHeadUrl(): void
    {
        $this->feedback->setHeadUrl('https://example.com/avatar.jpg');
        $this->assertSame('https://example.com/avatar.jpg', $this->feedback->getHeadUrl());
    }

    public function testFeedbackType(): void
    {
        $type = FeedbackType::TYPE_1;
        $this->feedback->setFeedbackType($type);
        $this->assertSame($type, $this->feedback->getFeedbackType());
    }

    public function testMediaIds(): void
    {
        $mediaIds = ['media1', 'media2'];
        $this->feedback->setMediaIds($mediaIds);
        $this->assertSame($mediaIds, $this->feedback->getMediaIds());
    }

    public function testSystemInfo(): void
    {
        $systemInfo = '{"version": "1.0", "os": "iOS"}';
        $this->feedback->setSystemInfo($systemInfo);
        $this->assertSame($systemInfo, $this->feedback->getSystemInfo());
    }

    public function testRawData(): void
    {
        $this->feedback->setRawData('{"test": "data"}');
        $this->assertSame('{"test": "data"}', $this->feedback->getRawData());
    }
}
