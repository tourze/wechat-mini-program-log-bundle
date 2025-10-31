<?php

namespace WechatMiniProgramLogBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 获取小程序交易体验分违规记录
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/transaction-guarantee/GetPenaltyList.html
 */
class GetWechatPenaltyListRequest extends WithAccountRequest
{
    private int $offset = 0;

    private int $limit = 20;

    public function getRequestPath(): string
    {
        return '/wxaapi/wxamptrade/get_penalty_list';
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRequestOptions(): ?array
    {
        return [
            'query' => [
                'offset' => $this->getOffset(),
                'limit' => $this->getLimit(),
            ],
        ];
    }

    public function getRequestMethod(): ?string
    {
        return 'GET';
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRequestPayload(): ?array
    {
        return [];
    }
}
