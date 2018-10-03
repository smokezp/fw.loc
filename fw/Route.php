<?php

namespace fw;

class Route
{
    public static function get(string $uri, string $target)
    {
        self::save($uri, $target, 'GET');
    }

    public static function post(string $uri, string $target)
    {
        self::save($uri, $target, 'POST');
    }

    private static function save(string $uri, string $target, string $method)
    {
        $GLOBALS['routes'][] = compact('uri', 'target', 'method');
    }

    public static function load()
    {
        if (!isset($GLOBALS['routes'])) {
            echo 'routes does not exist';
            return;
        }

        $route = array();
        $exist_route = false;
        foreach ($GLOBALS['routes'] as $r) {
            if ($_SERVER['REQUEST_URI'] === $r['uri'] && $_SERVER['REQUEST_METHOD'] === $r['method']) {
                $exist_route = true;
                $route = $r;
                break;
            }
        }

        if (!$exist_route) {
            echo '404';
            return;
        } else {

            $array = explode('@', $route['target']);
            $controller = $array[0];
            $method = $array[1];
            $file_controller = __ROOT__ . '/Http/Controllers/' . $controller . '.php';

            if (!file_exists($file_controller)) {
                echo 'controller does not exist';
                return;
            }

            require_once $file_controller;

            if (!class_exists($controller)) {
                echo 'class does not exist';
                return;
            }

            $obj = new $controller;

            if (!method_exists($obj, $method)) {
                echo 'method does not exist';
                return;
            }
            $view = call_user_func(array($obj, $method));

            $file_view = __ROOT__ . '/public/views/' . $view . '.html';
            echo file_get_contents($file_view);
        }

    }
}