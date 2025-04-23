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
use WechatMiniProgramLogBundle\Entity\ErrorListData;
use WechatMiniProgramLogBundle\Repository\ErrorListDataRepository;
use WechatMiniProgramLogBundle\Request\GetErrorListRequest;

/**
 * 运维中心-查询错误列表
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/operation/getJsErrList.html
 */
#[AsCronTask('6 3 * * *')]
#[AsCronTask('35 11 * * *')]
#[AsCommand(name: 'wechat:official-account:SyncGetErrorListCommand', description: '运维中心-查询错误列表')]
class SyncGetErrorListCommand extends Command
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly Client $client,
        private readonly ErrorListDataRepository $errorListDataRepository,
        private readonly EntityManagerInterface $entityManager,
        ?string $name = null,
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = Carbon::now()->startOfDay();

        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
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

            try {
                $response = $this->client->request($request);
            } catch (\Throwable $exception) {
                $output->writeln("同步[{$account->getId()}]错误列表出错：" . $exception);
                continue;
            }

            foreach ($response['data'] as $item) {
                $errorList = $this->errorListDataRepository->findOneBy([
                    'account' => $account,
                    'date' => Carbon::now()->subDays(2),
                    'open_id' => $response['openid'],
                    'error_msg_code' => $item['errorMsgMd5'],
                ]);
                if (!$errorList) {
                    $errorList = new ErrorListData();
                    $errorList->setOpenId($response['openid']);
                    $errorList->setAccount($account);
                    $errorList->setDate($now->subDays(2));
                    $errorList->setErrorMsgCode($item['errorMsgMd5']);
                }
                $errorList->setErrorMsg($item['errorMsg']);
                $errorList->setUv($item['uv']);
                $errorList->setPv($item['pv']);
                $errorList->setErrorStackCode($item['errorStackMd5']);
                $errorList->setErrorStack($item['errorStack']);
                $errorList->setPvPercent($item['pvPercent']);
                $errorList->setUvPercent($item['uvPercent']);
                $this->entityManager->persist($errorList);
                $this->entityManager->flush();
            }
        }

        return Command::SUCCESS;
    }
}
