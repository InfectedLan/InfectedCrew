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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.	If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'session.php';
require_once 'settings.php';
require_once 'localization.php';
require_once 'page.php';

class PasswordResetPage extends Page {
    public function isPublic(): bool {
        return !Session::isAuthenticated();
    }

    public function getContent(User $user = null): string {
		$content = null;
        $content .= '<body class="register-page">';
            $content .= '<div class="register-box">';
                $content .= '<div class="register-logo">';
                    $content .= '<a href="."><b>' . Settings::name . '</b> Crew</a>';
                $content .= '</div>';
                $content .= '<div class="register-box-body">';

                    if (isset($_GET['code'])) {
                        $code = $_GET['code'];

                        $content .= '<p class="login-box-msg">' . Localization::getLocale('enter_a_new_password') . '</p>';
                        $content .= '<form class="password-reset-edit">';
                            $content .= '<input type="hidden" name="code" value="' . $code . '">';
                            $content .= '<div class="form-group has-feedback">';
                                $content .= '<input type="password" class="form-control" name="password" placeholder="' . Localization::getLocale('new_password') . '" required>';
                                $content .= '<span class="glyphicon glyphicon-lock form-control-feedback"></span>';
                            $content .= '</div>';
                            $content .= '<div class="form-group has-feedback">';
                                $content .= '<input type="password" class="form-control" name="confirm-password" placeholder="' . Localization::getLocale('repeat_password') . '" required>';
                                $content .= '<span class="glyphicon glyphicon-repeat form-control-feedback"></span>';
                            $content .= '</div>';
                            $content .= '<div class="row">';
                                $content .= '<div class="col-md-8">';
                                $content .= '</div>';
                                $content .= '<div class="col-md-4">';
                                    $content .= '<button type="submit" class="btn btn-primary btn-block btn-flat">' . Localization::getLocale('change') . '</button>';
                                $content .= '</div>';
                            $content .= '</div>';
                        $content .= '</form>';
                    } else {
                        $content .= '<p class="login-box-msg">Fyll ut skjemaet for å registrere deg.</p>';
                        $content .= '<form class="password-reset-create">';
                            $content .= '<div class="input-group">';
                                $content .= '<div class="form-group has-feedback">';
                                    $content .= '<input type="email" class="form-control" name="identifier" placeholder="E-post" required>';
                                    $content .= '<span class="glyphicon glyphicon-envelope form-control-feedback"></span>';
                                $content .= '</div>';
                                $content .= '<span class="input-group-btn">';
                                    $content .= '<button type="submit" class="btn btn-primary btn-block btn-flat">' . Localization::getLocale('reset_password') . '</button>';
                                $content .= '</span>';
                            $content .= '</div>';
                        $content .= '</form>';
                    }

                $content .= '</div>';
            $content .= '<script src="pages/scripts/password-reset.js"></script>';
        $content .= '</body>';

		return $content;
	}
}