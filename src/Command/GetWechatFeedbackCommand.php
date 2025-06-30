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
use WechatMiniProgramLogBundle\Entity\Feedback;
use WechatMiniProgramLogBundle\Enum\FeedbackType;
use WechatMiniProgramLogBundle\Repository\FeedbackRepository;
use WechatMiniProgramLogBundle\Request\GetWechatFeedbackRequest;

#[AsCronTask(expression: '15 * * * *')]
#[AsCommand(name: self::NAME, description: '定期获取小程序反馈信息')]
class GetWechatFeedbackCommand extends Command
{
    
    public const NAME = 'wechat-mini-program:get-feedback';
public function __construct(
        private readonly Client $client,
        private readonly FeedbackRepository $feedbackRepository,
        private readonly AccountRepository $accountRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $accounts = $this->accountRepository->findBy(['valid' => true]);
        foreach ($accounts as $account) {
            $start = CarbonImmutable::now()->getTimestamp();
            $this->request($account, $output, 1, 20);
            $output->writeln("{$account->getId()} wechat-mini-program:get-feedback command end, use time:" . time() - $start);
        }

        return Command::SUCCESS;
    }

    private function request(Account $account, OutputInterface $output, int $page, int $num): void
    {
        $request = new GetWechatFeedbackRequest();
        $request->setAccount($account);
        $request->setNum($num);
        $request->setPage($page);
        $response = $this->client->request($request);

        $output->writeln("page: $page, num: $num");

        foreach ($response['list'] as $item) {
            $feedback = $this->feedbackRepository->findOneBy([
                'wxRecordId' => $item['record_id'],
            ]);
            if ((bool) $feedback) {
                continue;
            }
            $feedback = new Feedback();
            $feedback->setAccount($account);
            $feedback->setWxRecordId($item['record_id']);
            $feedback->setWxCreateTime(CarbonImmutable::parse($item['create_time']));
            $feedback->setContent($item['content']);
            $feedback->setPhone($item['phone']);
            $feedback->setOpenid($item['openid'] ?? null);
            $feedback->setNickname($item['nickname'] ?? null);
            $feedback->setHeadUrl($item['head_url']);
            $feedback->setFeedbackType(FeedbackType::tryFrom($item['type']));
            $feedback->setMediaIds($item['mediaIds']);
            if ((bool) isset($item['systemInfo'])) {
                $feedback->setSystemInfo($item['systemInfo']);
            }
            $feedback->setRawData(json_encode($item));
            $this->entityManager->persist($feedback);
            $this->entityManager->flush();
        }

        if ($page * $num < $response['total_num']) {
            ++$page;
            $this->request($account, $output, $page, $num);
        }
    }
}
