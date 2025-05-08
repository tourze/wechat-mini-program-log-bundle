<?php

namespace WechatMiniProgramLogBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineTimestampBundle\Attribute\CreateTimeColumn;
use Tourze\DoctrineTimestampBundle\Attribute\UpdateTimeColumn;
use Tourze\EasyAdmin\Attribute\Action\Deletable;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Filter\Filterable;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramLogBundle\Repository\ErrorListDataRepository;

#[AsPermission(title: '查询错误列表')]
#[Deletable]
#[ORM\Entity(repositoryClass: ErrorListDataRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_error_list', options: ['comment' => '查询错误列表'])]
#[ORM\UniqueConstraint(name: 'wechat_mini_program_error_list_uniq', columns: ['account_id', 'date', 'open_id', 'error_msg_code'])]
class ErrorListData
{
    #[ListColumn(order: -1)]
    #[ExportColumn]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    #[ListColumn]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $account = null;

    #[ListColumn]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $openId = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $errorMsgCode = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $errorMsg = null;

    #[ListColumn]
    #[ORM\Column]
    private ?int $uv = null;

    #[ListColumn]
    #[ORM\Column]
    private ?int $pv = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $errorStackCode = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $errorStack = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $pvPercent = null;

    #[ListColumn]
    #[ORM\Column]
    private ?string $uvPercent = null;

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

    public function setPv(string $pv): static
    {
        $this->pv = $pv;

        return $this;
    }

    public function getUv(): ?int
    {
        return $this->uv;
    }

    public function setUv(string $uv): static
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
