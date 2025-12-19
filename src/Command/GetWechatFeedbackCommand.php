<?php

namespace WechatMiniProgramLogBundle\Command;

use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
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
#[WithMonologChannel(channel: 'wechat_mini_program_log')]
final class GetWechatFeedbackCommand extends Command
{
    public const NAME = 'wechat-mini-program:get-feedback';

    public function __construct(
        private readonly Client $client,
        private readonly FeedbackRepository $feedbackRepository,
        private readonly AccountRepository $accountRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
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
        $response = $this->sendRequest($account, $page, $num);
        $output->writeln("page: {$page}, num: {$num}");

        $feedbackList = $this->processResponse($response);
        $this->saveFeedbackData($account, $feedbackList);

        $totalNum = $response['total_num'] ?? 0;
        assert(is_int($totalNum), 'totalNum should be int');
        $this->handlePagination($account, $output, $page, $num, $totalNum);
    }

    /**
     * @return array<string, mixed>
     */
    private function sendRequest(Account $account, int $page, int $num): array
    {
        $startTime = microtime(true);
        $request = new GetWechatFeedbackRequest();
        $request->setAccount($account);
        $request->setNum($num);
        $request->setPage($page);

        $this->logger->info('Starting WeChat feedback request', [
            'account_id' => $account->getId(),
            'page' => $page,
            'num' => $num,
        ]);

        try {
            $response = $this->client->request($request);
            assert(is_array($response), 'Response should be an array');
            /** @var array<string, mixed> $response */
            $requestTime = (microtime(true) - $startTime) * 1000;
            $totalNum = $response['total_num'] ?? 0;
            assert(is_int($totalNum), 'totalNum should be int');
            $list = $response['list'] ?? [];
            assert(is_array($list), 'List should be an array');

            $this->logger->info('WeChat feedback request completed', [
                'account_id' => $account->getId(),
                'page' => $page,
                'num' => $num,
                'response_total' => $totalNum,
                'response_count' => count($list),
                'duration_ms' => round($requestTime, 2),
            ]);

            return $response;
        } catch (\Exception $e) {
            $requestTime = (microtime(true) - $startTime) * 1000;

            $this->logger->error('WeChat feedback request failed', [
                'account_id' => $account->getId(),
                'page' => $page,
                'num' => $num,
                'error' => $e->getMessage(),
                'duration_ms' => round($requestTime, 2),
            ]);

            throw $e;
        }
    }

    /**
     * @param array<string, mixed> $response
     * @return array<int, array<string, mixed>>
     */
    private function processResponse(array $response): array
    {
        $list = $response['list'] ?? [];
        assert(is_array($list), 'List should be an array');
        /** @var array<int, array<string, mixed>> $list */

        /** @var array<int, array<string, mixed>> $feedbackList */
        $feedbackList = [];
        foreach ($list as $item) {
            assert(is_array($item), 'Item should be an array');
            assert(isset($item['record_id']) && is_string($item['record_id']), 'record_id should be string');

            $existingFeedback = $this->feedbackRepository->findOneBy([
                'wxRecordId' => $item['record_id'],
            ]);

            if (null === $existingFeedback) {
                $feedbackList[] = $item;
            }
        }

        return $feedbackList;
    }

    /**
     * @param array<int, array<string, mixed>> $feedbackList
     */
    private function saveFeedbackData(Account $account, array $feedbackList): void
    {
        foreach ($feedbackList as $item) {
            $feedback = $this->createFeedbackEntity($account, $item);
            $this->entityManager->persist($feedback);
        }

        if ([] !== $feedbackList) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param array<string, mixed> $item
     */
    private function createFeedbackEntity(Account $account, array $item): Feedback
    {
        $feedback = new Feedback();
        $feedback->setAccount($account);

        assert(isset($item['record_id']) && is_string($item['record_id']), 'record_id should be string');
        $feedback->setWxRecordId($item['record_id']);

        $feedback->setWxCreateTime($this->parseCreateTime($item));
        $feedback->setContent($this->getValidatedString($item, 'content'));
        $feedback->setPhone($this->getValidatedString($item, 'phone'));
        $feedback->setOpenid($this->getOptionalString($item, 'openid'));
        $feedback->setNickname($this->getOptionalString($item, 'nickname'));
        $feedback->setHeadUrl($this->getOptionalString($item, 'head_url'));

        $this->setFeedbackType($feedback, $item);
        $this->setMediaIds($feedback, $item);
        $this->setSystemInfo($feedback, $item);
        $this->setRawData($feedback, $item);

        return $feedback;
    }

    /**
     * @param array<string, mixed> $item
     */
    private function parseCreateTime(array $item): CarbonImmutable
    {
        assert(isset($item['create_time']) && is_string($item['create_time']), 'create_time should be string');

        return CarbonImmutable::parse($item['create_time']);
    }

    /**
     * @param array<string, mixed> $item
     */
    private function getValidatedString(array $item, string $key): string
    {
        assert(isset($item[$key]) && is_string($item[$key]), "{$key} should be string");

        return $item[$key];
    }

    /**
     * @param array<string, mixed> $item
     */
    private function getOptionalString(array $item, string $key): ?string
    {
        $value = $item[$key] ?? null;
        assert(null === $value || is_string($value), "{$key} should be string or null");

        return $value;
    }

    /**
     * @param array<string, mixed> $item
     */
    private function setFeedbackType(Feedback $feedback, array $item): void
    {
        assert(isset($item['type']) && is_string($item['type']), 'type should be string');
        $feedbackType = FeedbackType::tryFrom($item['type']);
        if (null !== $feedbackType) {
            $feedback->setFeedbackType($feedbackType);
        }
    }

    /**
     * @param array<string, mixed> $item
     */
    private function setMediaIds(Feedback $feedback, array $item): void
    {
        assert(isset($item['mediaIds']) && is_array($item['mediaIds']), 'mediaIds should be an array');
        /** @var array<string> $mediaIds */
        $mediaIds = array_values($item['mediaIds']);
        $feedback->setMediaIds($mediaIds);
    }

    /**
     * @param array<string, mixed> $item
     */
    private function setSystemInfo(Feedback $feedback, array $item): void
    {
        if (isset($item['systemInfo'])) {
            assert(is_string($item['systemInfo']), 'systemInfo should be a string');
            $feedback->setSystemInfo($item['systemInfo']);
        }
    }

    /**
     * @param array<string, mixed> $item
     */
    private function setRawData(Feedback $feedback, array $item): void
    {
        $rawData = json_encode($item);
        $feedback->setRawData(false !== $rawData ? $rawData : null);
    }

    private function handlePagination(Account $account, OutputInterface $output, int $page, int $num, int $totalNum): void
    {
        if ($page * $num < $totalNum) {
            ++$page;
            $this->request($account, $output, $page, $num);
        }
    }
}
