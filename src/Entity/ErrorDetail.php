<?php

namespace WechatMiniProgramLogBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Repository\ErrorDetailRepository;

#[ORM\Entity(repositoryClass: ErrorDetailRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_error_detail', options: ['comment' => '错误详情'])]
#[ORM\UniqueConstraint(name: 'wechat_mini_program_error_detail_uniq', columns: ['account_id', 'date', 'open_id', 'error_msg_code'])]
class ErrorDetail implements Stringable
{
    use TimestampableAware;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $account = null;

    private ?\DateTimeInterface $date = null;

    private ?string $openId = null;

    private ?string $errorMsgCode = null;

    private ?string $errorMsg = null;

    private ?string $errorStackCode = null;

    private ?string $errorStack = null;

    private ?string $count = null;

    private ?string $sdkVersion = null;

    private ?string $clientVersion = null;

    private ?\DateTimeInterface $timeStamp = null;

    private ?string $appVersion = null;

    private ?string $ds = null;

    private ?string $osName = null;

    private ?string $pluginVersion = null;

    private ?string $appId = null;

    private ?string $deviceModel = null;

    private ?string $source = null;

    private ?string $route = null;

    private ?string $uin = null;

    private ?string $nickname = null;

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
    public function __toString(): string
    {
        return (string) $this->id;
    }
}
