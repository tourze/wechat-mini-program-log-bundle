<?php

namespace WechatMiniProgramLogBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;

#[AsPermission(title: '运维中心')]
class WechatMiniProgramLogBundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            \Tourze\Symfony\CronJob\CronJobBundle::class => ['all' => true],
            \WechatMiniProgramBundle\WechatMiniProgramBundle::class => ['all' => true],
        ];
    }
}
