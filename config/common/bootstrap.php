<?php

declare(strict_types=1);

/**
 * bootstrap.php
 *
 * PHP Version 8.4
 *
 * @copyright 2010-2026 Blackcube - Philippe Gaultier
 * @license https://www.blackcube.io/license
 * @link https://www.blackcube.io
 */

use Blackcube\Injector\Injector;
use Psr\Container\ContainerInterface;

/** @var array $params */

return [
    static function (ContainerInterface $container): void {
        Injector::init($container);
    },
];
