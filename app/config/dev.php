<?php
/** @var $app Application */

require_once 'config.php';

$app['debug'] = true;

// Console
$app['console'] = $app->protect(function (\Symfony\Component\Console\Application $console) use ($app) {
    $console->add(new Silicone\Doctrine\Console\DatabaseCreateCommand($app));
    $console->add(new Silicone\Doctrine\Console\DatabaseDropCommand($app));
    $console->add(new Silicone\Doctrine\Console\SchemaCreateCommand($app));
    $console->add(new Silicone\Doctrine\Console\SchemaDropCommand($app));
    $console->add(new Silicone\Doctrine\Console\SchemaUpdateCommand($app));
    $console->add(new Silicone\Console\CacheClearCommand($app));
});

// WebProfiler
$app->register(new Silex\Provider\WebProfilerServiceProvider(), array(
    'profiler.cache_dir' => $app->getCacheDir() . '/profiler',
    'profiler.mount_prefix' => '/_profiler',
));
$app->register(new Silicone\Provider\WebProfilerServiceProvider());

