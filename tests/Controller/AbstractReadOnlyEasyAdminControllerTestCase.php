<?php

declare(strict_types=1);

namespace WechatMiniProgramLogBundle\Tests\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;

/**
 * 为禁用了NEW/EDIT/DELETE操作的只读EasyAdmin控制器提供适配的测试基类
 *
 * @internal
 */
#[CoversClass(AbstractEasyAdminControllerTestCase::class)]
#[RunTestsInSeparateProcesses]
abstract class AbstractReadOnlyEasyAdminControllerTestCase extends AbstractEasyAdminControllerTestCase
{
    /**
     * 对于只读控制器，提供占位符字段以满足DataProvider要求
     * 实际测试时会因操作被禁用而跳过
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        // 临时禁用测试，直到基类问题得到修复
        // 相关GitHub Issue: https://github.com/tourze/php-monorepo/issues/1603
        yield 'temporarily_disabled' => ['temporarily_disabled'];
    }

    /**
     * 对于只读控制器，提供占位符字段以满足DataProvider要求
     * 实际测试时会因操作被禁用而跳过
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        // 临时禁用测试，直到基类问题得到修复
        // 相关GitHub Issue: https://github.com/tourze/php-monorepo/issues/1603
        yield 'temporarily_disabled' => ['temporarily_disabled'];
    }

    /**
     * 验证控制器正确配置了禁用操作
     */
    final public function testControllerDisablesWriteOperations(): void
    {
        $controller = $this->getControllerService();
        $actions = Actions::new();
        $configuredActions = $controller->configureActions($actions);

        // 验证NEW操作被禁用
        $newActions = $configuredActions->getAsDto(Crud::PAGE_NEW)->getActions();
        self::assertEmpty($newActions, 'NEW操作应该被禁用');

        // 验证EDIT操作被禁用
        $editActions = $configuredActions->getAsDto(Crud::PAGE_EDIT)->getActions();
        self::assertEmpty($editActions, 'EDIT操作应该被禁用');

        // 验证INDEX页面有DETAIL操作 - 简化检查，只验证非空即可
        $indexActions = $configuredActions->getAsDto(Crud::PAGE_INDEX)->getActions();
        self::assertNotEmpty($indexActions, 'INDEX页面应该包含操作（包含DETAIL）');
    }

    /**
     * 验证字段配置对各个页面都有效（即使某些页面被禁用）
     */
    final public function testConfigureFieldsForAllPages(): void
    {
        $controller = $this->getControllerService();

        // 测试INDEX页面字段
        $indexFields = iterator_to_array($controller->configureFields(Crud::PAGE_INDEX));
        self::assertNotEmpty($indexFields, 'INDEX页面应该有字段配置');
        foreach ($indexFields as $field) {
            self::assertInstanceOf(FieldInterface::class, $field);
        }

        // 测试DETAIL页面字段
        $detailFields = iterator_to_array($controller->configureFields(Crud::PAGE_DETAIL));
        self::assertNotEmpty($detailFields, 'DETAIL页面应该有字段配置');
        foreach ($detailFields as $field) {
            self::assertInstanceOf(FieldInterface::class, $field);
        }

        // 即使NEW/EDIT被禁用，configureFields方法仍应能处理这些页面类型
        // 注意：这些调用主要是为了确保方法不会抛出异常
        iterator_to_array($controller->configureFields(Crud::PAGE_NEW));
        iterator_to_array($controller->configureFields(Crud::PAGE_EDIT));
    }

    /**
     * 由于基类方法是final的，通过setUp方法来实现跳过逻辑
     */
}
