<?php

namespace WechatMiniProgramLogBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineTimestampBundle\Attribute\CreateTimeColumn;
use Tourze\DoctrineTimestampBundle\Attribute\UpdateTimeColumn;
use Tourze\EasyAdmin\Attribute\Action\Deletable;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Filter\Filterable;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Repository\ErrorDetailRepository;

#[AsPermission(title: '错误详情')]
#[Deletable]
#[ORM\Entity(repositoryClass: ErrorDetailRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_error_detail', options: ['comment' => '错误详情'])]
#[ORM\UniqueConstraint(name: 'wechat_mini_program_error_detail_uniq', columns: ['account_id', 'date', 'open_id', 'error_msg_code'])]
class ErrorDetail
{
    #[ListColumn(order: -1)]
    #[ExportColumn]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    #[ListColumn]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $account = null;

    #[ListColumn]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $openId = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $errorMsgCode = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $errorMsg = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $errorStackCode = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $errorStack = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $count = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $sdkVersion = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $clientVersion = null;

    #[ListColumn]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $timeStamp = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $appVersion = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $ds = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $osName = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $pluginVersion = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $appId = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $deviceModel = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $source = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $route = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $uin = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $nickname = null;

    #[Filterable]
    #[IndexColumn]
    #[ListColumn(order: 98, sorter: true)]
    #[ExportColumn]
    #[CreateTimeColumn]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '创建时间'])]
    private ?\DateTimeInterface $createTime = null;

    #[UpdateTimeColumn]
    #[ListColumn(order: 99, sorter: true)]
    #[Filterable]
    #[ExportColumn]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '更新时间'])]
    private ?\DateTimeInterface $updateTime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getUin(): ?string
    {
        return $this->uin;
    }

    public function setUin(string $uin): static
    {
        $this->uin = $uin;

        return $this;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(string $route): static
    {
        $this->route = $route;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): static
    {
        $this->source = $source;

        return $this;
    }

    public function getDeviceModel(): ?string
    {
        return $this->deviceModel;
    }

    public function setDeviceModel(string $deviceModel): static
    {
        $this->deviceModel = $deviceModel;

        return $this;
    }

    public function getAppId(): ?string
    {
        return $this->appId;
    }

    public function setAppId(string $appId): static
    {
        $this->appId = $appId;

        return $this;
    }

    public function getPluginVersion(): ?string
    {
        return $this->pluginVersion;
    }

    public function setPluginVersion(string $pluginVersion): static
    {
        $this->pluginVersion = $pluginVersion;

        return $this;
    }

    public function getOsName(): ?string
    {
        return $this->osName;
    }

    public function setOsName(string $osName): static
    {
        $this->osName = $osName;

        return $this;
    }

    public function getDs(): ?string
    {
        return $this->ds;
    }

    public function setDs(string $ds): static
    {
        $this->ds = $ds;

        return $this;
    }

    public function getAppVersion(): ?string
    {
        return $this->appVersion;
    }

    public function setAppVersion(string $appVersion): static
    {
        $this->appVersion = $appVersion;

        return $this;
    }

    public function getTimeStamp(): ?\DateTimeInterface
    {
        return $this->timeStamp;
    }

    public function setTimeStamp(\DateTimeInterface $timeStamp): static
    {
        $this->timeStamp = $timeStamp;

        return $this;
    }

    public function getClientVersion(): ?string
    {
        return $this->clientVersion;
    }

    public function setClientVersion(string $clientVersion): static
    {
        $this->clientVersion = $clientVersion;

        return $this;
    }

    public function getSdkVersion(): ?string
    {
        return $this->sdkVersion;
    }

    public function setSdkVersion(string $sdkVersion): static
    {
        $this->sdkVersion = $sdkVersion;

        return $this;
    }

    public function getCount(): ?string
    {
        return $this->count;
    }

    public function setCount(string $count): static
    {
        $this->count = $count;

        return $this;
    }

    public function getErrorStack(): ?string
    {
        return $this->errorStack;
    }

    public function setErrorStack(string $errorStack): static
    {
        $this->errorStack = $errorStack;

        return $this;
    }

    public function getErrorStackCode(): ?string
    {
        return $this->errorStackCode;
    }

    public function setErrorStackCode(string $errorStackCode): static
    {
        $this->errorStackCode = $errorStackCode;

        return $this;
    }

    public function getErrorMsg(): ?string
    {
        return $this->errorMsg;
    }

    public function setErrorMsg(string $errorMsg): static
    {
        $this->errorMsg = $errorMsg;

        return $this;
    }

    public function getErrorMsgCode(): ?string
    {
        return $this->errorMsgCode;
    }

    public function setErrorMsgCode(string $errorMsgCode): static
    {
        $this->errorMsgCode = $errorMsgCode;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getOpenId(): ?string
    {
        return $this->openId;
    }

    public function setOpenId(string $openId): static
    {
        $this->openId = $openId;

        return $this;
    }

    public function setCreateTime(?\DateTimeInterface $createdAt): void
    {
        $this->createTime = $createdAt;
    }

    public function getCreateTime(): ?\DateTimeInterface
    {
        return $this->createTime;
    }

    public function setUpdateTime(?\DateTimeInterface $updateTime): void
    {
        $this->updateTime = $updateTime;
    }

    public function getUpdateTime(): ?\DateTimeInterface
    {
        return $this->updateTime;
    }
}
