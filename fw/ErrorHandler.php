<?php

namespace fw;

use Exception;

class ErrorHandler
{
    private $code;
    private $msg;
    private $full_msg;

    function __construct(int $code, $msg)
    {
        $this->code = $code;
        $this->msg = $msg;
        $this->full_msg = '<br>Code: ' . $this->code . ' <br>Message: ' . $this->msg;
        $this->generateLog();
        throw new Exception($this->full_msg);
    }

    private function generateLog()
    {
        $logs_dir = __FW__ . 'logs/';
        if (!file_exists($logs_dir)) mkdir($logs_dir, 0777);

        $filename = date('d-m-Y');
        $file = $logs_dir . $filename;
        $f = fopen($file, "w");
        fwrite($f, '[' . date('H-i-s') . '] ' . $this->full_msg);
        fclose($f);
    }
}