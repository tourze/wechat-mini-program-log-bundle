<?php

namespace WechatMiniProgramLogBundle\Tests;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramLogBundle\DependencyInjection\WechatMiniProgramLogExtension;
use WechatMiniProgramLogBundle\WechatMiniProgramLogBundle;

class WechatMiniProgramLogBundleTest extends TestCase
{
    private WechatMiniProgramLogBundle $bundle;

    protected function setUp(): void
    {
        $this->bundle = new WechatMiniProgramLogBundle();
    }

    public function testGetContainerExtension_ShouldReturnExtension(): void
    {
        $extension = $this->bundle->getContainerExtension();
        $this->assertInstanceOf(WechatMiniProgramLogExtension::class, $extension);
    }
} 