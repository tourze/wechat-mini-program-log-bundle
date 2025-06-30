<?php

namespace WechatMiniProgramLogBundle\Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use WechatMiniProgramLogBundle\Command\SyncGetErrorListCommand;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramLogBundle\Repository\ErrorListDataRepository;
use WechatMiniProgramBundle\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use WechatMiniProgramBundle\Entity\Account;

class SyncGetErrorListCommandTest extends TestCase
{
    private CommandTester $commandTester;
    private Client $client;
    private ErrorListDataRepository $errorListDataRepository;
    private AccountRepository $accountRepository;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->accountRepository = $this->createMock(AccountRepository::class);
        $this->client = $this->createMock(Client::class);
        $this->errorListDataRepository = $this->createMock(ErrorListDataRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $command = new SyncGetErrorListCommand(
            $this->accountRepository,
            $this->client,
            $this->errorListDataRepository,
            $this->entityManager
        );

        $application = new Application();
        $application->add($command);

        $command = $application->find('wechat-mini-program:sync-get-error-list');
        $this->commandTester = new CommandTester($command);
    }

    public function testExecuteWithNoAccounts(): void
    {
        $this->accountRepository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([]);

        $exitCode = $this->commandTester->execute([]);

        $this->assertSame(0, $exitCode);
    }

    public function testExecuteWithAccount(): void
    {
        $account = $this->createMock(Account::class);

        $this->accountRepository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([$account]);

        $this->client->expects($this->once())
            ->method('request')
            ->willReturn([
                'data' => [],
                'openid' => 'test_openid'
            ]);

        $exitCode = $this->commandTester->execute([]);

        $this->assertSame(0, $exitCode);
    }

    public function testExecuteWithException(): void
    {
        $account = $this->createMock(Account::class);
        $account->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $this->accountRepository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([$account]);

        $this->client->expects($this->once())
            ->method('request')
            ->willThrowException(new \Exception('Test exception'));

        $exitCode = $this->commandTester->execute([]);

        $this->assertSame(0, $exitCode);
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('同步[1]错误列表出错', $output);
    }

    public function testCommandName(): void
    {
        $this->assertSame('wechat-mini-program:sync-get-error-list', SyncGetErrorListCommand::NAME);
    }
}