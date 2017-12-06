<?php
/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2017 Infected <http://infected.no/>.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3.0 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'session.php';
require_once 'settings.php';
require_once 'page.php';

class LoginPage extends Page {
	public function getTitle(): string {
		return 'Login';
	}

    public function getContent(): string {
        $content = null;

        if (!Session::isAuthenticated()) {
            $content .= '<body class="hold-transition login-page">';
                $content .= '<div class="login-box">';
                    $content .= '<div class="login-logo">';
                        $content .= '<a href="."><b>' . Settings::name . '</b> Crew</a>';
                    $content .= '</div>';
                    $content .= '<div class="login-box-body">';
                        $content .= '<p class="login-box-msg">Du kan bruke samme bruker overalt hos <b>' . Settings::name . '</b>.</p>';
                        $content .= '<form class="login" method="post">';
                            $content .= '<div class="form-group has-feedback">';
                                $content .= '<input type="text" name="identifier" class="form-control" placeholder="Brukernavn eller e-post">';
                                $content .= '<span class="glyphicon glyphicon-envelope form-control-feedback"></span>';
                            $content .= '</div>';
                            $content .= '<div class="form-group has-feedback">';
                                $content .= '<input type="password" name="password" class="form-control" placeholder="Passord">';
                                $content .= '<span class="glyphicon glyphicon-lock form-control-feedback"></span>';
                            $content .= '</div>';
                            $content .= '<div class="row">';
                                $content .= '<div class="col-xs-8">';
                                    /*
                                    $content .= '<div class="checkbox">';
                                        $content .= '<label><input type="checkbox"> Husk meg</label>';
                                    $content .= '</div>';
                                    */
                                $content .= '</div>'; // .col-xs-8
                                $content .= '<div class="col-xs-4">';
                                    $content .= '<button class="btn btn-primary btn-block btn-flat">Logg inn</button>';
                                $content .= '</div>'; // .col-xs-4
                            $content .= '</div>';
                        $content .= '</form>';
                        $content .= '<a href="?page=reset-password">Jeg har glemt passordet mitt!</a><br>';
                        $content .= '<a href="?page=register">Register ny bruker</a>';
                    $content .= '</div>';
                $content .= '</div>';

                $content .= '<script>';
                    $content .= '$(function () {';
                        $content .= '$(\'input\').iCheck({';
                            $content .= 'checkboxClass: \'icheckbox_square-blue\',';
                            $content .= 'radioClass: \'iradio_square-blue\',';
                        $content .= '});';
                    $content .= '});';
                $content .= '</script>';
                $content .= '<script src="pages/scripts/login.js"></script>';
            $content .= '</body>';
        }

    	return $content;
	}
}