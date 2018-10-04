<?php

namespace fw;

class Route
{
    protected static $routes = [];

    public $name;

    public function name(string $name) {
        $this->name = $name;
    }

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
            if (preg_match('/\/{(\w+)}/', $r['uri'])) {
                $route_parts = explode('/', $r['uri']);
                $request_parts = explode('/', $request->uri);

                if (count($route_parts) == count($request_parts)) {
                    $correct_parts = true;
                    $dynamic_parts = [];
                    foreach ($route_parts as $key => $value) {
                        preg_match('/{(\w+)}/', $value, $match);
                        if (empty($match)) {
                            if ($request_parts[$key] == $value) {
                                $correct_parts = true;
                            } else {
                                $correct_parts = false;
                                break;
                            }
                        } else {
                            $dynamic_parts[$match[1]] = $request_parts[$key];
                        }
                    }

                    if ($correct_parts) {
                        $exist_route = true;
                        $route = $r;
                        $request->dynamic_parts = $dynamic_parts;
                        unset($dynamic_parts);
                        break;
                    }

                    unset($dynamic_parts);
                }
            } else {
                if ($request->uri === $r['uri'] && $request->method === $r['method']) {
                    $exist_route = true;
                    $route = $r;
                    break;
                }
            }
        }

        if (!$exist_route) {
            new ErrorHandler(404, 'Page not found');
        } else {

            @list($controller, $method) = explode('@', $route['target']);

            if (!$controller || !$method) {
                new ErrorHandler(500, 'Incorrect target "' . $route['target'] . '" for current uri');
            }

            $file_controller = __APP__ . '/Http/Controllers/' . $controller . '.php';

            if (!file_exists($file_controller)) {
                new ErrorHandler(500, 'Controller ' . $controller . ' does not exist here ' . $file_controller);
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
            if (!$response instanceof Response) {
                new ErrorHandler(500, 'Method ' . $method . ' must return instance of ' . Response::class .
                    ' in file ' . $file_controller);

            }

            $response->send();
        }

    }
}