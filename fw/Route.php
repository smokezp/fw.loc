<?php

namespace fw;

class Route
{
    protected static $routes = [];
    private static $pattern = '/{(\w+)}/';


    public function name(string $name)
    {
        static::$routes[count(static::$routes) - 1]['name'] = $name;
        return $this;
    }

    public static function get(string $uri, string $target)
    {
        self::save($uri, $target, 'GET');
        return new self();
    }

    public static function getUriByRoute($route)
    {
        $found_route = [];

        foreach (static::$routes as $r) {
            if (isset($r['name']) && $r['name'] === $route['name']) {
                $found_route = $r;
                break;
            }
        }

        if (empty($found_route)) {
            new ErrorHandler(500, 'The ' . $route . ' does not exist');

        }

        if (preg_match_all(static::$pattern, $found_route['uri'], $matches)) {
            if (empty($route['dynamic_parts'])) {
                new ErrorHandler(500, 'You must set dynamic parts of your route');
            }

            if (count($route['dynamic_parts']) != count($matches[0])) {
                new ErrorHandler(500, 'Count of your dynamic parts should be the same as in route');
            }

            $uri = $found_route['uri'];
            foreach ($route['dynamic_parts'] as $dynamic_part => $value) {
                if (!strpos($uri, '{' . $dynamic_part . '}') || !$dynamic_part || !$value) {
                    new ErrorHandler(500, 'Your dynamic parts of route is incorrect');
                }
                $uri = str_replace("{" . $dynamic_part . "}", $value, $uri);
            }

        } else {
            $uri = $found_route['uri'];
        }

        if (!empty($route['params'])) {
            $uri .= '?' . http_build_query($route['params']);
        }

        return $uri;
    }

    public static function post(string $uri, string $target)
    {
        self::save($uri, $target, 'POST');
        return new self();
    }

    private static function save(string $uri, string $target, string $method)
    {
        if (substr($uri, 0, 1) !== '/') {
            new ErrorHandler(500, 'Route ' . $uri . $target . ' must start by symbol "/"');
        }

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
            if (preg_match(static::$pattern, $r['uri'])) {
                $route_parts = explode('/', $r['uri']);
                $request_parts = explode('/', $request->uri);

                if (count($route_parts) == count($request_parts)) {
                    $correct_parts = true;
                    $dynamic_parts = [];
                    foreach ($route_parts as $key => $value) {
                        preg_match(static::$pattern, $value, $match);
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

            call_user_func(array($obj, $method), $request);

        }

    }
}