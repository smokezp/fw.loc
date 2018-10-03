<?php

namespace fw;


class Request
{
    public $method;
    public $uri;
    public $params = [];

    function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];

        switch ($this->method) {
            case 'GET':
                @list($uri_part, $params) = explode('?', $_SERVER['REQUEST_URI'], 2);
                $this->uri = $uri_part;
                $this->params = $_GET;
                break;
            case 'POST':
                $this->params = $_POST;
                $this->uri = $_SERVER['REQUEST_URI'];
                break;
        }
    }
}