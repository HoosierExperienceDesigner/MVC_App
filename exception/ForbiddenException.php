<?php

namespace app\core\exception;

class ForbiddenException extends \Exception
{
    protected $code =403;
    protected $message = 'You don\'t have access to this page o __ o ';
}