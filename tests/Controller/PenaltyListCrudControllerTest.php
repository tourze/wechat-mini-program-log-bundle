<?php

declare(strict_types=1);

namespace WechatMiniProgramLogBundle\Tests\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use WechatMiniProgramLogBundle\Controller\PenaltyListCrudController;
use WechatMiniProgramLogBundle\Entity\PenaltyList;

/**
 * @internal
 * @method void markTestSkipped(string $message = '')
 */
#[CoversClass(PenaltyListCrudController::class)]
#[RunTestsInSeparateProcesses]
class PenaltyListCrudControllerTest extends AbstractReadOnlyEasyAdminControllerTestCase
{
    protected function getControllerService(): PenaltyListCrudController
    {
        return self::getService(PenaltyListCrudController::class);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield '违规订单ID' => ['违规订单ID'];
        yield '投诉订单ID' => ['投诉订单ID'];
        yield '违规时间' => ['违规时间'];
        yield '处罚状态' => ['处罚状态'];
        yield '扣分' => ['扣分'];
        yield '当前分数' => ['当前分数'];
        yield '订单ID' => ['订单ID'];
        yield '违规措辞' => ['违规措辞'];
        yield '创建时间' => ['创建时间'];
    }

    // provideNewPageFields 和 provideEditPageFields 由基类提供空实现

    private function getController(): PenaltyListCrudController
    {
        return new PenaltyListCrudController();
    }

    public function testGetEntityFqcn(): void
    {
        $result = PenaltyListCrudController::getEntityFqcn();

        $this->assertSame(PenaltyList::class, $result);
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
