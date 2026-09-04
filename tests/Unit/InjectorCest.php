<?php

declare(strict_types=1);

/**
 * InjectorCest.php
 *
 * PHP Version 8.4
 *
 * @copyright 2010-2026 Blackcube - Philippe Gaultier
 * @license https://www.blackcube.io/license
 * @link https://www.blackcube.io
 */

namespace Blackcube\Injector\Tests\Unit;

use Blackcube\Injector\Injector;
use Blackcube\Injector\Tests\Support\SimpleContainer;
use Blackcube\Injector\Tests\Support\UnitTester;
use RuntimeException;

/**
 * Unit tests for Injector.
 */
final class InjectorCest
{
    /**
     * Reset Injector state between tests via reflection.
     */
    public function _before(UnitTester $I): void
    {
        $ref = new \ReflectionClass(Injector::class);
        $prop = $ref->getProperty('container');
        $prop->setValue(null, null);
    }

    public function getThrowsWhenNotInitialized(UnitTester $I): void
    {
        $I->wantTo('verify get() throws when Injector is not initialized');

        $I->expectThrowable(RuntimeException::class, static function () {
            Injector::get('anything');
        });
    }

    public function hasReturnsFalseWhenNotInitialized(UnitTester $I): void
    {
        $I->wantTo('verify has() returns false when Injector is not initialized');

        $I->assertFalse(Injector::has('anything'));
    }

    public function initAndGetReturnsService(UnitTester $I): void
    {
        $I->wantTo('verify init() + get() returns the registered service');

        $container = new SimpleContainer([
            'logger' => 'my-logger-instance',
            'db' => 'my-db-instance',
        ]);

        Injector::init($container);

        $I->assertEquals('my-logger-instance', Injector::get('logger'));
        $I->assertEquals('my-db-instance', Injector::get('db'));
    }

    public function hasReturnsTrueForRegisteredService(UnitTester $I): void
    {
        $I->wantTo('verify has() returns true for a registered service');

        $container = new SimpleContainer(['foo' => 'bar']);
        Injector::init($container);

        $I->assertTrue(Injector::has('foo'));
    }

    public function hasReturnsFalseForUnknownService(UnitTester $I): void
    {
        $I->wantTo('verify has() returns false for an unknown service');

        $container = new SimpleContainer(['foo' => 'bar']);
        Injector::init($container);

        $I->assertFalse(Injector::has('unknown'));
    }

    public function getThrowsForUnknownService(UnitTester $I): void
    {
        $I->wantTo('verify get() throws NotFoundExceptionInterface for unknown service');

        $container = new SimpleContainer([]);
        Injector::init($container);

        $I->expectThrowable(\Psr\Container\NotFoundExceptionInterface::class, static function () {
            Injector::get('nonexistent');
        });
    }

    public function initOverridesPreviousContainer(UnitTester $I): void
    {
        $I->wantTo('verify calling init() twice replaces the container');

        $first = new SimpleContainer(['key' => 'first']);
        $second = new SimpleContainer(['key' => 'second']);

        Injector::init($first);
        $I->assertEquals('first', Injector::get('key'));

        Injector::init($second);
        $I->assertEquals('second', Injector::get('key'));
    }
}
