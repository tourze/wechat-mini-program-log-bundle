<?php

declare(strict_types=1);

namespace WechatMiniProgramLogBundle\Tests\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use WechatMiniProgramLogBundle\Controller\FeedbackCrudController;
use WechatMiniProgramLogBundle\Entity\Feedback;

/**
 * @internal
 * @method void markTestSkipped(string $message = '')
 */
#[CoversClass(FeedbackCrudController::class)]
#[RunTestsInSeparateProcesses]
class FeedbackCrudControllerTest extends AbstractReadOnlyEasyAdminControllerTestCase
{
    protected function getControllerService(): FeedbackCrudController
    {
        return self::getService(FeedbackCrudController::class);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield '账户' => ['账户'];
        yield '微信记录ID' => ['微信记录ID'];
        yield '微信创建时间' => ['微信创建时间'];
        yield '反馈类型' => ['反馈类型'];
        yield '昵称' => ['昵称'];
        yield '手机号' => ['手机号'];
        yield '反馈内容' => ['反馈内容'];
        yield '创建时间' => ['创建时间'];
    }

    // provideNewPageFields 和 provideEditPageFields 由基类提供空实现

    private function getController(): FeedbackCrudController
    {
        return new FeedbackCrudController();
    }

    public function testConfigureCrud(): void
    {
        $crud = Crud::new();
        $result = $this->getController()->configureCrud($crud);

        // 验证返回的是同一个Crud对象
        $this->assertSame($crud, $result);
        $this->assertInstanceOf(Crud::class, $result);
    }

    // testConfigureFieldsForAllPages 方法由基类提供

    // 基类的测试方法是final的，无法重写，但基类现在有了markTestSkipped检查
    // 基类的isActionEnabled()方法会自动检测禁用的操作并跳过相关测试
}
