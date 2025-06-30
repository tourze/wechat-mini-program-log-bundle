<?php

namespace WechatMiniProgramLogBundle\Command;

use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramLogBundle\Entity\PenaltyList;
use WechatMiniProgramLogBundle\Enum\PenaltyStatus;
use WechatMiniProgramLogBundle\Repository\PenaltyListRepository;
use WechatMiniProgramLogBundle\Request\GetWechatPenaltyListRequest;

#[AsCronTask(expression: '40 1 * * *')]
#[AsCronTask(expression: '45 13 * * *')]
#[AsCommand(name: self::NAME, description: '获取小程序交易体验分违规记录')]
class GetWechatPenaltyListCommand extends Command
{
    
    public const NAME = 'wechat-mini-program:get-penalty';
public function __construct(
        private readonly Client $client,
        private readonly PenaltyListRepository $penaltyListRepository,
        private readonly AccountRepository $accountRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            $start = CarbonImmutable::now()->getTimestamp();
            $this->request($account, 0, 20);
            $output->writeln($account->getId() . ' wechat-mini-program:check-performance command end, use time:' . time() - $start);
        }

        return Command::SUCCESS;
    }

    private function request(Account $account, int $offset, int $limit): void
    {
        $request = new GetWechatPenaltyListRequest();
        $request->setAccount($account);
        $request->setLimit($limit);
        $request->setOffset($offset);
        $response = $this->client->request($request);

        foreach ($response['appealList'] as $item) {
            $penalty = $this->penaltyListRepository->findOneBy([
                'illegalOrderId' => $item['illegalOrderId'],
            ]);
            if ((bool) empty($penalty)) {
                $penalty = new PenaltyList();
                $penalty->setIllegalOrderId($item['illegalOrderId']);
                $penalty->setComplaintOrderId($item['complaintOrderId']);
                $penalty->setIllegalWording($item['illegalWording']);
                $penalty->setMinusScore($item['minusScore']);
                $penalty->setIllegalTime(CarbonImmutable::parse($item['illegalTime']));
                $penalty->setOrderId($item['orderId']);
                $penalty->setCurrentScore($response['currentScore']);
                $penalty->setRawData(json_encode($item));
            }

            $status = PenaltyStatus::tryFrom($item['status']);
            if (empty($penalty->getPenaltyStatus()) || $penalty->getPenaltyStatus() != $status) {
                $penalty->setPenaltyStatus($status);
            }

            $this->entityManager->persist($penalty);
            $this->entityManager->flush();
        }

        if (($offset + 1) * $limit < $response['totalNum']) {
            ++$offset;
            $this->request($account, $offset, $limit);
        }
    }
}
