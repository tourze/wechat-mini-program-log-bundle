<?php

namespace WechatMiniProgramLogBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum FeedbackType: string implements Labelable, Itemable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case TYPE_1 = '1';
    case TYPE_2 = '2';
    case TYPE_3 = '3';
    case TYPE_4 = '4';
    case TYPE_5 = '5';
    case TYPE_6 = '6';
    case TYPE_7 = '7';
    case TYPE_8 = '8';

    public function getLabel(): string
    {
        return match ($this) {
            self::TYPE_1 => '无法打开小程序',
            self::TYPE_2 => '小程序闪退',
            self::TYPE_3 => '卡顿',
            self::TYPE_4 => '黑屏白屏',
            self::TYPE_5 => '死机',
            self::TYPE_6 => '界面错位',
            self::TYPE_7 => '界面加载慢',
            self::TYPE_8 => '其他异常',
        };
    }
}
