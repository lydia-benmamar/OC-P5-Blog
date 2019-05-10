<?php

define('ROOT', dirname(__DIR__));

require_once ROOT . '/vendor/autoload.php';

require_once ROOT . '/src/TwigAddon/TwigAdd.php';

use App\Controller\FrontController;
use Tracy\Debugger;

Debugger::enable();
Debugger::$strictMode = true;

$test = new FrontController();

$test->run();
