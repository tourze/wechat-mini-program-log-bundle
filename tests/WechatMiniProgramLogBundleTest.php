<?php

declare(strict_types=1);

namespace WechatMiniProgramLogBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use WechatMiniProgramLogBundle\WechatMiniProgramLogBundle;

/**
 * @internal
 */
#[CoversClass(WechatMiniProgramLogBundle::class)]
#[RunTestsInSeparateProcesses]
final class WechatMiniProgramLogBundleTest extends AbstractBundleTestCase
{
}
