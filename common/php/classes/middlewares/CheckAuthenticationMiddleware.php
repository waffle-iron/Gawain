<?php
/**
 * Gawain
 * Copyright (C) 2016  Stefano Romanò (rumix87 (at) gmail (dot) com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Gawain\Classes\Middlewares;

require_once(PHP_CLASSES_DIR . 'auths/UserAuthManager.php');
require_once(PHP_VENDOR_DIR . 'Slim/Slim.php');

use Slim\Middleware;
use Slim\Slim;
use Gawain\Classes\Auths\UserAuthManager;

Slim::registerAutoloader();


class CheckAuthenticationMiddleware extends Middleware
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
