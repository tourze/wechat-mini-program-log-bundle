<?php

namespace WechatMiniProgramLogBundle\Request;

use Carbon\CarbonInterface;
use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 运维中心-查询错误列表
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/operation/getJsErrList.html
 */
class GetErrorListRequest extends WithAccountRequest
{
    private int $limit;

    private int $offset;

    private string $desc;

    private string $orderBy;

    private string $keyword;

    private string $openId;

    private string $appVersion;

    private string $errType;

    private CarbonInterface $startTime;

    private CarbonInterface $endTime;

    public function getRequestPath(): string
    {
        return '/wxaapi/log/jserr_list';
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRequestOptions(): ?array
    {
        $json = [
            // 文档说是 xxxx-xx-xx，实际不是，参考 https://developers.weixin.qq.com/community/develop/doc/00004c4e7f0018e5020d0bcf95b800
            'startTime' => $this->getStartTime()->format('Y-m-d H:i:s'),
            'endTime' => $this->getEndTime()->format('Y-m-d H:i:s'),
            'errType' => $this->getErrType(),
            'appVersion' => $this->getAppVersion(),
            'openid' => $this->getOpenId(),
            'keyword' => $this->getKeyword(),
            'orderby' => $this->getOrderBy(),
            'desc' => $this->getDesc(),
            'offset' => $this->getOffset(),
            'limit' => $this->getLimit(),
        ];

        return [
            'json' => $json,
        ];
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    public function getDesc(): string
    {
        return $this->desc;
    }

    public function setDesc(string $desc): void
    {
        $this->desc = $desc;
    }

    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    public function setOrderBy(string $orderBy): void
    {
        $this->orderBy = $orderBy;
    }

    public function getKeyword(): string
    {
        return $this->keyword;
    }

    public function setKeyword(string $keyword): void
    {
        $this->keyword = $keyword;
    }

    public function getOpenId(): string
    {
        return $this->openId;
    }

    public function setOpenId(string $openId): void
    {
        $this->openId = $openId;
    }

    public function getAppVersion(): string
    {
        return $this->appVersion;
    }

    public function setAppVersion(string $appVersion): void
    {
        $this->appVersion = $appVersion;
    }

    public function getErrType(): string
    {
        return $this->errType;
    }

    public function setErrType(string $errType): void
    {
        $this->errType = $errType;
    }

    public function getStartTime(): CarbonInterface
    {
        return $this->startTime;
    }

    public function setStartTime(CarbonInterface $startTime): void
    {
        $this->startTime = $startTime;
    }

    public function getEndTime(): CarbonInterface
    {
        return $this->endTime;
    }

    public function setEndTime(CarbonInterface $endTime): void
    {
        $this->endTime = $endTime;
    }
}
