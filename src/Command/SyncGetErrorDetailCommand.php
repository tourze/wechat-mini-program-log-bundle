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
use WechatMiniProgramLogBundle\Entity\ErrorDetail;
use WechatMiniProgramLogBundle\Entity\ErrorListData;
use WechatMiniProgramLogBundle\Repository\ErrorDetailRepository;
use WechatMiniProgramLogBundle\Repository\ErrorListDataRepository;
use WechatMiniProgramLogBundle\Request\GetErrorDetailRequest;

/**
 * 运维中心-查询js错误详情
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/operation/getJsErrList.html
 */
#[AsCronTask(expression: '2 4 * * *')]
#[AsCronTask(expression: '22 8 * * *')]
#[AsCommand(name: self::NAME, description: '运维中心-查询js错误详情')]
#[WithMonologChannel(channel: 'wechat_mini_program_log')]
final class SyncGetErrorDetailCommand extends Command
{
    public const NAME = 'wechat-mini-program:sync-get-error-detail';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly Client $client,
        private readonly ErrorListDataRepository $errorDetailDataRepository,
        private readonly ErrorDetailRepository $errorDetailRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        ?string $name = null,
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            $this->processAccountErrorDetails($account);
        }

        return Command::SUCCESS;
    }

    private function processAccountErrorDetails(Account $account): void
    {
        $date = CarbonImmutable::now()->subDays(2);
        $errorDetailDataItems = $this->errorDetailDataRepository->findBy(['account' => $account, 'date' => $date]);

        foreach ($errorDetailDataItems as $item) {
            assert($item instanceof ErrorListData);
            $startTime = microtime(true);
            $request = $this->createErrorDetailRequest($account, $item);

            $this->logger->info('Starting WeChat error detail request', [
                'account_id' => $account->getId(),
                'error_stack_code' => $item->getErrorStackCode(),
                'error_msg_code' => $item->getErrorMsgCode(),
                'open_id' => $item->getOpenId(),
            ]);

            try {
                $response = $this->client->request($request);
                $requestTime = (microtime(true) - $startTime) * 1000;

                assert(is_array($response), 'Response should be an array');
                $responseData = $response['data'] ?? [];
                assert(is_countable($responseData), 'Response data should be countable');

                $this->logger->info('WeChat error detail request completed', [
                    'account_id' => $account->getId(),
                    'error_stack_code' => $item->getErrorStackCode(),
                    'error_msg_code' => $item->getErrorMsgCode(),
                    'open_id' => $item->getOpenId(),
                    'response_count' => count($responseData),
                    'duration_ms' => round($requestTime, 2),
                ]);

                $this->processErrorDetailResponse($account, $response, $date);
            } catch (\Exception $e) {
                $requestTime = (microtime(true) - $startTime) * 1000;

                $this->logger->error('WeChat error detail request failed', [
                    'account_id' => $account->getId(),
                    'error_stack_code' => $item->getErrorStackCode(),
                    'error_msg_code' => $item->getErrorMsgCode(),
                    'open_id' => $item->getOpenId(),
                    'error' => $e->getMessage(),
                    'duration_ms' => round($requestTime, 2),
                ]);

                throw $e;
            }
        }
    }

    private function createErrorDetailRequest(Account $account, ErrorListData $item): GetErrorDetailRequest
    {
        $request = new GetErrorDetailRequest();
        $request->setAccount($account);
        $request->setStartTime(CarbonImmutable::now()->subDays(2));
        $request->setEndTime(CarbonImmutable::now()->subDay());
        $errorStackCode = $item->getErrorStackCode();
        $errorMsgCode = $item->getErrorMsgCode();
        $openId = $item->getOpenId();

        if (null !== $errorStackCode) {
            $request->setErrorStackCode($errorStackCode);
        }
        if (null !== $errorMsgCode) {
            $request->setErrorMsgCode($errorMsgCode);
        }
        $request->setAppVersion('0');
        $request->setSdkVersion('0');
        $request->setOsName('0');
        $request->setClientVersion('0');
        if (null !== $openId) {
            $request->setOpenId($openId);
        }
        $request->setOffset(0);
        $request->setLimit(30);
        $request->setDesc('0');

        return $request;
    }

    /**
     * @param array<mixed> $response
     */
    private function processErrorDetailResponse(Account $account, array $response, CarbonImmutable $date): void
    {
        assert(isset($response['data']) && is_array($response['data']), 'Response data should be an array');
        assert(isset($response['openid']) && is_string($response['openid']), 'Response openid should be a string');

        foreach ($response['data'] as $value) {
            assert(is_array($value), 'Value should be an array');
            assert(isset($value['errorMsgMd5']) && is_string($value['errorMsgMd5']), 'errorMsgMd5 should be a string');

            $errorDetail = $this->findOrCreateErrorDetail($account, $response['openid'], $value['errorMsgMd5'], $date);
            $this->updateErrorDetail($errorDetail, $value);
            $this->entityManager->persist($errorDetail);
            $this->entityManager->flush();
        }
    }

    private function findOrCreateErrorDetail(Account $account, string $openId, string $errorMsgCode, CarbonImmutable $date): ErrorDetail
    {
        /** @var ErrorDetail|null $errorDetail */
        $errorDetail = $this->errorDetailRepository->findOneBy([
            'account' => $account,
            'date' => $date,
            'open_id' => $openId,
            'error_msg_code' => $errorMsgCode,
        ]);

        if (null === $errorDetail) {
            $errorDetail = new ErrorDetail();
            $errorDetail->setOpenId($openId);
            $errorDetail->setAccount($account);
            $errorDetail->setDate($date);
            $errorDetail->setErrorMsgCode($errorMsgCode);
        }

        return $errorDetail;
    }

    /**
     * @param array<mixed> $value
     */
    private function updateErrorDetail(ErrorDetail $errorDetail, array $value): void
    {
        $this->setCount($errorDetail, $value);
        $this->setSdkVersion($errorDetail, $value);
        $this->setClientVersion($errorDetail, $value);
        $this->setTimeStamp($errorDetail, $value);
        $this->setAppVersion($errorDetail, $value);
        $this->setDs($errorDetail, $value);
        $this->setOsName($errorDetail, $value);
        $this->setPluginVersion($errorDetail, $value);
        $this->setAppId($errorDetail, $value);
        $this->setDeviceModel($errorDetail, $value);
        $this->setSource($errorDetail, $value);
        $this->setRoute($errorDetail, $value);
        $this->setUin($errorDetail, $value);
        $this->setNickname($errorDetail, $value);
        $this->setErrorMsg($errorDetail, $value);
        $this->setErrorStackCode($errorDetail, $value);
        $this->setErrorStack($errorDetail, $value);
    }

    /**
     * @param array<mixed> $value
     */
    private function setCount(ErrorDetail $errorDetail, array $value): void
    {
        assert(isset($value['Count']) && is_int($value['Count']), 'Count should be an integer');
        $errorDetail->setCount((string) $value['Count']);
    }

    /**
     * @param array<mixed> $value
     */
    private function setSdkVersion(ErrorDetail $errorDetail, array $value): void
    {
        assert(isset($value['sdkVersion']) && is_string($value['sdkVersion']), 'sdkVersion should be a string');
        $errorDetail->setSdkVersion($value['sdkVersion']);
    }

    /**
     * @param array<mixed> $value
     */
    private function setClientVersion(ErrorDetail $errorDetail, array $value): void
    {
        assert(isset($value['ClientVersion']) && is_string($value['ClientVersion']), 'ClientVersion should be a string');
        $errorDetail->setClientVersion($value['ClientVersion']);
    }

    /**
     * @param array<mixed> $value
     */
    private function setTimeStamp(ErrorDetail $errorDetail, array $value): void
    {
        assert(isset($value['TimeStamp']) && is_int($value['TimeStamp']), 'TimeStamp should be an integer');
        $errorDetail->setTimeStamp(new \DateTimeImmutable('@' . $value['TimeStamp']));
    }

    /**
     * @param array<mixed> $value
     */
    private function setAppVersion(ErrorDetail $errorDetail, array $value): void
    {
        assert(isset($value['appVersion']) && is_string($value['appVersion']), 'appVersion should be a string');
        $errorDetail->setAppVersion($value['appVersion']);
    }

    /**
     * @param array<mixed> $value
     */
    private function setDs(ErrorDetail $errorDetail, array $value): void
    {
        assert(isset($value['Ds']) && is_string($value['Ds']), 'Ds should be a string');
        $errorDetail->setDs($value['Ds']);
    }

    /**
     * @param array<mixed> $value
     */
    private function setOsName(ErrorDetail $errorDetail, array $value): void
    {
        assert(isset($value['OsName']) && is_string($value['OsName']), 'OsName should be a string');
        $errorDetail->setOsName($value['OsName']);
    }

    /**
     * @param array<mixed> $value
     */
    private function setPluginVersion(ErrorDetail $errorDetail, array $value): void
    {
        assert(isset($value['pluginversion']) && is_string($value['pluginversion']), 'pluginversion should be a string');
        $errorDetail->setPluginVersion($value['pluginversion']);
    }

    /**
     * @param array<mixed> $value
     */
    private function setAppId(ErrorDetail $errorDetail, array $value): void
    {
        assert(isset($value['appId']) && is_string($value['appId']), 'appId should be a string');
        $errorDetail->setAppId($value['appId']);
    }

    /**
     * @param array<mixed> $value
     */
    private function setDeviceModel(ErrorDetail $errorDetail, array $value): void
    {
        assert(isset($value['deviceModel']) && is_string($value['deviceModel']), 'deviceModel should be a string');
        $errorDetail->setDeviceModel($value['deviceModel']);
    }

    /**
     * @param array<mixed> $value
     */
    private function setSource(ErrorDetail $errorDetail, array $value): void
    {
        assert(isset($value['source']) && is_string($value['source']), 'source should be a string');
        $errorDetail->setSource($value['source']);
    }

    /**
     * @param array<mixed> $value
     */
    private function setRoute(ErrorDetail $errorDetail, array $value): void
    {
        assert(isset($value['route']) && is_string($value['route']), 'route should be a string');
        $errorDetail->setRoute($value['route']);
    }

    /**
     * @param array<mixed> $value
     */
    private function setUin(ErrorDetail $errorDetail, array $value): void
    {
        assert(isset($value['Uin']) && is_string($value['Uin']), 'Uin should be a string');
        $errorDetail->setUin($value['Uin']);
    }

    /**
     * @param array<mixed> $value
     */
    private function setNickname(ErrorDetail $errorDetail, array $value): void
    {
        assert(isset($value['nickname']) && is_string($value['nickname']), 'nickname should be a string');
        $errorDetail->setNickname($value['nickname']);
    }

    /**
     * @param array<mixed> $value
     */
    private function setErrorMsg(ErrorDetail $errorDetail, array $value): void
    {
        assert(isset($value['errorMsg']) && is_string($value['errorMsg']), 'errorMsg should be a string');
        $errorDetail->setErrorMsg($value['errorMsg']);
    }

    /**
     * @param array<mixed> $value
     */
    private function setErrorStackCode(ErrorDetail $errorDetail, array $value): void
    {
        assert(isset($value['errorStackMd5']) && is_string($value['errorStackMd5']), 'errorStackMd5 should be a string');
        $errorDetail->setErrorStackCode($value['errorStackMd5']);
    }

    /**
     * @param array<mixed> $value
     */
    private function setErrorStack(ErrorDetail $errorDetail, array $value): void
    {
        assert(isset($value['errorStack']) && is_string($value['errorStack']), 'errorStack should be a string');
        $errorDetail->setErrorStack($value['errorStack']);
    }
}
