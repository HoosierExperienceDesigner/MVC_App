<?php

/**
 * @author   DrewA
 * @package  app\core
 * @Date     09-Apr-22
 */

namespace app\core;

use app\models\User;

class Application
{
    public static string $ROOT_DIR;
    public string $layout = 'main';
    public string $userClass;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public Database $db;
    public ?UserModel $user; // ? for null possibility
    public View $view;
    public static Application $app;
    public ?Controller $controller = null;

    public function __construct($rootPath, array $config) // <-$userClass inside config var
    {
        $this->user=null;
        $this->userClass = $config['userClass']; // Makes userClass app\models\user
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session(); // <- When Application constructor called in index.php will also call Session constructor - starting session with cookie
        // ^ Also will trigger foreach loop that marks messages for removal, destruction, after processing is complete.
        $this->router = new Router($this->request, $this->response); // create instance of the router in Application constructor
        $this->view = new View();

        $this->db = new Database($config['db']);


        $userId = $this->session->get('user');
        if($userId)
        {
            $primaryKey = $this->userClass::primaryKey();

            $this->user = $this->userClass::findOne([$primaryKey=>$userId]);

        } else {
            $this->user=null;
        }
    }

    public static function isGuest()
    {
        return !self::$app->user;
    }

    public function run()
    {
        try{
            echo $this->router->resolve();
        }catch(\Exception $e){
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renderView('_error',[
                'exception'=>$e
            ]);
        }
    }

    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    public function login(UserModel $user)
    {
        //get the id and save it in the session
        //$this->user = $user;
        //$primaryKey = $user->primaryKey(); // 'id'
        //$primaryValue = $user->{$primaryKey};
        //$this->session->set('user', $primaryValue);
        $this->user = $user;
        $className = get_class($user);
        $primaryKey = $className::primaryKey(); //instead of ::
        $value = $user->{$primaryKey};
        Application::$app->session->set('user', $value);
        return true;

    }

    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }


}