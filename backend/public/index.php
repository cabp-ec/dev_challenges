<?php

require_once('../lib/autoload.php');

$ds = DIRECTORY_SEPARATOR;
$pathSettings = dirname(__DIR__) . $ds . "settings$ds";
unset($ds);

$settings = [
    'di' => include_once $pathSettings . 'di.php',
    'routes' => include_once $pathSettings . 'routes.php',
    'pipelines' => include_once $pathSettings . 'pipelines.php',
];

$poker = \App\Kernel::getInstance();
$poker->setup($settings);
$poker->run();
