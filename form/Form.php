<?php

namespace app\core\form;

class Form
{
    public static function begin($action, $method) // NOTE: Coming from the register.php's perspective
    {
        echo sprintf('<form action="%s" method="%s">', $action, $method);
        //Return an instantiated object of Form
        return new Form();
    }

    public static function end()
    {
        echo '</form>';
    }

    public function field($model, $attribute)
    {
        return new InputField($model, $attribute);
    }

}