<?php

namespace fw;

class Route
{
    protected static $routes = [];

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
        static::$routes[] = compact('uri', 'target', 'method');
    }

    public static function load()
    {
        if (empty(static::$routes)) {
             new ErrorHandler(500, 'Routes is missing');
        }

        $route = array();
        $exist_route = false;
        $request = new Request();
        foreach (static::$routes as $r) {
            if ($request->uri === $r['uri'] && $request->method === $r['method']) {
                $exist_route = true;
                $route = $r;
                break;
            }
        }

        if (!$exist_route) {
            new ErrorHandler(404, 'Page not found');
        } else {

            @list($controller, $method) = explode('@', $route['target']);

            if (!$controller || !$method) {
                new ErrorHandler(500, 'Incorrect target "'.$route['target'].'" for current uri');
            }

            $file_controller = __APP__ . '/Http/Controllers/' . $controller . '.php';

            if (!file_exists($file_controller)) {
                new ErrorHandler(500, 'Controller ' . $controller . ' does not exist here '. $file_controller);
            }

            require_once $file_controller;

            if (!class_exists($controller)) {
                new ErrorHandler(500, 'Class ' . $controller . ' does not exist in file ' . $file_controller);
            }

            $obj = new $controller;

            if (!method_exists($obj, $method)) {
                new ErrorHandler(500, 'Method ' . $method . ' does not exist in class ' . $controller .
                    ' in file ' . $file_controller);

            }

            $response = call_user_func(array($obj, $method), $request);
            $response->send();
        }

    }
}