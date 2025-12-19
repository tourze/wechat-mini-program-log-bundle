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
use WechatMiniProgramLogBundle\Command\GetWechatPenaltyListCommand;
use WechatMiniProgramLogBundle\Repository\PenaltyListRepository;

/**
 * @internal
 */
#[CoversClass(GetWechatPenaltyListCommand::class)]
#[RunTestsInSeparateProcesses]
final class GetWechatPenaltyListCommandTest extends AbstractCommandTestCase
{
    public function testCommandCanBeInstantiated(): void
    {
        $command = self::getContainer()->get(GetWechatPenaltyListCommand::class);

        $this->assertInstanceOf(Command::class, $command);
        $this->assertSame('wechat-mini-program:get-penalty', $command->getName());
    }

    public function testCommandHasCorrectName(): void
    {
        $this->assertEquals('wechat-mini-program:get-penalty', GetWechatPenaltyListCommand::NAME);
    }

    public function testCommandExecuteWithNoAccounts(): void
    {
        // 清理所有有效账户
        $em = self::getService(EntityManagerInterface::class);
        $em->createQuery('UPDATE WechatMiniProgramBundle\Entity\Account a SET a.valid = false')->execute();
        $em->flush();
        $em->clear();

        $command = self::getContainer()->get(GetWechatPenaltyListCommand::class);
        $this->assertInstanceOf(GetWechatPenaltyListCommand::class, $command);

        $commandTester = new CommandTester($command);
        $result = $commandTester->execute([]);

        $this->assertEquals(Command::SUCCESS, $result);
    }

    protected function getCommandTester(): CommandTester
    {
        $command = self::getService(GetWechatPenaltyListCommand::class);
        $this->assertInstanceOf(GetWechatPenaltyListCommand::class, $command);

        return new CommandTester($command);
    }

    protected function onSetUp(): void        // Command 测试不需要特殊的设置
    {
    }
}
