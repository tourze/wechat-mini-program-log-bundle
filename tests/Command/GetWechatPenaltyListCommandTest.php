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
        /*
         * 使用具体类 Client 进行 Mock，因为：
         * 1) Client 是微信小程序服务的核心客户端，没有对应的接口抽象
         * 2) 在测试中需要模拟其网络请求行为，使用具体类是必要的
         * 3) 后续可考虑为 Client 创建接口来改善测试性
         */
        $client = $this->createMock(Client::class);
        /*
         * 使用具体类 PenaltyListRepository 进行 Mock，因为：
         * 1) Repository 继承自 Doctrine 的具体实现类，而非接口
         * 2) 测试中需要模拟数据库查询操作，直接 Mock Repository 是合理的
         * 3) 符合 Doctrine Repository 的标准测试模式
         */
        $penaltyListRepository = $this->createMock(PenaltyListRepository::class);
        /*
         * 使用具体类 AccountRepository 进行 Mock，因为：
         * 1) AccountRepository 是 Doctrine Repository 的具体实现
         * 2) 测试中需要模拟账户查询功能，Mock 具体类是标准做法
         * 3) 与项目中其他 Repository 的测试模式保持一致
         */
        $accountRepository = $this->createMock(AccountRepository::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        $command = self::getContainer()->get(GetWechatPenaltyListCommand::class);

        $this->assertInstanceOf(GetWechatPenaltyListCommand::class, $command);
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
