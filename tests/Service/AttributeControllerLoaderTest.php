<?php

declare(strict_types=1);

namespace WechatMiniProgramLogBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Routing\RouteCollection;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use Tourze\RoutingAutoLoaderBundle\Service\RoutingAutoLoaderInterface;
use WechatMiniProgramLogBundle\Controller\ErrorDetailCrudController;
use WechatMiniProgramLogBundle\Controller\ErrorListDataCrudController;
use WechatMiniProgramLogBundle\Controller\FeedbackCrudController;
use WechatMiniProgramLogBundle\Controller\PenaltyListCrudController;
use WechatMiniProgramLogBundle\Service\AttributeControllerLoader;

/**
 * @internal
 */
#[CoversClass(AttributeControllerLoader::class)]
#[RunTestsInSeparateProcesses]
final class AttributeControllerLoaderTest extends AbstractIntegrationTestCase
{
    private AttributeControllerLoader $loader;

    protected function onSetUp(): void
    {
        $this->loader = self::getService(AttributeControllerLoader::class);
    }

    public function testImplementsRoutingAutoLoaderInterface(): void
    {
        $this->assertInstanceOf(RoutingAutoLoaderInterface::class, $this->loader);
    }

    public function testSupportsAlwaysReturnsFalse(): void
    {
        $this->assertFalse($this->loader->supports('test'));
        $this->assertFalse($this->loader->supports(''));
        $this->assertFalse($this->loader->supports(null));
    }

    public function testLoadReturnsRouteCollection(): void
    {
        $routes = $this->loader->load('test');

        $this->assertInstanceOf(RouteCollection::class, $routes);
    }

    public function testAutoloadReturnsRouteCollection(): void
    {
        $routes = $this->loader->autoload();

        $this->assertInstanceOf(RouteCollection::class, $routes);
    }

    public function testAutoloadReturnsConsistentResults(): void
    {
        $routes1 = $this->loader->autoload();
        $routes2 = $this->loader->autoload();

        $this->assertEquals($routes1->count(), $routes2->count());
        $this->assertEquals($routes1->all(), $routes2->all());
    }

    public function testLoadAndAutoloadReturnSameResults(): void
    {
        $loadRoutes = $this->loader->load('test');
        $autoloadRoutes = $this->loader->autoload();

        $this->assertEquals($loadRoutes->count(), $autoloadRoutes->count());
        $this->assertEquals($loadRoutes->all(), $autoloadRoutes->all());
    }

    public function testControllerClassesCanBeInstantiated(): void
    {
        $errorDetailController = new ErrorDetailCrudController();
        $this->assertInstanceOf(ErrorDetailCrudController::class, $errorDetailController);

        $errorListDataController = new ErrorListDataCrudController();
        $this->assertInstanceOf(ErrorListDataCrudController::class, $errorListDataController);

        $feedbackController = new FeedbackCrudController();
        $this->assertInstanceOf(FeedbackCrudController::class, $feedbackController);

        $penaltyListController = new PenaltyListCrudController();
        $this->assertInstanceOf(PenaltyListCrudController::class, $penaltyListController);
    }

    public function testLoaderCanBeInstantiated(): void
    {
        $this->assertInstanceOf(AttributeControllerLoader::class, $this->loader);
    }
}
