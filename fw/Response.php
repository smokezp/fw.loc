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
                if (!$this->template)
                    new ErrorHandler(500, 'You must add template');

                $file_view = __APP__ . '/public/views/' . $this->template . '.html';
                echo file_get_contents($file_view);
                break;
            case $this->redirect:
                if (!$this->url)
                    new ErrorHandler(500, 'You must add url');

                header("Location: " . $this->url);
                break;
            default:
                new ErrorHandler(500, 'You must use some method');
                break;
        }
    }
}