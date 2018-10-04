<?php

use fw\Response;

function dd($debug)
{
    echo '<pre>';
    print_r($debug);
    echo '</pre>';
    die();
}

function response()
{
    return new Response();
}
