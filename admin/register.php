<?php

$dir = str_replace('\\', '/', dirname(__DIR__));

include $dir . '/app/start.php';

require $view_root . 'register.php';
