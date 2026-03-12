<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

// Manually bind some things to prevent the crash in this script
$app->singleton('files', function() { return new \Illuminate\Filesystem\Filesystem(); });
$app->singleton('cache', function($app) { return new \Illuminate\Cache\CacheManager($app); });

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

// EXPLICIT BOOTSTRAP
$reflectionKernel = new ReflectionClass($kernel);
$methodBoot = $reflectionKernel->getMethod('bootstrap');
$methodBoot->setAccessible(true);
$methodBoot->invoke($kernel);

echo "Console Kernel bootstrapped.\n";

$methodArtisan = $reflectionKernel->getMethod('getArtisan');
$methodArtisan->setAccessible(true);
$artisan = $methodArtisan->invoke($kernel);

echo "Artisan class: " . get_class($artisan) . "\n";

foreach (['help', 'route:list'] as $name) {
    try {
        $command = $artisan->get($name);
        echo "Command [$name] instance: " . get_class($command) . "\n";

        $refCommand = new ReflectionClass($command);
        if ($refCommand->hasProperty('laravel')) {
            $prop = $refCommand->getProperty('laravel');
            $prop->setAccessible(true);
            $val = $prop->getValue($command);
            echo "Command [$name] -> laravel instance is " . ($val ? "Set" : "NULL") . "\n";
        } else {
            echo "Command [$name] -> NO laravel property.\n";
        }
    } catch (\Exception $e) {
        echo "Command [$name] failed: " . $e->getMessage() . "\n";
    }
}
