<?php

use fw\Request;
use fw\Response;

class SiteController
{
    public function index(Request $request)
    {
        $response = new Response();
        $response->view('index');
        return $response;
    }

    public function index2(Request $request)
    {
        $response = new Response();
        $response->redirect('sddsds');
        return $response;
    }
}