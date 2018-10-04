<?php

use fw\Request;


class SiteController
{
    public function index(Request $request)
    {
        response()->view('index')->send();
    }

    public function index2(Request $request)
    {
        response()->redirectUrl('/sddssdsd/sassas')->send();
    }

    public function index3(Request $request)
    {
        response()->redirectRoute('dddd', ['sdsdsdsd' => 'ddd1'], ['sdddd' => 'sdd22'])->send();
    }
}