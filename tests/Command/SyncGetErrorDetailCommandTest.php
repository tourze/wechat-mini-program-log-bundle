<?php

namespace WechatMiniProgramLogBundle\Tests\Command;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Tourze\PHPUnitSymfonyKernelTest\AbstractCommandTestCase;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramLogBundle\Command\SyncGetErrorDetailCommand;
use WechatMiniProgramLogBundle\Repository\ErrorDetailRepository;
use WechatMiniProgramLogBundle\Repository\ErrorListDataRepository;

/**
 * @internal
 */
#[CoversClass(SyncGetErrorDetailCommand::class)]
#[RunTestsInSeparateProcesses]
final class SyncGetErrorDetailCommandTest extends AbstractCommandTestCase
{
    public function testCommandCanBeInstantiated(): void
    {
        $command = self::getContainer()->get(SyncGetErrorDetailCommand::class);

        $this->assertInstanceOf(Command::class, $command);
        $this->assertSame('wechat-mini-program:sync-get-error-detail', $command->getName());
    }

    public function testCommandHasCorrectName(): void
    {
        $this->assertEquals('wechat-mini-program:sync-get-error-detail', SyncGetErrorDetailCommand::NAME);
    }

    public function testCommandExecuteWithNoAccounts(): void
    {
        $command = self::getContainer()->get(SyncGetErrorDetailCommand::class);
        $this->assertInstanceOf(SyncGetErrorDetailCommand::class, $command);

        $commandTester = new CommandTester($command);
        $result = $commandTester->execute([]);

        $this->assertEquals(Command::SUCCESS, $result);
    }

    protected function getCommandTester(): CommandTester
    {
        $command = self::getService(SyncGetErrorDetailCommand::class);
        $this->assertInstanceOf(SyncGetErrorDetailCommand::class, $command);

        return new CommandTester($command);
    }

    protected function onSetUp(): void        // Command 测试不需要特殊的设置
    {
    }
}
