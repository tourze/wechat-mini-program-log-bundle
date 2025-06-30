<?php

namespace WechatMiniProgramLogBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use WechatMiniProgramLogBundle\Enum\PenaltyStatus;
use WechatMiniProgramLogBundle\Repository\PenaltyListRepository;

/**
 * 小程序交易体验分违规记录
 */
#[ORM\Entity(repositoryClass: PenaltyListRepository::class)]
#[ORM\Table(name: 'wechat_penalty_list', options: ['comment' => '小程序交易体验分违规记录'])]
class PenaltyList implements Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;

    private ?string $illegalOrderId = null;

    private ?string $complaintOrderId = null;

    private ?\DateTimeInterface $illegalTime = null;

    private ?string $illegalWording = null;

    private ?PenaltyStatus $penaltyStatus = null;

    private ?int $minusScore = null;

    private ?string $orderId = null;

    private ?int $currentScore = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '原始数据'])]
    private ?string $rawData = null;


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
    }
    public function __toString(): string
    {
        return (string) $this->id;
    }
}
