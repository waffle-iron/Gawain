<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'auths/UserAuthManager.php');
require_once(PHP_VENDOR_DIR . 'Slim/Slim.php');


\Slim\Slim::registerAutoloader();


class CheckAuthenticationMiddleware extends \Slim\Middleware
{

    public function call()
    {

        // Get reference to application
        $app = $this->app;


        // List of exceptions that needs to pass through the check
        $arr_RoutesExceptions = array(
            'login',
            'authentication'
        );


        // Check if the current request is the list of exceptions... in this case pass through the middleware
        foreach ($arr_RoutesExceptions as $str_RouteException) {
            if (strpos($this->app->request()->getPathInfo(), $str_RouteException) !== false) {
                $this->next->call();

                return;
            }
        }

        // Check if GawainSession cookie exists
        $str_SessionCookie = $app->getCookie('GawainSessionID');

        $obj_AuthManager = new UserAuthManager($app->request->getHost());


        // Check if path is part of REST API, in this case no redirect is performed but only a halt
        $bool_IsApi = strpos($this->app->request()->getPathInfo(), 'rest-api') !== false;


        // If cookie is null, redirects to login
        if (is_null($str_SessionCookie)) {
            if ($bool_IsApi) {
                $app->response->setStatus(401);
            } else {
                $app->response->redirect($app->urlFor('loginPage'), 401);
            }
        } elseif (!$obj_AuthManager->isAuthenticated($str_SessionCookie)) { // If session is not valid, redirect to login
            if ($bool_IsApi) {
                $app->response->setStatus(401);
            } else {
                $app->response->redirect($app->urlFor('loginPage'), 401);
            }
        } else {
            $this->next->call();
        }

    }

}