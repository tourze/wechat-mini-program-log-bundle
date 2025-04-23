<?php

namespace WechatMiniProgramLogBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/operation/getFeedback.html
 */
class GetWechatFeedbackRequest extends WithAccountRequest
{
    /**
     * @var int 分页的页数，从1开始
     */
    private int $page = 1;

    /**
     * @var int 分页拉取的数据数量
     */
    private int $num = 20;

    /**
     * @var int|null 反馈的类型，默认拉取全部类型
     */
    private ?int $type = null;

    public function getRequestPath(): string
    {
        return '/wxaapi/feedback/list';
    }

    public function getRequestOptions(): ?array
    {
        $query = [
            'page' => $this->getPage(),
            'num' => $this->getNum(),
        ];
        if (null !== $this->getType()) {
            $query['type'] = $this->getType();
        }

        return [
            'query' => $query,
        ];
    }

    public function getRequestMethod(): ?string
    {
        return 'GET';
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function getNum(): int
    {
        return $this->num;
    }

    public function setNum(int $num): void
    {
        $this->num = $num;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): void
    {
        $this->type = $type;
    }
}
