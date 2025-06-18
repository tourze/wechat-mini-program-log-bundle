<?php

namespace WechatMiniProgramLogBundle\Command;

use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramLogBundle\Entity\ErrorDetail;
use WechatMiniProgramLogBundle\Repository\ErrorDetailRepository;
use WechatMiniProgramLogBundle\Repository\ErrorListDataRepository;
use WechatMiniProgramLogBundle\Request\GetErrorDetailRequest;

/**
 * 运维中心-查询js错误详情
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/operation/getJsErrList.html
 */
#[AsCronTask('2 4 * * *')]
#[AsCronTask('22 8 * * *')]
#[AsCommand(name: 'wechat:official-account:SyncGetErrorDetailCommand', description: '运维中心-查询js错误详情')]
class SyncGetErrorDetailCommand extends Command
{
    
    public const NAME = 'wechat:official-account:SyncGetErrorDetailCommand';
public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly Client $client,
        private readonly ErrorListDataRepository $errorDetailDataRepository,
        private readonly ErrorDetailRepository $errorDetailRepository,
        private readonly EntityManagerInterface $entityManager,
        ?string $name = null,
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            foreach ($this->errorDetailDataRepository->findBy(['account' => $account, 'date' => Carbon::now()->subDays(2)]) as $item) {
                $request = new GetErrorDetailRequest();
                $request->setAccount($account);
                $request->setStartTime(Carbon::now()->subDays(2));
                $request->setEndTime(Carbon::now()->subDay());
                $request->setErrorStackCode($item->getErrorStackCode());
                $request->setErrorMsgCode($item->getErrorMsgCode());
                $request->setAppVersion('0');
                $request->setSdkVersion('0');
                $request->setOsName('0');
                $request->setClientVersion('0');
                $request->setOpenId($item->getOpenId());
                $request->setOffset(0);
                $request->setLimit(30);
                $request->setDesc('0');
                $response = $this->client->request($request);

                foreach ($response['data'] as $value) {
                    $errorDetail = $this->errorDetailRepository->findOneBy([
                        'account' => $account,
                        'date' => Carbon::now()->subDays(2),
                        'open_id' => $response['openid'],
                        'error_msg_code' => $value['errorMsgMd5'],
                    ]);
                    if (!$errorDetail) {
                        $errorDetail = new ErrorDetail();
                        $errorDetail->setOpenId($response['openid']);
                        $errorDetail->setAccount($account);
                        $errorDetail->setDate(Carbon::now()->subDays(2));
                        $errorDetail->setErrorMsgCode($value['errorMsgMd5']);
                    }
                    $errorDetail->setCount($value['Count']);
                    $errorDetail->setSdkVersion($value['sdkVersion']);
                    $errorDetail->setClientVersion($value['ClientVersion']);
                    $errorDetail->setTimeStamp($value['TimeStamp']);
                    $errorDetail->setAppVersion($value['appVersion']);
                    $errorDetail->setDs($value['Ds']);
                    $errorDetail->setOsName($value['OsName']);
                    $errorDetail->setPluginVersion($value['pluginversion']);
                    $errorDetail->setAppId($value['appId']);
                    $errorDetail->setDeviceModel($value['deviceModel']);
                    $errorDetail->setSource($value['source']);
                    $errorDetail->setRoute($value['route']);
                    $errorDetail->setUin($value['Uin']);
                    $errorDetail->setNickname($value['nickname']);
                    $errorDetail->setErrorMsg($value['errorMsg']);
                    $errorDetail->setErrorStackCode($value['errorStackMd5']);
                    $errorDetail->setErrorStack($value['errorStack']);
                    $this->entityManager->persist($errorDetail);
                    $this->entityManager->flush();
                }
            }
        }

        return Command::SUCCESS;
    }
}
