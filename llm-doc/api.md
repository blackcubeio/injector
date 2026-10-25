# injector — API of `Blackcube\Injector\Injector`

All methods are static. Single state: `private static ?ContainerInterface $container` (`Injector.php:26`).

| Method | Returns | Behaviour |
|---|---|---|
| `init(ContainerInterface $container)` | `void` | stores the container; a second call replaces it |
| `get(string $id)` | `mixed` | `$container->get($id)` |
| `get(array $definition)` | `mixed` | `$container->get(Factory::class)->create($definition)` (`Injector.php:39`) |
| `has(string $id)` | `bool` | `$container->has($id)`; `false` before `init()` |

## Before `init()`

- `get()` (string or array) throws `RuntimeException` `Injector has not been initialized`.
- `has()` returns `false`, no exception.

## `get(string)`

- Delegates to the container: sharing, autowiring and exceptions are the container's.
- Measured with yiisoft/di: two `get(X::class)` calls return the same object; an unknown id throws `Yiisoft\Di\NotFoundException`.

## `get(array)`

- Built through `Yiisoft\Factory\Factory::create()`: a new object on every call (measured: two calls → two different objects).
- Measured keys: `class` (required), `__construct()` (named arguments), `$property` (assignment).

```php
$formHydrator = Injector::get([
    'class' => FormHydrator::class,
    '__construct()' => [
        'hydrator' => $hydrator,
    ],
]);
```

- Without `class` → `Yiisoft\Definitions\Exception\InvalidConfigException` `Invalid definition: no class name specified.`
- The container must provide `Yiisoft\Factory\Factory` (yiisoft/di does, through autowiring). Otherwise: the container's "not found" exception for `Yiisoft\Factory\Factory` (measured with a minimal PSR-11 container).

## `has(string)`

- Delegates to the container.
- Measured with yiisoft/di: `true` for any instantiable class, even undeclared; `false` for an undeclared interface. So `has(SomeInterface::class)` = an implementation is declared.

## Exceptions

| Case | Exception |
|---|---|
| `get()` before `init()` | `RuntimeException` `Injector has not been initialized` |
| `get(string)`, unknown id | the container's "not found" exception (`Yiisoft\Di\NotFoundException` with yiisoft/di) |
| `get(array)` without `class` | `Yiisoft\Definitions\Exception\InvalidConfigException` |
| `get(array)`, container without `Factory` | the container's "not found" exception for `Yiisoft\Factory\Factory` |
