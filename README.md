# Blackcube Injector

> **⚠️ Blackcube Warning**
>
> DI works great — inside methods. Outside, you're on your own.
>
> Static factories, prototype patterns, helper classes — no constructor, no injection point.
> Injector is a portable container that bridges the gap without depending on the inheritance chain.
> One `init()` at bootstrap, `get()` wherever the container can't reach.

Static PSR-11 bridge for dependency injection outside of method scope.

[![License](https://img.shields.io/badge/license-BSD--3--Clause-blue.svg)](LICENSE.md)
[![Packagist Version](https://img.shields.io/packagist/v/blackcube/injector.svg)](https://packagist.org/packages/blackcube/injector)
[![Warning](https://img.shields.io/badge/Blackcube-Warning-orange)](BLACKCUBE_WARNING.md)

## Installation

```bash
composer require blackcube/injector
```

## Why Injector?

| Situation | Problem |
|-----------|---------|
| Static factory (`File::from()`) | No constructor, no DI |
| Prototype pattern (`CacheFile`) | Clone-based, container unreachable |
| Helper classes | Need a service, have no injection point |
| **Injector** | `Injector::get(MyService::class)` — done |

**One class. Three methods. Zero magic.**

## Quick Start

### 1. Bootstrap (once)

```php
use Blackcube\Injector\Injector;

// In your bootstrap or container setup
Injector::init($container);
```

With Yii config-plugin, this happens automatically via `config/common/bootstrap.php`.

### 2. Use anywhere

```php
use Blackcube\Injector\Injector;

// Get a service
$logger = Injector::get(LoggerInterface::class);

// Check availability
if (Injector::has(CacheInterface::class)) {
    $cache = Injector::get(CacheInterface::class);
}
```

## API

| Method | Description |
|--------|-------------|
| `Injector::init(ContainerInterface $container)` | Store the container (call once at bootstrap) |
| `Injector::get(string|array $id): mixed` | Retrieve a service (string id) or build one from a factory definition (array) — throws `RuntimeException` if not initialized |
| `Injector::has(string $id): bool` | Check if a service exists — returns `false` if not initialized |

## Yii Integration

The package ships with config-plugin support. Add `blackcube/injector` to your project and the bootstrap runs automatically:

```php
// config/common/bootstrap.php (shipped with package)
static function (ContainerInterface $container): void {
    Injector::init($container);
};
```

No manual setup required.

## Tests

```bash
vendor/bin/codecept run Unit
```

## License

BSD-3-Clause. See [LICENSE.md](LICENSE.md).

## Author

Philippe Gaultier <philippe@blackcube.io>
