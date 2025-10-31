<?php

declare(strict_types=1);

namespace WechatMiniProgramLogBundle\Tests\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use WechatMiniProgramLogBundle\Controller\ErrorListDataCrudController;
use WechatMiniProgramLogBundle\Entity\ErrorListData;

/**
 * @internal
 */
#[CoversClass(ErrorListDataCrudController::class)]
#[RunTestsInSeparateProcesses]
class ErrorListDataCrudControllerTest extends AbstractReadOnlyEasyAdminControllerTestCase
{
    protected function getControllerService(): ErrorListDataCrudController
    {
        return self::getService(ErrorListDataCrudController::class);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield '账户' => ['账户'];
        yield '日期' => ['日期'];
        yield 'OpenID' => ['OpenID'];
        yield '错误消息代码' => ['错误消息代码'];
        yield '错误消息' => ['错误消息'];
        yield 'UV访问量' => ['UV访问量'];
        yield 'PV访问量' => ['PV访问量'];
        yield 'UV百分比' => ['UV百分比'];
        yield 'PV百分比' => ['PV百分比'];
        yield '创建时间' => ['创建时间'];
    }

    // provideNewPageFields 和 provideEditPageFields 由基类提供空实现

    private function getController(): ErrorListDataCrudController
    {
        return new ErrorListDataCrudController();
    }

    public function testGetEntityFqcn(): void
    {
        $result = ErrorListDataCrudController::getEntityFqcn();

        $this->assertSame(ErrorListData::class, $result);
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
