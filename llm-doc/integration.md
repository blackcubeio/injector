# injector — Integration

## 1. Initialisation

| Environment | Who calls `init()` | Where |
|---|---|---|
| Yii 3 (config-plugin) | the package, automatically | `bootstrap` group, `config/common/bootstrap.php:17-19` |
| Other PSR-11 container | the application, manually | its bootstrap, after the container is built |

Yii:
- The package's `bootstrap` group runs only if the application declares `bootstrap` and references it in `bootstrap-web` / `bootstrap-console` (`starter/config/configuration.php:39-41`).
- The HTTP runner runs the bootstrap before handling the request (`yii-runner-http/src/HttpApplicationRunner.php:204`, then `:219`).
- Before the `bootstrap` group (container construction), `get()` throws `RuntimeException`.

Other container:

```php
use Blackcube\Injector\Injector as BlackcubeInjector;

BlackcubeInjector::init($container);
```

- Slim: `demo-slim/config/bootstrap.php:21`.
- Laravel: in the service provider's `boot()`, `BlackcubeInjector::init($this->app)` (`ssr/src/Laravel/BlackcubeServiceProvider.php:138`).

## 2. When to use `Injector`

| Context | `Injector` |
|---|---|
| Class built by the container (Handler, Service, Middleware…) | no: constructor injection |
| Static method / static factory (`CacheFile::from()`, `RouteHelper::create()`, `BridgeFormModel::formHydrator()`) | yes |
| Trait without a constructor (`BaseElasticTrait`) | yes |
| Object created outside the container (AR query `ScopableQuery`, dcore scopes) | yes |

## 3. The 3 patterns

**Optional service.** Use `has()` on an interface: with yiisoft/di, `has()` on a concrete class is always `true`.

```php
if (Injector::has(LoggerInterface::class) === true) {
    Injector::get(LoggerInterface::class)->error('Elastic schema could not be parsed', [
        'exception' => $e,
    ]);
}
```

Source: `active-record/src/Elastic/BaseElasticTrait.php:149-153`.

**Static singleton.** The instance comes from the container (all its dependencies) and is memoised.

```php
public static function create(): static
{
    if (static::$instance === null) {
        static::$instance = Injector::get(static::class);
    }
    return static::$instance;
}
```

- Source: `dboard/src/Helpers/RouteHelper.php:66-72`.
- Prototype variant: `CacheFile::from()` memoises the instance, then returns a configured `clone` (`fileprovider/src/CacheFile.php:59-71`).

**Array definition.** Some constructor arguments are given, the others come from the container (measured: an omitted argument receives the container's shared instance).

```php
self::$formHydrator = Injector::get([
    'class' => FormHydrator::class,
    '__construct()' => [
        'hydrator' => new ElasticHydrator(...),
    ],
]);
```

- Source: `bridge-form-model/src/BridgeFormModel.php:72-92`.
- `validator` (not given) is resolved by the container.
