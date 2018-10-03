<?php
ini_set('display_errors', 1);

define('__APP__', dirname(dirname(__FILE__)));
define('__ROOT__', dirname(__APP__));
$fw_path = __ROOT__ . '/fw/';
try {
    require_once($fw_path. 'Helper.php');
    require_once($fw_path. 'Response.php');
    require_once($fw_path . 'ErrorHandler.php');
    require_once($fw_path . 'Request.php');
    require_once(__APP__ . '/routes.php');

} catch (Exception $e) {
    echo '<h2>Fatal Error:</h2> ', $e->getMessage(), "\n";
}