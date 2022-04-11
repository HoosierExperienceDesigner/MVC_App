<?php

/**
 * @author   DrewA
 * @package  app\core
 * @Date     09-Apr-22
 */

namespace app\core;

use app\core\exception\NotFoundException;

class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response=$response;
    }

    public function get($path, $callback) // When path = this, This callback executed
    {
        $this->routes['get'][$path] = $callback;
    }
    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
       $path = $this->request->getPath();
       $method = $this->request->method();
       // Now from these routes - determine the callback
        $callback = $this->routes[$method][$path] ?? false;
        if($callback === false ) {
            throw new NotFoundException();

        }
        if(is_string($callback)){ //Note - POST callback is an array, GET callbacks are strings
            return Application::$app->view->renderView($callback);
        }
        if(is_array($callback)){
            //$instance = new $callback[0]();
            //$callback[0] = new $callback[0](); // callback becomes an object instance of the controller
            //We can set this ...
            //Application::$app->controller = new $callback[0]();
            //Application::$app->controller->action-> $callback[1];
            //$callback[0] = Application::$app->controller;

            /**
             * @var \app\core\Controller $controller
             */

            $controller =  new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];

            foreach ($controller->getMiddlewares() as $middleware)
            {
                $middleware->execute(); // Throws the overridden exception property on file execution
            }
            $callback[0] = $controller;
        }
        return call_user_func($callback, $this->request, $this->response);
    }

    /*public function renderView($view, $params=[])
    {
        // Want to render view inside layout - and we need to access the layout content to do so
        //$layoutContent = $this->layoutContent();
        //$viewContent = $this->renderOnlyView($view, $params);
        //return str_replace('{{content}}', $viewContent, $layoutContent); // replaces {{content}} placeholder
        //return Application::$app->view->renderView($view, $params);
    }

    protected function layoutContent()
    {
        /// $layout = Application::$app->controller->layout; -------- Causes issues when implementing profile
        ///
        /// If the controller exists - use the controller layout
        ///
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
    */
}