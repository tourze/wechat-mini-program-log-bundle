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
use WechatMiniProgramLogBundle\Entity\PenaltyList;
use WechatMiniProgramLogBundle\Enum\PenaltyStatus;
use WechatMiniProgramLogBundle\Repository\PenaltyListRepository;
use WechatMiniProgramLogBundle\Request\GetWechatPenaltyListRequest;

#[AsCronTask(expression: '40 1 * * *')]
#[AsCronTask(expression: '45 13 * * *')]
#[AsCommand(name: self::NAME, description: '获取小程序交易体验分违规记录')]
#[WithMonologChannel(channel: 'wechat_mini_program_log')]
final class GetWechatPenaltyListCommand extends Command
{
    public const NAME = 'wechat-mini-program:get-penalty';

    public function __construct(
        private readonly Client $client,
        private readonly PenaltyListRepository $penaltyListRepository,
        private readonly AccountRepository $accountRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
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
        $startTime = microtime(true);
        $request = $this->createRequest($account, $offset, $limit);

        $this->logRequestStart($account, $offset, $limit);

        try {
            $response = $this->executeRequest($request, $startTime, $account, $offset, $limit);
            $this->processResponse($response, $account, $offset, $limit);
        } catch (\Exception $e) {
            $this->handleRequestError($e, $account, $offset, $limit, $startTime);
            throw $e;
        }
    }

    private function createRequest(Account $account, int $offset, int $limit): GetWechatPenaltyListRequest
    {
        $request = new GetWechatPenaltyListRequest();
        $request->setAccount($account);
        $request->setLimit($limit);
        $request->setOffset($offset);

        return $request;
    }

    private function logRequestStart(Account $account, int $offset, int $limit): void
    {
        $this->logger->info('Starting WeChat penalty list request', [
            'account_id' => $account->getId(),
            'offset' => $offset,
            'limit' => $limit,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function executeRequest(GetWechatPenaltyListRequest $request, float $startTime, Account $account, int $offset, int $limit): array
    {
        $response = $this->client->request($request);
        assert(is_array($response), 'Response should be an array');
        /** @var array<string, mixed> $response */
        $requestTime = (microtime(true) - $startTime) * 1000;
        $totalNum = $response['totalNum'] ?? 0;
        assert(is_int($totalNum), 'totalNum should be int');
        $appealList = $response['appealList'] ?? [];
        assert(is_array($appealList), 'appealList should be array');
        /** @var array<int, array<string, mixed>> $appealList */
        $currentScore = $response['currentScore'] ?? 0;
        assert(is_int($currentScore), 'currentScore should be int');

        $this->logRequestSuccess($account, $offset, $limit, $totalNum, $appealList, $currentScore, $requestTime);

        return $response;
    }

    /**
     * @param array<int, array<string, mixed>> $appealList
     */
    private function logRequestSuccess(Account $account, int $offset, int $limit, int $totalNum, array $appealList, int $currentScore, float $requestTime): void
    {
        $this->logger->info('WeChat penalty list request completed', [
            'account_id' => $account->getId(),
            'offset' => $offset,
            'limit' => $limit,
            'response_total' => $totalNum,
            'response_count' => count($appealList),
            'current_score' => $currentScore,
            'duration_ms' => round($requestTime, 2),
        ]);
    }

    private function handleRequestError(\Exception $e, Account $account, int $offset, int $limit, float $startTime): void
    {
        $requestTime = (microtime(true) - $startTime) * 1000;

        $this->logger->error('WeChat penalty list request failed', [
            'account_id' => $account->getId(),
            'offset' => $offset,
            'limit' => $limit,
            'error' => $e->getMessage(),
            'duration_ms' => round($requestTime, 2),
        ]);
    }

    /**
     * @param array<string, mixed> $response
     */
    private function processResponse(array $response, Account $account, int $offset, int $limit): void
    {
        $totalNum = $response['totalNum'] ?? 0;
        assert(is_int($totalNum), 'totalNum should be int');
        $appealList = $response['appealList'] ?? [];
        assert(is_array($appealList), 'appealList should be array');
        /** @var array<int, array<string, mixed>> $appealList */
        $currentScore = $response['currentScore'] ?? 0;
        assert(is_int($currentScore), 'currentScore should be int');

        foreach ($appealList as $item) {
            $this->processPenaltyItem($item, $currentScore);
        }

        $this->handlePagination($account, $offset, $limit, $totalNum);
    }

    /**
     * @param array<string, mixed> $item
     */
    private function processPenaltyItem(array $item, int $currentScore): void
    {
        assert(isset($item['illegalOrderId']), 'illegalOrderId should exist');

        $penalty = $this->findOrCreatePenalty($item, $currentScore);
        $this->updatePenaltyStatus($penalty, $item);
        $this->savePenalty($penalty);
    }

    /**
     * @param array<string, mixed> $item
     */
    private function findOrCreatePenalty(array $item, int $currentScore): PenaltyList
    {
        $penalty = $this->penaltyListRepository->findOneBy([
            'illegalOrderId' => $item['illegalOrderId'],
        ]);

        if (null === $penalty) {
            $penalty = $this->createNewPenalty($item, $currentScore);
        }

        return $penalty;
    }

    /**
     * @param array<string, mixed> $item
     */
    private function createNewPenalty(array $item, int $currentScore): PenaltyList
    {
        $penalty = new PenaltyList();

        $illegalOrderId = $item['illegalOrderId'] ?? null;
        assert(null === $illegalOrderId || is_string($illegalOrderId), 'illegalOrderId should be string or null');
        $penalty->setIllegalOrderId($illegalOrderId);

        $complaintOrderId = $item['complaintOrderId'] ?? null;
        assert(null === $complaintOrderId || is_string($complaintOrderId), 'complaintOrderId should be string or null');
        $penalty->setComplaintOrderId($complaintOrderId);

        assert(isset($item['illegalWording']) && is_string($item['illegalWording']), 'illegalWording should be string');
        $penalty->setIllegalWording($item['illegalWording']);

        $minusScore = $item['minusScore'] ?? 0;
        assert(is_int($minusScore), 'minusScore should be int');
        $penalty->setMinusScore($minusScore);

        assert(isset($item['illegalTime']) && is_string($item['illegalTime']), 'illegalTime should be string');
        $penalty->setIllegalTime(CarbonImmutable::parse($item['illegalTime']));

        assert(isset($item['orderId']) && is_string($item['orderId']), 'orderId should be string');
        $penalty->setOrderId($item['orderId']);

        $penalty->setCurrentScore($currentScore);

        $rawData = json_encode($item);
        $penalty->setRawData(false !== $rawData ? $rawData : null);

        return $penalty;
    }

    /**
     * @param array<string, mixed> $item
     */
    private function updatePenaltyStatus(PenaltyList $penalty, array $item): void
    {
        assert(isset($item['status']) && is_int($item['status']), 'status should be int');
        $status = PenaltyStatus::tryFrom($item['status']);

        if (null !== $status && (null === $penalty->getPenaltyStatus() || $penalty->getPenaltyStatus() !== $status)) {
            $penalty->setPenaltyStatus($status);
        }
    }

    private function savePenalty(PenaltyList $penalty): void
    {
        $this->entityManager->persist($penalty);
        $this->entityManager->flush();
    }

    private function handlePagination(Account $account, int $offset, int $limit, int $totalNum): void
    {
        if (($offset + 1) * $limit < $totalNum) {
            ++$offset;
            $this->request($account, $offset, $limit);
        }
    }
}
