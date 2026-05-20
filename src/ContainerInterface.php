<?php

declare(strict_types=1);

namespace Schnell;

use Psr\Container\ContainerInterface as PsrContainerInterface;
use Schnell\Config\ConfigInterface;

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
interface ContainerInterface extends PsrContainerInterface
{
    /**
     * @param string $id
     * @param mixed $definition
     * @return void
     */
    public function set(string $id, $definition): void;

    /**
     * @param array $definitions
     * @return void
     */
    public function setMultiple(array $definitions): void;

    /**
     * @param string $className
     * @param string $alias
     * @return void
     */
    public function alias(string $className, string $alias): void;

    /**
     * @return \Schnell\Config\ConfigInterface
     */
    public function getConfig(): ConfigInterface;

    /**
     * @param \Schnell\Config\ConfigInterface $config
     * @return void
     */
    public function setConfig(ConfigInterface $config): void;
}
