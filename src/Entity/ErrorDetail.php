<?php

namespace WechatMiniProgramLogBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Repository\ErrorDetailRepository;

#[ORM\Entity(repositoryClass: ErrorDetailRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_error_detail', options: ['comment' => '错误详情'])]
#[ORM\UniqueConstraint(name: 'wechat_mini_program_error_detail_uniq', columns: ['account_id', 'date', 'open_id', 'error_msg_code'])]
class ErrorDetail implements \Stringable
{
    use TimestampableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private int $id = 0;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: '账户不能为空')]
    private ?Account $account = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true, options: ['comment' => '日期'])]
    #[Assert\Type(type: \DateTimeInterface::class, message: '日期格式无效')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 100, nullable: true, options: ['comment' => 'OpenID'])]
    #[Assert\Length(max: 100, maxMessage: 'OpenID长度不能超过{{ limit }}个字符')]
    private ?string $openId = null;

    #[ORM\Column(length: 50, nullable: true, options: ['comment' => '错误消息代码'])]
    #[Assert\Length(max: 50, maxMessage: '错误消息代码长度不能超过{{ limit }}个字符')]
    private ?string $errorMsgCode = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '错误消息'])]
    #[Assert\Length(max: 65535, maxMessage: '错误消息长度不能超过{{ limit }}个字符')]
    private ?string $errorMsg = null;

    #[ORM\Column(length: 50, nullable: true, options: ['comment' => '错误堆栈代码'])]
    #[Assert\Length(max: 50, maxMessage: '错误堆栈代码长度不能超过{{ limit }}个字符')]
    private ?string $errorStackCode = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '错误堆栈'])]
    #[Assert\Length(max: 65535, maxMessage: '错误堆栈长度不能超过{{ limit }}个字符')]
    private ?string $errorStack = null;

    #[ORM\Column(length: 20, nullable: true, options: ['comment' => '计数'])]
    #[Assert\Length(max: 20, maxMessage: '计数长度不能超过{{ limit }}个字符')]
    private ?string $count = null;

    #[ORM\Column(length: 20, nullable: true, options: ['comment' => 'SDK版本'])]
    #[Assert\Length(max: 20, maxMessage: 'SDK版本长度不能超过{{ limit }}个字符')]
    private ?string $sdkVersion = null;

    #[ORM\Column(length: 20, nullable: true, options: ['comment' => '客户端版本'])]
    #[Assert\Length(max: 20, maxMessage: '客户端版本长度不能超过{{ limit }}个字符')]
    private ?string $clientVersion = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['comment' => '时间戳'])]
    #[Assert\Type(type: \DateTimeInterface::class, message: '时间戳格式无效')]
    private ?\DateTimeInterface $timeStamp = null;

    #[ORM\Column(length: 20, nullable: true, options: ['comment' => '应用版本'])]
    #[Assert\Length(max: 20, maxMessage: '应用版本长度不能超过{{ limit }}个字符')]
    private ?string $appVersion = null;

    #[ORM\Column(length: 20, nullable: true, options: ['comment' => 'DS'])]
    #[Assert\Length(max: 20, maxMessage: 'DS长度不能超过{{ limit }}个字符')]
    private ?string $ds = null;

    #[ORM\Column(length: 50, nullable: true, options: ['comment' => '操作系统名称'])]
    #[Assert\Length(max: 50, maxMessage: '操作系统名称长度不能超过{{ limit }}个字符')]
    private ?string $osName = null;

    #[ORM\Column(length: 20, nullable: true, options: ['comment' => '插件版本'])]
    #[Assert\Length(max: 20, maxMessage: '插件版本长度不能超过{{ limit }}个字符')]
    private ?string $pluginVersion = null;

    #[ORM\Column(length: 50, nullable: true, options: ['comment' => '应用ID'])]
    #[Assert\Length(max: 50, maxMessage: '应用ID长度不能超过{{ limit }}个字符')]
    private ?string $appId = null;

    #[ORM\Column(length: 100, nullable: true, options: ['comment' => '设备型号'])]
    #[Assert\Length(max: 100, maxMessage: '设备型号长度不能超过{{ limit }}个字符')]
    private ?string $deviceModel = null;

    #[ORM\Column(length: 50, nullable: true, options: ['comment' => '来源'])]
    #[Assert\Length(max: 50, maxMessage: '来源长度不能超过{{ limit }}个字符')]
    private ?string $source = null;

    #[ORM\Column(length: 200, nullable: true, options: ['comment' => '路由'])]
    #[Assert\Length(max: 200, maxMessage: '路由长度不能超过{{ limit }}个字符')]
    private ?string $route = null;

    #[ORM\Column(length: 50, nullable: true, options: ['comment' => 'UIN'])]
    #[Assert\Length(max: 50, maxMessage: 'UIN长度不能超过{{ limit }}个字符')]
    private ?string $uin = null;

    #[ORM\Column(length: 100, nullable: true, options: ['comment' => '昵称'])]
    #[Assert\Length(max: 100, maxMessage: '昵称长度不能超过{{ limit }}个字符')]
    private ?string $nickname = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    public function getUin(): ?string
    {
        return $this->uin;
    }

    public function setUin(string $uin): void
    {
        $this->uin = $uin;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(string $route): void
    {
        $this->route = $route;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): void
    {
        $this->source = $source;
    }

    public function getDeviceModel(): ?string
    {
        return $this->deviceModel;
    }

    public function setDeviceModel(string $deviceModel): void
    {
        $this->deviceModel = $deviceModel;
    }

    public function getAppId(): ?string
    {
        return $this->appId;
    }

    public function setAppId(string $appId): void
    {
        $this->appId = $appId;
    }

    public function getPluginVersion(): ?string
    {
        return $this->pluginVersion;
    }

    public function setPluginVersion(string $pluginVersion): void
    {
        $this->pluginVersion = $pluginVersion;
    }

    public function getOsName(): ?string
    {
        return $this->osName;
    }

    public function setOsName(string $osName): void
    {
        $this->osName = $osName;
    }

    public function getDs(): ?string
    {
        return $this->ds;
    }

    public function setDs(string $ds): void
    {
        $this->ds = $ds;
    }

    public function getAppVersion(): ?string
    {
        return $this->appVersion;
    }

    public function setAppVersion(string $appVersion): void
    {
        $this->appVersion = $appVersion;
    }

    public function getTimeStamp(): ?\DateTimeInterface
    {
        return $this->timeStamp;
    }

    public function setTimeStamp(\DateTimeInterface $timeStamp): void
    {
        $this->timeStamp = $timeStamp;
    }

    public function getClientVersion(): ?string
    {
        return $this->clientVersion;
    }

    public function setClientVersion(string $clientVersion): void
    {
        $this->clientVersion = $clientVersion;
    }

    public function getSdkVersion(): ?string
    {
        return $this->sdkVersion;
    }

    public function setSdkVersion(string $sdkVersion): void
    {
        $this->sdkVersion = $sdkVersion;
    }

    public function getCount(): ?string
    {
        return $this->count;
    }

    public function setCount(string $count): void
    {
        $this->count = $count;
    }

    public function getErrorStack(): ?string
    {
        return $this->errorStack;
    }

    public function setErrorStack(string $errorStack): void
    {
        $this->errorStack = $errorStack;
    }

    public function getErrorStackCode(): ?string
    {
        return $this->errorStackCode;
    }

    public function setErrorStackCode(string $errorStackCode): void
    {
        $this->errorStackCode = $errorStackCode;
    }

    public function getErrorMsg(): ?string
    {
        return $this->errorMsg;
    }

    public function setErrorMsg(string $errorMsg): void
    {
        $this->errorMsg = $errorMsg;
    }

    public function getErrorMsgCode(): ?string
    {
        return $this->errorMsgCode;
    }

    public function setErrorMsgCode(string $errorMsgCode): void
    {
        $this->errorMsgCode = $errorMsgCode;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getOpenId(): ?string
    {
        return $this->openId;
    }

    public function setOpenId(string $openId): void
    {
        $this->openId = $openId;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
