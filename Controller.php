<?php

/**
 * @author   DrewA
 * @Date     09-Apr-22
 */

namespace app\core;

use app\core\middlewares\BaseMiddleware;

class Controller
{
    //Controller Member Data
    public string $layout = 'main';
    public string $action = '';
    /**
     * @var \app\core\middlewares\BaseMiddleware[]
     */
    protected array $middlewares = [];

    //Controller function members
    public function setLayout($layout)
    {
        $this->layout=$layout;
    }
    public function render($view, $params =[])
    {
        return Application::$app->view->renderView($view, $params);
    }

    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

}