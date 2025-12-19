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
use WechatMiniProgramLogBundle\Command\SyncGetErrorListCommand;
use WechatMiniProgramLogBundle\Repository\ErrorListDataRepository;

/**
 * @internal
 */
#[CoversClass(SyncGetErrorListCommand::class)]
#[RunTestsInSeparateProcesses]
final class SyncGetErrorListCommandTest extends AbstractCommandTestCase
{
    public function testCommandCanBeInstantiated(): void
    {
        $command = self::getContainer()->get(SyncGetErrorListCommand::class);

        $this->assertInstanceOf(Command::class, $command);
        $this->assertSame('wechat-mini-program:sync-get-error-list', $command->getName());
    }

    public function testCommandHasCorrectName(): void
    {
        $this->assertEquals('wechat-mini-program:sync-get-error-list', SyncGetErrorListCommand::NAME);
    }

    public function testCommandExecuteWithNoAccounts(): void
    {
        $command = self::getContainer()->get(SyncGetErrorListCommand::class);
        $this->assertInstanceOf(SyncGetErrorListCommand::class, $command);

        $commandTester = new CommandTester($command);
        $result = $commandTester->execute([]);

        $this->assertEquals(Command::SUCCESS, $result);
    }

    protected function getCommandTester(): CommandTester
    {
        $command = self::getService(SyncGetErrorListCommand::class);
        $this->assertInstanceOf(SyncGetErrorListCommand::class, $command);

        return new CommandTester($command);
    }

    protected function onSetUp(): void        // Command 测试不需要特殊的设置
    {
    }
}
