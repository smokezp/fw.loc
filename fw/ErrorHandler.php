<?php

namespace fw;

use Exception;

class ErrorHandler
{
    function __construct($code, $msg)
    {
        throw new Exception('<br>Code: ' . $code . ' <br>Message: ' . $msg);
    }
}