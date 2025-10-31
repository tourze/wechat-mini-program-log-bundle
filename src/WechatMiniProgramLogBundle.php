<?php

namespace WechatMiniProgramLogBundle;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;
use Tourze\DoctrineSnowflakeBundle\DoctrineSnowflakeBundle;
use Tourze\DoctrineTimestampBundle\DoctrineTimestampBundle;
use Tourze\Symfony\CronJob\CronJobBundle;
use WechatMiniProgramBundle\WechatMiniProgramBundle;

class WechatMiniProgramLogBundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            DoctrineBundle::class => ['all' => true],
            DoctrineSnowflakeBundle::class => ['all' => true],
            DoctrineTimestampBundle::class => ['all' => true],
            CronJobBundle::class => ['all' => true],
            WechatMiniProgramBundle::class => ['all' => true],
        ];
    }
}
