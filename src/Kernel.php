<?php

declare(strict_types=1);

namespace Nusje2000\CAH;

use League\Tactician\Bundle\TacticianBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use SymfonyBundles\JsonRequestBundle\SymfonyBundlesJsonRequestBundle;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * @return array<int, BundleInterface>
     */
    public function registerBundles(): array
    {
        $bundles = [
            new FrameworkBundle(),
            new SymfonyBundlesJsonRequestBundle(),
            new TwigBundle(),
            new TacticianBundle(),
        ];

        if ('dev' === $this->getEnvironment()) {
            $bundles[] = new WebProfilerBundle();
        }

        return $bundles;
    }

    public function getProjectDir(): string
    {
        return dirname(__DIR__);
    }

    public function configureContainer(ContainerConfigurator $configurator): void
    {
        $confDir = $this->getConfigDir();

        $configurator->import($confDir . '/services/commands.xml');
        $configurator->import($confDir . '/services/controllers.xml');
        $configurator->import($confDir . '/services/consumers.xml');
        $configurator->import($confDir . '/services/repositories.xml');
    }

    public function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import($this->getConfigDir() . '/routes/routes.xml');

        if ('dev' === $this->getEnvironment()) {
            $routes->import($this->getConfigDir() . '/routes/routes_dev.xml');
        }
    }

    /**
     * @return string
     */
    private function getConfigDir(): string
    {
        return $this->getProjectDir() . '/config';
    }
}
