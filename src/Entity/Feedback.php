<?php

namespace WechatMiniProgramLogBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Attribute\CreateTimeColumn;
use Tourze\DoctrineTimestampBundle\Attribute\UpdateTimeColumn;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Filter\Filterable;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Enum\FeedbackType;
use WechatMiniProgramLogBundle\Repository\FeedbackRepository;

#[AsPermission(title: '微信小程序-反馈记录')]
#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_feedback', options: ['comment' => '微信小程序-反馈记录'])]
class Feedback
{
    #[ExportColumn]
    #[ListColumn(order: -1, sorter: true)]
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

    #[ListColumn]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['comment' => '投诉创建时间'])]
    private ?\DateTimeInterface $wxCreateTime = null;

    #[ListColumn]
    #[ORM\Column(length: 255, options: ['comment' => '内容'])]
    private ?string $content = null;

    #[ListColumn]
    #[ORM\Column(length: 100, options: ['comment' => '联系方式'])]
    private ?string $phone = null;

    #[ListColumn]
    #[ORM\Column(length: 255, nullable: true, options: ['comment' => 'openid'])]
    private ?string $openid = null;

    #[ListColumn]
    #[ORM\Column(length: 100, nullable: true, options: ['comment' => '用户昵称'])]
    private ?string $nickname = null;

    #[ListColumn]
    #[ORM\Column(length: 255, nullable: true, options: ['comment' => '用户头像'])]
    private ?string $headUrl = null;

    #[ORM\Column(length: 20, enumType: FeedbackType::class, options: ['comment' => '类型'])]
    private ?FeedbackType $feedbackType = null;

    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '图片'])]
    private array $mediaIds = [];

    #[ListColumn]
    #[ORM\Column(length: 255, nullable: true, options: ['comment' => '系统信息'])]
    private ?string $systemInfo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '原始数据'])]
    private ?string $rawData = null;

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
