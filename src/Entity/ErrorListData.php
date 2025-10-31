<?php

namespace WechatMiniProgramLogBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Repository\ErrorListDataRepository;

#[ORM\Entity(repositoryClass: ErrorListDataRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_error_list', options: ['comment' => '查询错误列表'])]
#[ORM\UniqueConstraint(name: 'wechat_mini_program_error_list_uniq', columns: ['account_id', 'date', 'open_id', 'error_msg_code'])]
class ErrorListData implements \Stringable
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

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => 'UV访问量'])]
    #[Assert\Type(type: 'integer', message: 'UV必须是整数')]
    #[Assert\PositiveOrZero(message: 'UV不能为负数')]
    private ?int $uv = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => 'PV访问量'])]
    #[Assert\Type(type: 'integer', message: 'PV必须是整数')]
    #[Assert\PositiveOrZero(message: 'PV不能为负数')]
    private ?int $pv = null;

    #[ORM\Column(length: 50, nullable: true, options: ['comment' => '错误堆栈代码'])]
    #[Assert\Length(max: 50, maxMessage: '错误堆栈代码长度不能超过{{ limit }}个字符')]
    private ?string $errorStackCode = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '错误堆栈'])]
    #[Assert\Length(max: 65535, maxMessage: '错误堆栈长度不能超过{{ limit }}个字符')]
    private ?string $errorStack = null;

    #[ORM\Column(length: 10, nullable: true, options: ['comment' => 'PV百分比'])]
    #[Assert\Length(max: 10, maxMessage: 'PV百分比长度不能超过{{ limit }}个字符')]
    private ?string $pvPercent = null;

    #[ORM\Column(length: 10, nullable: true, options: ['comment' => 'UV百分比'])]
    #[Assert\Length(max: 10, maxMessage: 'UV百分比长度不能超过{{ limit }}个字符')]
    private ?string $uvPercent = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUvPercent(): ?string
    {
        return $this->uvPercent;
    }

    public function setUvPercent(string $uvPercent): void
    {
        $this->uvPercent = $uvPercent;
    }

    public function getPvPercent(): ?string
    {
        return $this->pvPercent;
    }

    public function setPvPercent(string $pvPercent): void
    {
        $this->pvPercent = $pvPercent;
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

    public function getPv(): ?int
    {
        return $this->pv;
    }

    public function setPv(int $pv): void
    {
        $this->pv = $pv;
    }

    public function getUv(): ?int
    {
        return $this->uv;
    }

    public function setUv(int $uv): void
    {
        $this->uv = $uv;
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
