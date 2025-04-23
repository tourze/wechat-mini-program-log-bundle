<?php

namespace WechatMiniProgramLogBundle\Request;

use Carbon\CarbonInterface;
use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 运维中心-查询js错误详情
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/operation/getJsErrDetail.html
 */
class GetErrorDetailRequest extends WithAccountRequest
{
    private string $clientVersion;

    private string $osName;

    private string $sdkVersion;

    private string $errorStackCode;

    private string $errorMsgCode;

    private int $limit;

    private int $offset;

    private string $desc;

    private string $openId;

    private string $appVersion;

    private CarbonInterface $startTime;

    private CarbonInterface $endTime;

    public function getRequestPath(): string
    {
        return '/wxaapi/log/jserr_detail';
    }

    public function getRequestOptions(): ?array
    {
        $json = [
            'startTime' => $this->getStartTime()->format('Y-m-d'),
            'endTime' => $this->getEndTime()->format('Y-m-d'),
            'errorMsgMd5' => $this->getErrorMsgCode(),
            'errorStackMd5' => $this->getErrorStackCode(),
            'appVersion' => $this->getAppVersion(),
            'sdkVersion' => $this->getSdkVersion(),
            'osName' => $this->getOsName(),
            'clientVersion' => $this->getClientVersion(),
            'openid' => $this->getOpenId(),
            'desc' => $this->getDesc(),
            'offset' => $this->getOffset(),
            'limit' => $this->getLimit(),
        ];

        return [
            'json' => $json,
        ];
    }

    public function getClientVersion(): string
    {
        return $this->clientVersion;
    }

    public function setClientVersion(string $clientVersion): void
    {
        $this->clientVersion = $clientVersion;
    }

    public function getOsName(): string
    {
        return $this->osName;
    }

    public function setOsName(string $osName): void
    {
        $this->osName = $osName;
    }

    public function getSdkVersion(): string
    {
        return $this->sdkVersion;
    }

    public function setSdkVersion(string $sdkVersion): void
    {
        $this->sdkVersion = $sdkVersion;
    }

    public function getErrorStackCode(): string
    {
        return $this->errorStackCode;
    }

    public function setErrorStackCode(string $errorStackCode): void
    {
        $this->errorStackCode = $errorStackCode;
    }

    public function getErrorMsgCode(): string
    {
        return $this->errorMsgCode;
    }

    public function setErrorMsgCode(string $errorMsgCode): void
    {
        $this->errorMsgCode = $errorMsgCode;
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
