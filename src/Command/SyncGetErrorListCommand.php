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
use WechatMiniProgramLogBundle\Entity\ErrorListData;
use WechatMiniProgramLogBundle\Repository\ErrorListDataRepository;
use WechatMiniProgramLogBundle\Request\GetErrorListRequest;

/**
 * 运维中心-查询错误列表
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/operation/getJsErrList.html
 */
#[AsCronTask(expression: '6 3 * * *')]
#[AsCronTask(expression: '35 11 * * *')]
#[AsCommand(name: self::NAME, description: '运维中心-查询错误列表')]
#[WithMonologChannel(channel: 'wechat_mini_program_log')]
class SyncGetErrorListCommand extends Command
{
    public const NAME = 'wechat-mini-program:sync-get-error-list';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly Client $client,
        private readonly ErrorListDataRepository $errorListDataRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        ?string $name = null,
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = CarbonImmutable::now()->startOfDay();

        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            $this->syncAccountErrorList($account, $now, $output);
        }

        return Command::SUCCESS;
    }

    private function syncAccountErrorList(Account $account, CarbonImmutable $now, OutputInterface $output): void
    {
        $request = $this->createRequest($account, $now);
        $startTime = microtime(true);

        $this->logRequestStart($account, $request);

        try {
            $response = $this->client->request($request);
            assert(is_array($response), 'Response should be an array');
            /** @var array<string, mixed> $response */
            $requestTime = (microtime(true) - $startTime) * 1000;

            $this->logRequestSuccess($account, $response, $requestTime);
            $this->processResponseData($response, $account, $now);
        } catch (\Throwable $exception) {
            $requestTime = (microtime(true) - $startTime) * 1000;
            $this->logRequestError($account, $exception, $requestTime);
            $output->writeln("同步[{$account->getId()}]错误列表出错：" . $exception);
        }
    }

    private function createRequest(Account $account, CarbonImmutable $now): GetErrorListRequest
    {
        $request = new GetErrorListRequest();
        $request->setAccount($account);
        $request->setStartTime($now->clone()->subDays(2)->startOfDay());
        $request->setEndTime($now->clone()->subDay()->endOfDay());
        $request->setErrType('0');
        $request->setAppVersion('0');
        $request->setOpenId('');
        $request->setKeyword('');
        $request->setOrderBy('uv');
        $request->setDesc('2');
        $request->setOffset(0);
        $request->setLimit(30);

        return $request;
    }

    private function logRequestStart(Account $account, GetErrorListRequest $request): void
    {
        $this->logger->info('Starting WeChat error list request', [
            'account_id' => $account->getId(),
            'start_time' => $request->getStartTime()->format('Y-m-d H:i:s'),
            'end_time' => $request->getEndTime()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @param array<string, mixed> $response
     */
    private function logRequestSuccess(Account $account, array $response, float $requestTime): void
    {
        $responseData = $response['data'] ?? [];
        assert(is_array($responseData), 'responseData should be array');

        $this->logger->info('WeChat error list request completed', [
            'account_id' => $account->getId(),
            'response_count' => count($responseData),
            'duration_ms' => round($requestTime, 2),
        ]);
    }

    private function logRequestError(Account $account, \Throwable $exception, float $requestTime): void
    {
        $this->logger->error('WeChat error list request failed', [
            'account_id' => $account->getId(),
            'error' => $exception->getMessage(),
            'duration_ms' => round($requestTime, 2),
        ]);
    }

    /**
     * @param array<string, mixed> $response
     */
    private function processResponseData(array $response, Account $account, CarbonImmutable $now): void
    {
        assert(isset($response['data']) && is_array($response['data']), 'Response data should be an array');
        assert(isset($response['openid']) && is_string($response['openid']), 'Response openid should be a string');

        /** @var array<int, array<string, mixed>> $data */
        $data = $response['data'];
        $openid = $response['openid'];

        foreach ($data as $item) {
            $this->processErrorItem($item, $openid, $account, $now);
        }
    }

    /**
     * @param array<string, mixed> $item
     */
    private function processErrorItem(array $item, string $openId, Account $account, CarbonImmutable $now): void
    {
        assert(isset($item['errorMsgMd5']) && is_string($item['errorMsgMd5']), 'errorMsgMd5 should be a string');

        $errorList = $this->findOrCreateErrorList($account, $now, $openId, $item['errorMsgMd5']);
        $this->updateErrorListFromItem($errorList, $item);

        $this->entityManager->persist($errorList);
        $this->entityManager->flush();
    }

    private function findOrCreateErrorList(Account $account, CarbonImmutable $now, string $openId, string $errorMsgMd5): ErrorListData
    {
        $errorList = $this->errorListDataRepository->findOneBy([
            'account' => $account,
            'date' => CarbonImmutable::now()->subDays(2),
            'open_id' => $openId,
            'error_msg_code' => $errorMsgMd5,
        ]);

        if (null === $errorList) {
            $errorList = new ErrorListData();
            $errorList->setOpenId($openId);
            $errorList->setAccount($account);
            $errorList->setDate($now->subDays(2));
            $errorList->setErrorMsgCode($errorMsgMd5);
        }

        return $errorList;
    }

    /**
     * @param array<string, mixed> $item
     */
    private function updateErrorListFromItem(ErrorListData $errorList, array $item): void
    {
        assert(isset($item['errorMsg']) && is_string($item['errorMsg']), 'errorMsg should be a string');
        $errorList->setErrorMsg($item['errorMsg']);

        $uv = $item['uv'] ?? 0;
        assert(is_int($uv), 'uv should be an integer');
        $errorList->setUv($uv);

        $pv = $item['pv'] ?? 0;
        assert(is_int($pv), 'pv should be an integer');
        $errorList->setPv($pv);

        assert(isset($item['errorStackMd5']) && is_string($item['errorStackMd5']), 'errorStackMd5 should be a string');
        $errorList->setErrorStackCode($item['errorStackMd5']);

        assert(isset($item['errorStack']) && is_string($item['errorStack']), 'errorStack should be a string');
        $errorList->setErrorStack($item['errorStack']);

        assert(isset($item['pvPercent']) && is_string($item['pvPercent']), 'pvPercent should be a string');
        $errorList->setPvPercent($item['pvPercent']);

        assert(isset($item['uvPercent']) && is_string($item['uvPercent']), 'uvPercent should be a string');
        $errorList->setUvPercent($item['uvPercent']);
    }
}
