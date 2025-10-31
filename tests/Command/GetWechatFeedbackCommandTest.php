<?php

namespace WechatMiniProgramLogBundle\Tests\Command;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Tourze\PHPUnitSymfonyKernelTest\AbstractCommandTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramLogBundle\Command\GetWechatFeedbackCommand;

/**
 * @internal
 */
#[CoversClass(GetWechatFeedbackCommand::class)]
#[RunTestsInSeparateProcesses]
final class GetWechatFeedbackCommandTest extends AbstractCommandTestCase
{
    public function testCommandCanBeInstantiated(): void
    {
        $command = self::getContainer()->get(GetWechatFeedbackCommand::class);

        $this->assertInstanceOf(Command::class, $command);
        $this->assertSame('wechat-mini-program:get-feedback', $command->getName());
    }

    public function testCommandExecuteWithNoAccounts(): void
    {
        $accountRepository = self::getContainer()->get(AccountRepository::class);
        $this->assertInstanceOf(AccountRepository::class, $accountRepository);
        $em = self::getService(EntityManagerInterface::class);

        $accounts = $accountRepository->findBy(['valid' => true]);
        foreach ($accounts as $account) {
            $this->assertInstanceOf(Account::class, $account);
            $account->setValid(false);
        }
        $em->flush();

        $command = self::getContainer()->get(GetWechatFeedbackCommand::class);
        $this->assertInstanceOf(GetWechatFeedbackCommand::class, $command);

        $commandTester = new CommandTester($command);
        $result = $commandTester->execute([]);

        $this->assertEquals(Command::SUCCESS, $result);
    }

    protected function getCommandTester(): CommandTester
    {
        $command = self::getService(GetWechatFeedbackCommand::class);
        $this->assertInstanceOf(GetWechatFeedbackCommand::class, $command);

        return new CommandTester($command);
    }

    protected function onSetUp(): void
    {
    }
}
