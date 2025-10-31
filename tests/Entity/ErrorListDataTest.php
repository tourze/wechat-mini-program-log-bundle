<?php

namespace WechatMiniProgramLogBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Entity\ErrorListData;

/**
 * @internal
 */
#[CoversClass(ErrorListData::class)]
final class ErrorListDataTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new ErrorListData();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'errorMsgCode' => ['errorMsgCode', 'test_error_code'],
            'errorMsg' => ['errorMsg', 'Test error message'],
            'uv' => ['uv', 100],
            'pv' => ['pv', 500],
            'errorStackCode' => ['errorStackCode', 'stack_code_123'],
            'errorStack' => ['errorStack', 'Error stack trace'],
            'pvPercent' => ['pvPercent', '50.5'],
            'uvPercent' => ['uvPercent', '30.2'],
            'openId' => ['openId', 'openid123'],
        ];
    }

    public function testCreateNewInstanceWithDefaultValues(): void
    {
        $errorListData = new ErrorListData();

        // 验证默认值
        $this->assertSame(0, $errorListData->getId());
        $this->assertNull($errorListData->getErrorMsgCode());
        $this->assertNull($errorListData->getErrorMsg());
        $this->assertNull($errorListData->getUv());
        $this->assertNull($errorListData->getPv());
        $this->assertNull($errorListData->getErrorStackCode());
        $this->assertNull($errorListData->getErrorStack());
        $this->assertNull($errorListData->getPvPercent());
        $this->assertNull($errorListData->getUvPercent());
        $this->assertNull($errorListData->getOpenId());
        $this->assertNull($errorListData->getAccount());
    }
}
