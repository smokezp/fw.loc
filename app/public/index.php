<?php
ini_set('display_errors', 1);

define('__APP__', dirname(dirname(__FILE__)));
define('__ROOT__', dirname(__APP__));
define('__FW__', __ROOT__ . '/fw/');

for ($j = 0; $j < 2; $j++) {
    $start = microtime(true);
    $val = '52';
    for ($i = 0; $i < 10000000; $i++) {
        if ($j == 0) {
            $a = intval($val);
        } else {
            $a = (int)$val;
        }
    }
    $end = round(microtime(true) - $start, 4);
    echo '<br>Время выполнения скрипта #' . $j . ': ' . $end . ' сек.';
}

echo '<br>';

$array = [];
$object = new stdClass();
for ($j = 0; $j < 2; $j++) {
    $start = microtime(true);
    for ($i = 0; $i < 100000; $i++) {
        $val = 'foo' . $i;
        if ($j == 0) {
            $object->{$i} = $val;
        } else {
            $array[$i] = $val;
        }
    }

    $end = round(microtime(true) - $start, 4);
    echo '<br>Время выполнения скрипта #' . $j . ': ' . $end . ' сек.';
}

try {

    foreach (glob(__FW__ . '*.php') as $file) {
        require_once($file);
    }
    require_once(__APP__ . '/routes.php');

} catch (Exception $e) {
    echo '<h2>Fatal Error:</h2> ', $e->getMessage(), "\n";
}