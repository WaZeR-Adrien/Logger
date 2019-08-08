<?php
namespace Src;

class LogException extends \Exception
{
    const ERROR_DURING_PUT_IN_FILE = 100;
    const CANT_PARSE_FILE = 101;
}
