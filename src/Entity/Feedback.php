<?php

namespace WechatMiniProgramLogBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Enum\FeedbackType;
use WechatMiniProgramLogBundle\Repository\FeedbackRepository;

#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_feedback', options: ['comment' => '微信小程序-反馈记录'])]
class Feedback implements \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: '账户不能为空')]
    private ?Account $account = null;

    #[ORM\Column(length: 255, unique: true, options: ['comment' => '微信记录ID'])]
    #[Assert\NotBlank(message: '微信记录ID不能为空')]
    #[Assert\Length(max: 255, maxMessage: '微信记录ID长度不能超过{{ limit }}个字符')]
    private ?string $wxRecordId = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['comment' => '微信创建时间'])]
    #[Assert\Type(type: \DateTimeInterface::class, message: '微信创建时间格式无效')]
    private ?\DateTimeInterface $wxCreateTime = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '反馈内容'])]
    #[Assert\Length(max: 65535, maxMessage: '反馈内容长度不能超过{{ limit }}个字符')]
    private ?string $content = null;

    #[ORM\Column(length: 20, nullable: true, options: ['comment' => '手机号'])]
    #[Assert\Length(max: 20, maxMessage: '手机号长度不能超过{{ limit }}个字符')]
    #[Assert\Regex(pattern: '/^1[3-9]\d{9}$/', message: '手机号格式不正确')]
    #[Assert\Type(type: 'string', message: '手机号必须是字符串')]
    private ?string $phone = null;

    #[ORM\Column(length: 100, nullable: true, options: ['comment' => 'OpenID'])]
    #[Assert\Length(max: 100, maxMessage: 'OpenID长度不能超过{{ limit }}个字符')]
    private ?string $openid = null;

    #[ORM\Column(length: 100, nullable: true, options: ['comment' => '昵称'])]
    #[Assert\Length(max: 100, maxMessage: '昵称长度不能超过{{ limit }}个字符')]
    private ?string $nickname = null;

    #[ORM\Column(length: 500, nullable: true, options: ['comment' => '头像URL'])]
    #[Assert\Length(max: 500, maxMessage: '头像URL长度不能超过{{ limit }}个字符')]
    #[Assert\Url(message: '头像URL格式无效')]
    private ?string $headUrl = null;

    #[ORM\Column(length: 20, enumType: FeedbackType::class, options: ['comment' => '类型'])]
    #[Assert\NotNull(message: '反馈类型不能为空')]
    #[Assert\Choice(callback: [FeedbackType::class, 'cases'], message: '反馈类型选择无效')]
    private ?FeedbackType $feedbackType = null;

    /**
     * @var array<string>
     */
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '图片'])]
    #[Assert\Type(type: 'array', message: '媒体ID必须是数组')]
    private array $mediaIds = [];

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '系统信息'])]
    #[Assert\Length(max: 65535, maxMessage: '系统信息长度不能超过{{ limit }}个字符')]
    private ?string $systemInfo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '原始数据'])]
    #[Assert\Length(max: 65535, maxMessage: '原始数据长度不能超过{{ limit }}个字符')]
    private ?string $rawData = null;

    public function getWxRecordId(): ?string
    {
        return $this->wxRecordId;
    }

    public function setWxRecordId(string $wxRecordId): void
    {
        $this->wxRecordId = $wxRecordId;
    }

    public function getWxCreateTime(): ?\DateTimeInterface
    {
        return $this->wxCreateTime;
    }

    public function setWxCreateTime(\DateTimeInterface $wxCreateTime): void
    {
        $this->wxCreateTime = $wxCreateTime;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getOpenid(): ?string
    {
        return $this->openid;
    }

    public function setOpenid(?string $openid): void
    {
        $this->openid = $openid;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): void
    {
        $this->nickname = $nickname;
    }

    public function getHeadUrl(): ?string
    {
        return $this->headUrl;
    }

    public function setHeadUrl(?string $headUrl): void
    {
        $this->headUrl = $headUrl;
    }

    public function getFeedbackType(): ?FeedbackType
    {
        return $this->feedbackType;
    }

    public function setFeedbackType(FeedbackType $feedbackType): void
    {
        $this->feedbackType = $feedbackType;
    }

    /**
     * @return array<string>
     */
    public function getMediaIds(): array
    {
        return $this->mediaIds;
    }

    /**
     * @param array<string> $mediaIds
     */
    public function setMediaIds(array $mediaIds): void
    {
        $this->mediaIds = $mediaIds;
    }

    public function getSystemInfo(): ?string
    {
        return $this->systemInfo;
    }

    public function setSystemInfo(string $systemInfo): void
    {
        $this->systemInfo = $systemInfo;
    }

    public function getRawData(): ?string
    {
        return $this->rawData;
    }

    public function setRawData(?string $rawData): void
    {
        $this->rawData = $rawData;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
