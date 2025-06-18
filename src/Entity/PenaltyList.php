<?php

namespace WechatMiniProgramLogBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use WechatMiniProgramLogBundle\Enum\PenaltyStatus;
use WechatMiniProgramLogBundle\Repository\PenaltyListRepository;

/**
 * 小程序交易体验分违规记录
 */
#[ORM\Entity(repositoryClass: PenaltyListRepository::class)]
#[ORM\Table(name: 'wechat_penalty_list', options: ['comment' => '小程序交易体验分违规记录'])]
class PenaltyList
{
    use TimestampableAware;
    #[ExportColumn]
    #[ListColumn(order: -1, sorter: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[ListColumn]
    #[ORM\Column(length: 255, unique: true, options: ['comment' => '扣分记录ID'])]
    private ?string $illegalOrderId = null;

    #[ListColumn]
    #[ORM\Column(length: 100, options: ['comment' => '投诉单ID'])]
    private ?string $complaintOrderId = null;

    #[ListColumn]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['comment' => '扣分记录创建时间'])]
    private ?\DateTimeInterface $illegalTime = null;

    #[ListColumn]
    #[ORM\Column(length: 255, options: ['comment' => '违规行为'])]
    private ?string $illegalWording = null;

    #[ListColumn]
    #[ORM\Column(length: 20, enumType: PenaltyStatus::class, options: ['comment' => '扣分记录状态'])]
    private ?PenaltyStatus $penaltyStatus = null;

    #[ListColumn]
    #[ORM\Column(options: ['comment' => '扣除分数'])]
    private ?int $minusScore = null;

    #[ListColumn]
    #[ORM\Column(length: 100, nullable: true, options: ['comment' => '订单号'])]
    private ?string $orderId = null;

    #[ListColumn]
    #[ORM\Column(nullable: true, options: ['comment' => '当前小程序的交易体验分'])]
    private ?int $currentScore = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '原始数据'])]
    private ?string $rawData = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIllegalOrderId(): ?string
    {
        return $this->illegalOrderId;
    }

    public function setIllegalOrderId(string $illegalOrderId): static
    {
        $this->illegalOrderId = $illegalOrderId;

        return $this;
    }

    public function getIllegalTime(): ?\DateTimeInterface
    {
        return $this->illegalTime;
    }

    public function setIllegalTime(\DateTimeInterface $illegalTime): static
    {
        $this->illegalTime = $illegalTime;

        return $this;
    }

    public function getIllegalWording(): ?string
    {
        return $this->illegalWording;
    }

    public function setIllegalWording(string $illegalWording): static
    {
        $this->illegalWording = $illegalWording;

        return $this;
    }

    public function getPenaltyStatus(): ?PenaltyStatus
    {
        return $this->penaltyStatus;
    }

    public function setPenaltyStatus(PenaltyStatus $penaltyStatus): static
    {
        $this->penaltyStatus = $penaltyStatus;

        return $this;
    }

    public function getComplaintOrderId(): ?string
    {
        return $this->complaintOrderId;
    }

    public function setComplaintOrderId(string $complaintOrderId): static
    {
        $this->complaintOrderId = $complaintOrderId;

        return $this;
    }

    public function getMinusScore(): ?int
    {
        return $this->minusScore;
    }

    public function setMinusScore(int $minusScore): static
    {
        $this->minusScore = $minusScore;

        return $this;
    }

    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    public function setOrderId(?string $orderId): static
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getCurrentScore(): ?int
    {
        return $this->currentScore;
    }

    public function setCurrentScore(?int $currentScore): static
    {
        $this->currentScore = $currentScore;

        return $this;
    }

    public function getRawData(): ?string
    {
        return $this->rawData;
    }

    public function setRawData(?string $rawData): void
    {
        $this->rawData = $rawData;
    }}
