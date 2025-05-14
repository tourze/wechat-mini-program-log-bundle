<?php

namespace WechatMiniProgramLogBundle\Tests\Command;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramLogBundle\Command\SyncGetErrorDetailCommand;
use WechatMiniProgramLogBundle\Entity\ErrorDetail;
use WechatMiniProgramLogBundle\Entity\ErrorListData;
use WechatMiniProgramLogBundle\Repository\ErrorDetailRepository;
use WechatMiniProgramLogBundle\Repository\ErrorListDataRepository;
use WechatMiniProgramLogBundle\Request\GetErrorDetailRequest;

class SyncGetErrorDetailCommandTest extends TestCase
{
    private SyncGetErrorDetailCommand $command;
    private MockObject $accountRepository;
    private MockObject $client;
    private MockObject $errorDetailDataRepository;
    private MockObject $errorDetailRepository;
    private MockObject $entityManager;

    protected function setUp(): void
    {
        $this->accountRepository = $this->createMock(AccountRepository::class);
        $this->client = $this->createMock(Client::class);
        $this->errorDetailDataRepository = $this->createMock(ErrorListDataRepository::class);
        $this->errorDetailRepository = $this->createMock(ErrorDetailRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->command = new SyncGetErrorDetailCommand(
            $this->accountRepository,
            $this->client,
            $this->errorDetailDataRepository,
            $this->errorDetailRepository,
            $this->entityManager
        );
    }

    /**
     * 使用反射访问protected的execute方法
     */
    private function executeCommand(InputInterface $input, OutputInterface $output): int
    {
        $reflection = new ReflectionClass(SyncGetErrorDetailCommand::class);
        $method = $reflection->getMethod('execute');
        $method->setAccessible(true);
        return $method->invoke($this->command, $input, $output);
    }

    public function testExecute_WithValidAccounts_ShouldSucceed(): void
    {
        // 模拟数据
        $account = $this->createMock(Account::class);
        $errorListData = $this->createMock(ErrorListData::class);
        $errorListData->method('getErrorStackCode')->willReturn('stack_code_123');
        $errorListData->method('getErrorMsgCode')->willReturn('msg_code_123');
        $errorListData->method('getOpenId')->willReturn('open_id_123');

        // 设置返回有效的账号和错误列表数据
        $this->accountRepository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([$account]);

        $this->errorDetailDataRepository->expects($this->once())
            ->method('findBy')
            ->willReturn([$errorListData]);

        // 设置API响应
        $apiResponse = [
            'data' => [
                [
                    'errorMsgMd5' => 'msg_code_123',
                    'Count' => '5',
                    'sdkVersion' => '1.0.0',
                    'ClientVersion' => '2.0.0',
                    'TimeStamp' => new DateTime('2023-01-01 12:00:00'),
                    'appVersion' => '3.0.0',
                    'Ds' => 'test_ds',
                    'OsName' => 'iOS',
                    'pluginversion' => '1.2.3',
                    'appId' => 'wx123456789',
                    'deviceModel' => 'iPhone X',
                    'source' => 'test_source',
                    'route' => 'test_route',
                    'Uin' => 'test_uin',
                    'nickname' => 'test_nickname',
                    'errorMsg' => 'test error message',
                    'errorStackMd5' => 'stack_code_123',
                    'errorStack' => 'test error stack'
                ]
            ],
            'openid' => 'open_id_123'
        ];

        // 设置Client响应
        $this->client->expects($this->once())
            ->method('request')
            ->with($this->isInstanceOf(GetErrorDetailRequest::class))
            ->willReturn($apiResponse);

        // 设置错误详情查询和保存
        $this->errorDetailRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null); // 返回null表示不存在该记录，需要创建新的

        // 验证实体是否被持久化
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(ErrorDetail::class));

        $this->entityManager->expects($this->once())
            ->method('flush');

        // 创建模拟输入输出对象
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        // 执行命令
        $result = $this->executeCommand($input, $output);

        // 验证结果
        $this->assertEquals(0, $result); // 0表示成功
    }

    public function testExecute_WithNoAccounts_ShouldDoNothing(): void
    {
        // 设置返回空账号列表
        $this->accountRepository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([]);

        // 确保不会调用其他方法
        $this->errorDetailDataRepository->expects($this->never())
            ->method('findBy');
        $this->client->expects($this->never())
            ->method('request');
        $this->errorDetailRepository->expects($this->never())
            ->method('findOneBy');
        $this->entityManager->expects($this->never())
            ->method('persist');
        $this->entityManager->expects($this->never())
            ->method('flush');

        // 创建模拟输入输出对象
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        // 执行命令
        $result = $this->executeCommand($input, $output);

        // 验证结果
        $this->assertEquals(0, $result); // 0表示成功
    }

    public function testExecute_WithExistingData_ShouldUpdateData(): void
    {
        // 模拟数据
        $account = $this->createMock(Account::class);
        $errorListData = $this->createMock(ErrorListData::class);
        $errorListData->method('getErrorStackCode')->willReturn('stack_code_123');
        $errorListData->method('getErrorMsgCode')->willReturn('msg_code_123');
        $errorListData->method('getOpenId')->willReturn('open_id_123');

        $existingErrorDetail = $this->createMock(ErrorDetail::class);
        
        // 设置方法预期，确保更新方法被调用
        $existingErrorDetail->expects($this->once())->method('setCount');
        $existingErrorDetail->expects($this->once())->method('setSdkVersion');
        $existingErrorDetail->expects($this->once())->method('setClientVersion');
        $existingErrorDetail->expects($this->once())->method('setTimeStamp');
        $existingErrorDetail->expects($this->once())->method('setAppVersion');
        $existingErrorDetail->expects($this->once())->method('setDs');
        $existingErrorDetail->expects($this->once())->method('setOsName');
        $existingErrorDetail->expects($this->once())->method('setPluginVersion');
        $existingErrorDetail->expects($this->once())->method('setAppId');
        $existingErrorDetail->expects($this->once())->method('setDeviceModel');
        $existingErrorDetail->expects($this->once())->method('setSource');
        $existingErrorDetail->expects($this->once())->method('setRoute');
        $existingErrorDetail->expects($this->once())->method('setUin');
        $existingErrorDetail->expects($this->once())->method('setNickname');
        $existingErrorDetail->expects($this->once())->method('setErrorMsg');
        $existingErrorDetail->expects($this->once())->method('setErrorStackCode');
        $existingErrorDetail->expects($this->once())->method('setErrorStack');

        // 设置返回有效的账号和错误列表数据
        $this->accountRepository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([$account]);

        $this->errorDetailDataRepository->expects($this->once())
            ->method('findBy')
            ->willReturn([$errorListData]);

        // 设置API响应
        $apiResponse = [
            'data' => [
                [
                    'errorMsgMd5' => 'msg_code_123',
                    'Count' => '5',
                    'sdkVersion' => '1.0.0',
                    'ClientVersion' => '2.0.0',
                    'TimeStamp' => new DateTime('2023-01-01 12:00:00'),
                    'appVersion' => '3.0.0',
                    'Ds' => 'test_ds',
                    'OsName' => 'iOS',
                    'pluginversion' => '1.2.3',
                    'appId' => 'wx123456789',
                    'deviceModel' => 'iPhone X',
                    'source' => 'test_source',
                    'route' => 'test_route',
                    'Uin' => 'test_uin',
                    'nickname' => 'test_nickname',
                    'errorMsg' => 'test error message',
                    'errorStackMd5' => 'stack_code_123',
                    'errorStack' => 'test error stack'
                ]
            ],
            'openid' => 'open_id_123'
        ];

        // 设置Client响应
        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($apiResponse);

        // 设置错误详情查询返回已存在的记录
        $this->errorDetailRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn($existingErrorDetail);

        // 验证实体是否被持久化
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($existingErrorDetail);

        $this->entityManager->expects($this->once())
            ->method('flush');

        // 创建模拟输入输出对象
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        // 执行命令
        $result = $this->executeCommand($input, $output);

        // 验证结果
        $this->assertEquals(0, $result); // 0表示成功
    }
} 