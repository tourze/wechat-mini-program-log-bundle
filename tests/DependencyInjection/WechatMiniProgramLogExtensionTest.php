<?php

namespace WechatMiniProgramLogBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;
use WechatMiniProgramLogBundle\DependencyInjection\WechatMiniProgramLogExtension;

/**
 * @internal
 */
#[CoversClass(WechatMiniProgramLogExtension::class)]
final class WechatMiniProgramLogExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    public function testLoadShouldLoadServices(): void
    {
        $extension = new WechatMiniProgramLogExtension();
        $container = new ContainerBuilder();
        $container->setParameter('kernel.environment', 'test');

        $extension->load([], $container);

        $this->assertInstanceOf(WechatMiniProgramLogExtension::class, $extension);
    }

    public function testLoadShouldRegisterRepositories(): void
    {
        $extension = new WechatMiniProgramLogExtension();
        $container = new ContainerBuilder();
        $container->setParameter('kernel.environment', 'test');

        $extension->load([], $container);

        $serviceIds = $container->getServiceIds();
        $this->assertNotEmpty($serviceIds);
    }
}
