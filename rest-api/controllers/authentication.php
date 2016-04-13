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

use Gawain\Classes\Auths\UserAuthManager;


$obj_UserAuthManager = new UserAuthManager($app->request->getIp());


$app->group('/authentication', function () use ($app, $obj_UserAuthManager) {


    $app->post('/authenticate', function () use ($app, $obj_UserAuthManager) {

        $str_Body = $app->request->getBody();
        $arr_Body = json_decode($str_Body, true);

        $str_Username = $arr_Body['username'];
        $str_Password = $arr_Body['password'];


        try {
            $arr_Results = $obj_UserAuthManager->authenticate($str_Username, $str_Password);
            $app->setCookie('GawainSessionID', $arr_Results['sessionID'], 0);
            $app->response->body($arr_Results['enabledCustomers']);
        } catch (Exception $exc) {
            $app->response->setStatus(401);
        }

    });


    $app->post('/login', function () use ($app, $obj_UserAuthManager) {

        $str_SessionID = $app->getCookie('GawainSessionID');
        $int_SelectedCustomer = $app->request->post('selectedCustomer');

        if ($obj_UserAuthManager->login($str_SessionID, $int_SelectedCustomer)) {
            $app->redirect($app->urlFor('activities'));
        } else {
            $app->response->setStatus(403);
        }

    });


    $app->get('/isAuthenticated', function () use ($app, $obj_UserAuthManager) {

        if ($obj_UserAuthManager->isAuthenticated($app->getCookie('GawainSessionID'))) {
            $app->response->setStatus(200);
        } else {
            $app->response->setStatus(401);
        }

    });


    $app->get('/logout', function () use ($app, $obj_UserAuthManager) {

        $str_SessionID = $app->getCookie('GawainSessionID');

        if ($obj_UserAuthManager->logout($str_SessionID)) {
            $app->deleteCookie('GawainSessionID');
            $app->redirect($app->urlFor('loginPage'));
        } else {
            $app->response->setStatus(403);
        }

    })->name('logout');

});
