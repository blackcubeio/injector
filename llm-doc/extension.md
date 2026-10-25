# injector — Extension

## What plugs in

| Need | How |
|---|---|
| another container | `init()` accepts any `Psr\Container\ContainerInterface` (yiisoft/di, Laravel `$this->app`, Slim container) |
| change what `get()` / `has()` return | change the definition in the container, or pass another `ContainerInterface` to `init()` |
| build an object with specific arguments | `get(array)` (`api.md`) |

## Not extensible

- The class is not `final`, but:
  - `$container` is `private static`, read through `self::` (`Injector.php:26`, `30`, `35-40`): a subclass cannot reach it;
  - callers write `Injector::get(...)`: a static call naming the class targets `Injector`, never a subclass.
- So a subclass of `Injector` changes nothing for existing callers; any behaviour change goes through the container.

## Tests

- Global state: every `init()` replaces the previous container.
- Reset between tests: set `container` back to `null` through reflection (`tests/Unit/InjectorCest.php:30-35`).
