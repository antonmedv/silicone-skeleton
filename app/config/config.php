<?php
/** @var $app Application */

$app['router.resource'] = array(
    $app->getRootDir() . '/src/Controller/',
);

$app['twig.path'] = array(
    $app->getRootDir() . '/view/',
);

$app['doctrine.paths'] = array(
    $app->getRootDir() . '/src/Entity/',
);

$app['security.user_class'] = 'Entity\User';
