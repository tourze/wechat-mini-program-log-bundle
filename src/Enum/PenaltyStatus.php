<?php

namespace WechatMiniProgramLogBundle\Enum;

use Tourze\EnumExtra\BadgeInterface;
use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum PenaltyStatus: string implements Labelable, Itemable, Selectable, BadgeInterface
{
    use ItemTrait;
    use SelectTrait;

    case TYPE_2 = '2';
    case TYPE_4 = '4';
    case TYPE_5 = '5';
    case TYPE_6 = '6';

    public function getLabel(): string
    {
        return match ($this) {
            self::TYPE_2 => '扣分审批通过',
            self::TYPE_4 => '申诉中',
            self::TYPE_5 => '申述驳回',
            self::TYPE_6 => '申诉成功',
        };
    }

    public function getBadge(): string
    {
        return match ($this) {
            self::TYPE_2 => 'danger',
            self::TYPE_4 => 'warning',
            self::TYPE_5 => 'danger',
            self::TYPE_6 => 'success',
        };
    }
}
