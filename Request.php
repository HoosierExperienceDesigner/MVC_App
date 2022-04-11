<?php

/**
 * @author   DrewA
 * @package  app\core
 * @Date     09-Apr-22
 */

namespace app\core;

class Request
{
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if($position === false ) {
             return $path;
        }
        /*
        echo '<pre>';
        var_dump($position);
        echo '</pre>';
        exit;
        */
        return substr($path, 0, $position);
    }

    public function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isGet()
    {
        return $this->method() === 'get';
    }

    public function isPost()
    {
        return $this->method() === 'post';
    }

    public function getBody()
    {

        $body=[];

        if($this->method() ==='get')
        {
            //iterate over super global $_GET as key value pairs and sanitize
            foreach($_GET as $key => $value)
            {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if($this->method() ==='post')
        {
            //iterate over super global $_POST as key value pairs and sanitize
            foreach($_POST as $key => $value)
            {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }
}