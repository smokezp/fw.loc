<?php
ini_set('display_errors', 1);

define('__APP__', dirname(dirname(__FILE__)));
define('__ROOT__', dirname(__APP__));
define('__FW__', __ROOT__ . '/fw/');

try {
    require_once(__FW__. 'Helper.php');
    require_once(__FW__. 'Response.php');
    require_once(__FW__ . 'ErrorHandler.php');
    require_once(__FW__ . 'Request.php');
    require_once(__APP__ . '/routes.php');

} catch (Exception $e) {
    echo '<h2>Fatal Error:</h2> ', $e->getMessage(), "\n";
}