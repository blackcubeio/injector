<?php

declare(strict_types=1);

/**
 * SimpleContainer.php
 *
 * PHP Version 8.4
 *
 * @copyright 2010-2026 Blackcube - Philippe Gaultier
 * @license https://www.blackcube.io/license
 * @link https://www.blackcube.io
 */

namespace Blackcube\Injector\Tests\Support;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Minimal PSR-11 container for testing.
 */
final class SimpleContainer implements ContainerInterface
{
    public function __construct(private readonly array $services = [])
    {
    }

    public function get(string $id): mixed
    {
        if ($this->has($id) === false) {
            throw new class ('Service not found: '.$id) extends \RuntimeException implements NotFoundExceptionInterface {};
        }
        return $this->services[$id];
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services) === true;
    }
}
