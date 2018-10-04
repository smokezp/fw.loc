<?php

namespace fw;

class Response
{
    protected $template;
    protected $url;
    protected $route;
    private $type;

    private $redirect_url = 'redirect_url';
    private $redirect_route = 'redirect_route';
    private $view = 'view';
    private $json = 'json';

    public function redirectUrl(string $url)
    {
        $this->url = $url;
        $this->type = $this->redirect_url;
        return $this;
    }

    public function redirectRoute(string $name, $dynamic_parts = [], $params = [])
    {
        $this->type = $this->redirect_route;
        $this->route = compact('name', 'dynamic_parts', 'params');
        return $this;
    }

    public function json($data)
    {
        $this->type = $this->json;
        return $this;
    }

    public function view(string $tpl)
    {
        $this->template = $tpl;
        $this->type = $this->view;
        return $this;
    }

    public function send()
    {
        switch ($this->type) {
            case $this->view:
                if (!$this->template)
                    new ErrorHandler(500, 'You must add template');

                $file_view = __APP__ . '/public/views/' . $this->template . '.html';
                echo file_get_contents($file_view);
                break;
            case $this->redirect_url:
                if (!$this->url)
                    new ErrorHandler(500, 'You must add url');

                header("Location: " . $this->url);
                break;
            case $this->redirect_route:
                if (!$this->route)
                    new ErrorHandler(500, 'You must add route');

                $route = Route::getUriByRoute($this->route);

                header("Location: " . $route);
                break;
            default:
                new ErrorHandler(500, 'You must use some method');
                break;
        }
    }
}