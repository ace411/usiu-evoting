<?php

$base_url = htmlspecialchars('');

$dir_specific = htmlspecialchars('../../');

$img_url = $base_url . 'app/img/'; 

$js_url = $base_url . 'app/js/';

$css_url = $base_url . 'app/css/';

$font_url = $base_url . 'app/fonts/';

$dir_css = $dir_specific . 'app/css/';

$dir_font = $dir_specific . 'app/fonts/';

$dir_js = $dir_specific . 'app/js/';

$dir_img = $dir_specific . 'app/img/';

$app_root = str_replace('\\', '/', htmlspecialchars(__DIR__));

$view_root = $app_root . '/views/'; 

$admin_root = $app_root . '/admin/';

$voter_root = $app_root . '/voter/';

require $app_root . '/objects/autoload.php';
