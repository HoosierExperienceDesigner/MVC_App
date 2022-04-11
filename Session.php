<?php

namespace app\core;

class Session
{
    protected const FLASH_KEY = 'flash_messages';

    // SESSION CONSTRUCTOR
    public function __construct()
    {
        session_start();

        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

        foreach ($flashMessages as $key => &$flashMessage) // take flashMessage by reference
        {
            //Mark to be removed - modifying a copy of array
            $flashMessage['remove'] = true;
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;

        //echo '<pre>';
        //var_dump($_SESSION[self::FLASH_KEY]);
        //echo'</pre>';
    }

    public function setFlash($key, $message)
    {
        // Want a unique key for flash message
        //$_SESSION[self::FLASH_KEY][$key] = $message;
        $_SESSION[self::FLASH_KEY][$key] = [
            'removed' => false, // default this property to false
            'value' => $message
        ];
    }

    public function getFlash($key)
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }


    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? false;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }



    public function __destruct()
    {
        //Iterate over marked to be removed
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

        foreach ($flashMessages as $key => &$flashMessage) // take flashMessage by reference
        {
            if($flashMessage['remove'])
            {
                unset($flashMessages[$key]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

}