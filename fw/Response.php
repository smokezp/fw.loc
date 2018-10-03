<?php

namespace fw;

class Response
{
    protected $template;
    protected $url;
    private $type;

    private $redirect = 'redirect';
    private $view = 'view';
    private $json = 'json';

    public function redirect(string $url)
    {
        $this->url = $url;
        $this->type = $this->redirect;
    }

    public function json($data)
    {
        $this->type = $this->json;
    }

    public function view(string $tpl)
    {
        $this->template = $tpl;
        $this->type = $this->view;
    }

    public function send()
    {
        switch ($this->type) {
            case $this->view:
                $file_view = __APP__ . '/public/views/' . $this->template . '.html';
                echo file_get_contents($file_view);
                break;
            case $this->redirect:
                header("Location: " . $this->url);
                break;
        }
    }
}