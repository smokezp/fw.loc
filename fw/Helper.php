<?php

function dd($debug)
{
    echo '<pre>';
    print_r($debug);
    echo '</pre>';
    die();
}