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
        /*
         * 使用具体类 AccountRepository 进行 Mock，因为：
         * 1) AccountRepository继承自Doctrine的具体实现类，而非接口
         * 2) 测试中需要模拟数据库查询操作，直接Mock Repository是合理的
         * 3) 符合Doctrine Repository的标准测试模式
         */
        $accountRepository = $this->createMock(AccountRepository::class);
        /*
         * 使用具体类 Client 进行 Mock，因为：
         * 1) 微信小程序服务的核心客户端，没有对应的接口抽象
         * 2) 在测试中需要模拟其网络请求行为，使用具体类是必要的
         * 3) 后续可考虑为Client创建接口来改善测试性
         */
        $client = $this->createMock(Client::class);
        /*
         * 使用具体类 ErrorListDataRepository 进行 Mock，因为：
         * 1) Repository继承自Doctrine的具体实现类，而非接口
         * 2) 测试中需要模拟数据库查询操作，直接Mock Repository是合理的
         * 3) 符合Doctrine Repository的标准测试模式
         */
        $errorListDataRepository = $this->createMock(ErrorListDataRepository::class);
        /*
         * 使用接口 EntityManagerInterface 进行 Mock，因为：
         * 1) EntityManagerInterface是Doctrine ORM的标准接口
         * 2) 测试中需要模拟数据库管理操作，Mock接口是最佳实践
         * 3) 符合依赖注入和接口隔离原则
         */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        /*
         * 使用接口 LoggerInterface 进行 Mock，因为：
         * 1) LoggerInterface是PSR-3标准的日志接口
         * 2) 测试中需要模拟日志记录行为，Mock接口是标准做法
         * 3) 符合依赖注入原则，便于测试和维护
         */
        $logger = $this->createMock(LoggerInterface::class);

        $command = self::getContainer()->get(SyncGetErrorListCommand::class);

        $this->assertInstanceOf(SyncGetErrorListCommand::class, $command);
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
