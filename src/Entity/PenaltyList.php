<?php

namespace WechatMiniProgramLogBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use WechatMiniProgramLogBundle\Enum\PenaltyStatus;
use WechatMiniProgramLogBundle\Repository\PenaltyListRepository;

/**
 * 小程序交易体验分违规记录
 */
#[ORM\Entity(repositoryClass: PenaltyListRepository::class)]
#[ORM\Table(name: 'wechat_penalty_list', options: ['comment' => '小程序交易体验分违规记录'])]
class PenaltyList implements \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;

    #[ORM\Column(length: 100, nullable: true, options: ['comment' => '违规订单ID'])]
    #[Assert\Length(max: 100, maxMessage: '违规订单ID长度不能超过{{ limit }}个字符')]
    private ?string $illegalOrderId = null;

    #[ORM\Column(length: 100, nullable: true, options: ['comment' => '投诉订单ID'])]
    #[Assert\Length(max: 100, maxMessage: '投诉订单ID长度不能超过{{ limit }}个字符')]
    private ?string $complaintOrderId = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['comment' => '违规时间'])]
    #[Assert\Type(type: \DateTimeInterface::class, message: '违规时间格式无效')]
    private ?\DateTimeInterface $illegalTime = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '违规措辞'])]
    #[Assert\Length(max: 65535, maxMessage: '违规措辞长度不能超过{{ limit }}个字符')]
    private ?string $illegalWording = null;

    #[ORM\Column(length: 20, enumType: PenaltyStatus::class, nullable: true, options: ['comment' => '处罚状态'])]
    #[Assert\Type(type: PenaltyStatus::class, message: '处罚状态类型无效')]
    #[Assert\Choice(callback: [PenaltyStatus::class, 'cases'], message: '处罚状态选择无效')]
    private ?PenaltyStatus $penaltyStatus = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '扣分'])]
    #[Assert\Type(type: 'integer', message: '扣分必须是整数')]
    #[Assert\PositiveOrZero(message: '扣分不能为负数')]
    private ?int $minusScore = null;

    #[ORM\Column(length: 100, nullable: true, options: ['comment' => '订单ID'])]
    #[Assert\Length(max: 100, maxMessage: '订单ID长度不能超过{{ limit }}个字符')]
    private ?string $orderId = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '当前分数'])]
    #[Assert\Type(type: 'integer', message: '当前分数必须是整数')]
    #[Assert\PositiveOrZero(message: '当前分数不能为负数')]
    private ?int $currentScore = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '原始数据'])]
    #[Assert\Length(max: 65535, maxMessage: '原始数据长度不能超过{{ limit }}个字符')]
    private ?string $rawData = null;

    public function getIllegalOrderId(): ?string
    {
        return $this->illegalOrderId;
    }

    public function setIllegalOrderId(?string $illegalOrderId): void
    {
        $this->illegalOrderId = $illegalOrderId;
    }

    public function getIllegalTime(): ?\DateTimeInterface
    {
        return $this->illegalTime;
    }

    public function setIllegalTime(?\DateTimeInterface $illegalTime): void
    {
        $this->illegalTime = $illegalTime;
    }

    public function getIllegalWording(): ?string
    {
        return $this->illegalWording;
    }

    public function setIllegalWording(?string $illegalWording): void
    {
        $this->illegalWording = $illegalWording;
    }

    public function getPenaltyStatus(): ?PenaltyStatus
    {
        return $this->penaltyStatus;
    }

    public function setPenaltyStatus(?PenaltyStatus $penaltyStatus): void
    {
        $this->penaltyStatus = $penaltyStatus;
    }

    public function getComplaintOrderId(): ?string
    {
        return $this->complaintOrderId;
    }

    public function setComplaintOrderId(?string $complaintOrderId): void
    {
        $this->complaintOrderId = $complaintOrderId;
    }

    public function getMinusScore(): ?int
    {
        return $this->minusScore;
    }

    public function setMinusScore(?int $minusScore): void
    {
        $this->minusScore = $minusScore;
    }

    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    public function setOrderId(?string $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getCurrentScore(): ?int
    {
        return $this->currentScore;
    }

    public function setCurrentScore(?int $currentScore): void
    {
        $this->currentScore = $currentScore;
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
