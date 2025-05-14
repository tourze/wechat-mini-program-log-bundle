<?php

namespace WechatMiniProgramLogBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use WechatMiniProgramLogBundle\DependencyInjection\WechatMiniProgramLogExtension;

class WechatMiniProgramLogExtensionTest extends TestCase
{
    private WechatMiniProgramLogExtension $extension;
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $this->extension = new WechatMiniProgramLogExtension();
        $this->container = new ContainerBuilder();
    }

    public function testLoad_ShouldLoadServices(): void
    {
        $this->extension->load([], $this->container);
        
        // 验证服务是否加载
        $this->assertTrue($this->container->hasParameter('wechat_mini_program_log.loaded'));
    }

    public function testLoad_ShouldRegisterCommands(): void
    {
        $this->extension->load([], $this->container);
        
        // 验证命令服务是否注册
        $commandServiceIds = array_filter(
            $this->container->getServiceIds(),
            function ($id) {
                return strpos($id, 'WechatMiniProgramLogBundle\Command\\') === 0;
            }
        );
        
        $this->assertNotEmpty($commandServiceIds);
    }
} 