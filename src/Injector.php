<?php

declare(strict_types=1);

/**
 * Injector.php
 *
 * PHP Version 8.4
 *
 * @copyright 2010-2026 Blackcube - Philippe Gaultier
 * @license https://www.blackcube.io/license
 * @link https://www.blackcube.io
 */

namespace Blackcube\Injector;

use Psr\Container\ContainerInterface;
use RuntimeException;
use Yiisoft\Factory\Factory;

class Injector
{
    private static ?ContainerInterface $container = null;

    public static function init(ContainerInterface $container): void
    {
        self::$container = $container;
    }

    public static function get(string|array $id): mixed
    {
        if (self::$container === null) {
            throw new RuntimeException('Injector has not been initialized');
        }
        return is_array($id) === true
            ? self::$container->get(Factory::class)->create($id)
            : self::$container->get($id);
    }

    public static function has(string $id): bool
    {
        if (self::$container === null) {
            return false;
        }
        return self::$container->has($id);
    }
}