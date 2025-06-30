<?php

namespace WechatMiniProgramLogBundle\Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use WechatMiniProgramLogBundle\Command\GetWechatFeedbackCommand;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramLogBundle\Repository\FeedbackRepository;
use WechatMiniProgramBundle\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use WechatMiniProgramBundle\Entity\Account;

class GetWechatFeedbackCommandTest extends TestCase
{
    private CommandTester $commandTester;
    private Client $client;
    private FeedbackRepository $feedbackRepository;
    private AccountRepository $accountRepository;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->feedbackRepository = $this->createMock(FeedbackRepository::class);
        $this->accountRepository = $this->createMock(AccountRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $command = new GetWechatFeedbackCommand(
            $this->client,
            $this->feedbackRepository,
            $this->accountRepository,
            $this->entityManager
        );

        $application = new Application();
        $application->add($command);

        $command = $application->find('wechat-mini-program:get-feedback');
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
                'list' => [],
                'total_num' => 0
            ]);

        $exitCode = $this->commandTester->execute([]);

        $this->assertSame(0, $exitCode);
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('1 wechat-mini-program:get-feedback command end', $output);
    }

    public function testCommandName(): void
    {
        $this->assertSame('wechat-mini-program:get-feedback', GetWechatFeedbackCommand::NAME);
    }
}