# API

## Injector

Static PSR-11 bridge. All methods are static — no instantiation needed.

| Method | Description |
|--------|-------------|
| `Injector::init(ContainerInterface $container): void` | Store the container (call once at bootstrap) |
| `Injector::get(string|array $id): mixed` | Retrieve a service (string id) or build one from a factory definition (array) — throws `RuntimeException` if not initialized |
| `Injector::has(string $id): bool` | Check if a service exists — returns `false` if not initialized |

### init()

Stores a PSR-11 container for later use. Call once during application bootstrap. Calling again replaces the previous container.

```php
use Blackcube\Injector\Injector;
use Psr\Container\ContainerInterface;

Injector::init($container);
```

### get()

Retrieves a service from the stored container. Throws `RuntimeException` if `init()` has not been called. With a string id it delegates to the container's own `get()`, which may throw `NotFoundExceptionInterface` for unknown services. With an array it builds the object through `Yiisoft\Factory\Factory::create()`.

```php
$logger = Injector::get(LoggerInterface::class);
```

### has()

Checks if a service is available. Returns `false` if `init()` has not been called (no exception).

```php
if (Injector::has(CacheInterface::class)) {
    $cache = Injector::get(CacheInterface::class);
}
```
