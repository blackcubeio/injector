# blackcube/injector — LLM index

**Role:** static access to the PSR-11 container, for code that has no injectable constructor (static factory, static singleton, trait, cloned prototype).

## Package card

| | |
|---|---|
| Namespace | `Blackcube\Injector` |
| Single class | `Injector` (`src/Injector.php`), 3 static methods: `init()`, `get()`, `has()` |
| Requires | PHP ^8.2, `psr/container` ^2.0, `yiisoft/factory` ^1.3. No Blackcube dependency. |
| Yii config | config-plugin, `bootstrap` group: `config/common/bootstrap.php:17-19` calls `Injector::init($container)` |
| License | BSD-3-Clause |

## Rules

- Constructor injection first; `Injector` only where the container cannot reach (README: "`get()` wherever the container can't reach").
- Do not confuse with `Yiisoft\Injector\Injector` (instance class, invokes callables). When both are imported, alias ours as `BlackcubeInjector`.
- Optional service: `Injector::has(X) === true ? Injector::get(X) : <fallback>`. `has()` never throws.

## Parts

| File | Content | Read when |
|---|---|---|
| `api.md` | The 3 signatures. Before `init()`: `get()` throws `RuntimeException('Injector has not been initialized')`, `has()` returns `false`. `get(string)` delegates to the container (shared or not depending on the container). `get(array)` calls `Factory::create()`: a new object on every call, yiisoft/definitions format (`class`, `__construct()`), `Yiisoft\Factory\Factory` must be resolvable from the container (`Injector.php:39`). A second `init()` replaces the container. Exceptions. | before calling `Injector` |
| `integration.md` | Yii: `init()` is called automatically by the `bootstrap` group. Other PSR-11 container: call `init()` manually at bootstrap (Slim, Laravel). The 3 measured patterns: optional service, static singleton `self::$instance ??= Injector::get(self::class)`, array definition. | before introducing `Injector` in a class |
| `extension.md` | What plugs in: any PSR-11 container through `init()`; on-the-fly construction through an array definition. Not extensible: the class is not final but `$container` is `private` (`Injector.php:26`), a subclass cannot reach the container. | before modifying `Injector` |
