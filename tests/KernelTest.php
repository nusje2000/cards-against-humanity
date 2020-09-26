<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests;

use Nusje2000\CAH\Kernel;
use PHPStan\Testing\TestCase;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\Loader\PhpFileLoader as RoutingLoader;
use Symfony\Component\Routing\RouteCollection;
use SymfonyBundles\JsonRequestBundle\SymfonyBundlesJsonRequestBundle;

final class KernelTest extends TestCase
{
    public function testRegisterBundles(): void
    {
        $kernel = $this->createKernel('dev', true);
        $bundles = $kernel->registerBundles();
        self::assertCount(4, $bundles);
        self::assertInstanceOf(FrameworkBundle::class, $bundles[0]);
        self::assertInstanceOf(SymfonyBundlesJsonRequestBundle::class, $bundles[1]);
        self::assertInstanceOf(TwigBundle::class, $bundles[2]);
        self::assertInstanceOf(WebProfilerBundle::class, $bundles[3]);

        $kernel = $this->createKernel('dev', false);
        $bundles = $kernel->registerBundles();
        self::assertCount(4, $bundles);
        self::assertInstanceOf(FrameworkBundle::class, $bundles[0]);
        self::assertInstanceOf(SymfonyBundlesJsonRequestBundle::class, $bundles[1]);
        self::assertInstanceOf(TwigBundle::class, $bundles[2]);
        self::assertInstanceOf(WebProfilerBundle::class, $bundles[3]);

        $kernel = $this->createKernel('test', true);
        $bundles = $kernel->registerBundles();
        self::assertCount(2, $bundles);
        self::assertInstanceOf(FrameworkBundle::class, $bundles[0]);
        self::assertInstanceOf(SymfonyBundlesJsonRequestBundle::class, $bundles[1]);

        $kernel = $this->createKernel('test', false);
        $bundles = $kernel->registerBundles();
        self::assertCount(2, $bundles);
        self::assertInstanceOf(FrameworkBundle::class, $bundles[0]);
        self::assertInstanceOf(SymfonyBundlesJsonRequestBundle::class, $bundles[1]);

        $kernel = $this->createKernel('prod', false);
        $bundles = $kernel->registerBundles();
        self::assertCount(2, $bundles);
        self::assertInstanceOf(FrameworkBundle::class, $bundles[0]);
        self::assertInstanceOf(SymfonyBundlesJsonRequestBundle::class, $bundles[1]);
    }

    public function testGetProjectDir(): void
    {
        self::assertSame(dirname(__DIR__), $this->createKernel('some-env', false)->getProjectDir());
    }

    public function testConfigureContainer(): void
    {
        $containerBuilder = $this->createStub(ContainerBuilder::class);
        $phpFileLoader = $this->createStub(PhpFileLoader::class);
        $instanceof = [];

        $this->createKernel('some-env', false)->configureContainer(new ContainerConfigurator($containerBuilder, $phpFileLoader, $instanceof, '/path', 'file'));

        $this->addToAssertionCount(1);
    }

    public function testConfigureRoutes(): void
    {
        $phpFileLoader = $this->createStub(RoutingLoader::class);

        $this->createKernel('dev', false)->configureRoutes(new RoutingConfigurator(new RouteCollection(), $phpFileLoader, '/path', 'file'));
        $this->createKernel('test', false)->configureRoutes(new RoutingConfigurator(new RouteCollection(), $phpFileLoader, '/path', 'file'));
        $this->createKernel('prod', false)->configureRoutes(new RoutingConfigurator(new RouteCollection(), $phpFileLoader, '/path', 'file'));

        $this->addToAssertionCount(1);
    }

    private function createKernel(string $environment, bool $debug): Kernel
    {
        return new Kernel($environment, $debug);
    }
}
