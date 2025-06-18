<?php

namespace WechatMiniProgramLogBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Enum\FeedbackType;
use WechatMiniProgramLogBundle\Repository\FeedbackRepository;

#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_feedback', options: ['comment' => '微信小程序-反馈记录'])]
class Feedback implements Stringable
{
    use TimestampableAware;
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $account = null;

    #[ORM\Column(length: 255, unique: true, options: ['comment' => '微信记录ID'])]
    private ?string $wxRecordId = null;

    private ?\DateTimeInterface $wxCreateTime = null;

    private ?string $content = null;

    private ?string $phone = null;

    private ?string $openid = null;

    private ?string $nickname = null;

    private ?string $headUrl = null;

    #[ORM\Column(length: 20, enumType: FeedbackType::class, options: ['comment' => '类型'])]
    private ?FeedbackType $feedbackType = null;

    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '图片'])]
    private array $mediaIds = [];

    private ?string $systemInfo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '原始数据'])]
    private ?string $rawData = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getWxRecordId(): ?string
    {
        return $this->wxRecordId;
    }

    public function setWxRecordId(string $wxRecordId): static
    {
        $this->wxRecordId = $wxRecordId;

        return $this;
    }

    public function getWxCreateTime(): ?\DateTimeInterface
    {
        return $this->wxCreateTime;
    }

    public function setWxCreateTime(\DateTimeInterface $wxCreateTime): static
    {
        $this->wxCreateTime = $wxCreateTime;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getOpenid(): ?string
    {
        return $this->openid;
    }

    public function setOpenid(?string $openid): static
    {
        $this->openid = $openid;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getHeadUrl(): ?string
    {
        return $this->headUrl;
    }

    public function setHeadUrl(?string $headUrl): static
    {
        $this->headUrl = $headUrl;

        return $this;
    }

    public function getFeedbackType(): ?FeedbackType
    {
        return $this->feedbackType;
    }

    public function setFeedbackType(FeedbackType $feedbackType): static
    {
        $this->feedbackType = $feedbackType;

        return $this;
    }

    public function getMediaIds(): array
    {
        return $this->mediaIds;
    }

    public function setMediaIds(array $mediaIds): static
    {
        $this->mediaIds = $mediaIds;

        return $this;
    }

    public function getSystemInfo(): ?string
    {
        return $this->systemInfo;
    }

    public function setSystemInfo(string $systemInfo): static
    {
        $this->systemInfo = $systemInfo;

        return $this;
    }

    public function getRawData(): ?string
    {
        return $this->rawData;
    }

    public function setRawData(?string $rawData): static
    {
        $this->rawData = $rawData;

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
    public function __toString(): string
    {
        return (string) $this->id;
    }
}
