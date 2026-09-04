# Integration

## PSR / generic PHP

Call `Injector::init($container)` once in your bootstrap, passing any PSR-11 `ContainerInterface` implementation:

```php
use Blackcube\Injector\Injector;

// In your bootstrap
Injector::init($container);

// Anywhere else
$service = Injector::get(MyService::class);
```

## Yii

The package ships with config-plugin support. Registration is automatic — no manual setup required.

The bootstrap file (`config/common/bootstrap.php`) calls `Injector::init($container)` at application startup:

```php
return [
    static function (ContainerInterface $container): void {
        Injector::init($container);
    },
];
```

This is triggered by the `configuration.php` config-plugin descriptor:

```php
return [
    'config-plugin' => [
        'bootstrap' => 'common/bootstrap.php',
    ],
    'config-plugin-options' => [
        'source-directory' => 'config',
    ],
];
```
