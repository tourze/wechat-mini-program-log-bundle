<?php

namespace WechatMiniProgramLogBundle\Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use WechatMiniProgramLogBundle\Command\GetWechatPenaltyListCommand;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramLogBundle\Repository\PenaltyListRepository;
use WechatMiniProgramBundle\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use WechatMiniProgramBundle\Entity\Account;

class GetWechatPenaltyListCommandTest extends TestCase
{
    private CommandTester $commandTester;
    private Client $client;
    private PenaltyListRepository $penaltyListRepository;
    private AccountRepository $accountRepository;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->penaltyListRepository = $this->createMock(PenaltyListRepository::class);
        $this->accountRepository = $this->createMock(AccountRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $command = new GetWechatPenaltyListCommand(
            $this->client,
            $this->penaltyListRepository,
            $this->accountRepository,
            $this->entityManager
        );

        $application = new Application();
        $application->add($command);

        $command = $application->find('wechat-mini-program:get-penalty');
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
        $account->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $this->accountRepository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([$account]);

        $this->client->expects($this->once())
            ->method('request')
            ->willReturn([
                'appealList' => [],
                'currentScore' => 100,
                'totalNum' => 0
            ]);

        $exitCode = $this->commandTester->execute([]);

        $this->assertSame(0, $exitCode);
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('1 wechat-mini-program:check-performance command end', $output);
    }

    public function testCommandName(): void
    {
        $this->assertSame('wechat-mini-program:get-penalty', GetWechatPenaltyListCommand::NAME);
    }
}