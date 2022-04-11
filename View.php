<?php

namespace app\core;

class View
{
    // View renderings should happen in View class,
    // THus, main.php so $this corresponds with the view class instance.
    public string $title = '';


    public function renderView($view, $params=[])
    {
        // Want to render view inside layout - and we need to access the layout content to do so
        $viewContent = $this->renderOnlyView($view, $params);

        $layoutContent = $this->layoutContent();

        return str_replace('{{content}}', $viewContent, $layoutContent); // replaces {{content}} placeholder
    }

    public function renderContent($viewContent)
    {
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent); // replaces {{content}} placeholder
    }

    protected function layoutContent()
    {

        $layout = Application::$app->layout; // app-layout defaults to string 'main'
        if (Application::$app->controller)
        {
            $layout = Application::$app->controller->layout;
        }

        ob_start(); // start cache of ouput
        include_once Application::$ROOT_DIR."/views/layouts/$layout.php"; // inject layout to get actual output we want to render
        return ob_get_clean();
    }

    protected function renderOnlyView($view, $params)
    {
        foreach ($params as $key => $value) {
            $$key = $value; // key evaluates as $name variable
        }

        ob_start(); // start cache of ouput
        include_once Application::$ROOT_DIR."/views/$view.php"; // the actual output we want to render
        return ob_get_clean();

    }
}