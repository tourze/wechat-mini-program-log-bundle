<?php

namespace WechatMiniProgramLogBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Repository\ErrorListDataRepository;

#[ORM\Entity(repositoryClass: ErrorListDataRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_error_list', options: ['comment' => '查询错误列表'])]
#[ORM\UniqueConstraint(name: 'wechat_mini_program_error_list_uniq', columns: ['account_id', 'date', 'open_id', 'error_msg_code'])]
class ErrorListData implements Stringable
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

    private ?int $uv = null;

    private ?int $pv = null;

    private ?string $errorStackCode = null;

    private ?string $errorStack = null;

    private ?string $pvPercent = null;

    private ?string $uvPercent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUvPercent(): ?string
    {
        return $this->uvPercent;
    }

    public function setUvPercent(string $uvPercent): static
    {
        $this->uvPercent = $uvPercent;

        return $this;
    }

    public function getPvPercent(): ?string
    {
        return $this->pvPercent;
    }

    public function setPvPercent(string $pvPercent): static
    {
        $this->pvPercent = $pvPercent;

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

    public function getPv(): ?int
    {
        return $this->pv;
    }

    public function setPv(int $pv): static
    {
        $this->pv = $pv;

        return $this;
    }

    public function getUv(): ?int
    {
        return $this->uv;
    }

    public function setUv(int $uv): static
    {
        $this->uv = $uv;

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
