<?php
/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2015 Infected <http://infected.no/>.
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
require_once 'interfaces/page.php';
require_once 'traits/page.php';

class ResetPasswordPage implements IPage {
	use TPage;

	public function getTitle() {
		return null;
	}

	public function getContent() {
		$content = null;
		$content .= '<script src="scripts/seatmapEditor.js"></script>';

        if (!Session::isAuthenticated()) {
            $content .= '<script src="../api/scripts/reset-password.js"></script>';

            $content .= '<body class="register-page">';
                $content .= '<div class="register-box">';
                    $content .= '<div class="register-logo">';
                        $content .= '<a href="."><b>' . Settings::name . '</b> Crew</a>';
                    $content .= '</div>';   

                    $content .= '<div class="register-box-body">';
                        $content .= '<p class="login-box-msg">Fyll ut skjemaet for å registrere deg.</p>';

                        $content .= '<form class="request-reset-password" method="post">';
                            $content .= '<div class="form-group has-feedback">';
                                $content .= '<input type="text" class="form-control" name="identifier" placeholder="E-post" required>';
                                $content .= '<span class="glyphicon glyphicon-envelope form-control-feedback"></span>';
                            $content .= '</div>';
                            $content .= '<button type="submit" class="btn btn-primary btn-block btn-flat">' . Localization::getLocale('reset_password') . '</button>';
                        $content .= '</form>';
                    $content .= '</div><!-- /.form-box -->';
                $content .= '</div><!-- /.register-box -->';

                $content .= '<script src="../api/scripts/register.js"></script>';
            $content .= '</body>';

            /*
            if (!isset($_GET['code'])) {
                $content .= '<h2>' . Localization::getLocale('forgot_password') . '?</h2>';
                $content .= '<form class="request-reset-password" method="post">';
                    $content .= '<p>' . Localization::getLocale('enter_your_username_or_email_in_order_to_reset_your_password') . ': <input type="text" name="identifier"></p>';
                    $content .= '<input type="submit" value="' . Localization::getLocale('reset_password') . '">';
                $content .= '</form>';
            } else {    
                $content .= '<h2>' . Localization::getLocale('reset_password') . '</h2>';
                $content .= '<p>' . Localization::getLocale('enter_a_new_password') . '</p>';
                
                $content .= '<form class="reset-password" method="post">';
                    $content .= '<input type="hidden" name="code" value="' . $_GET['code'] . '">';
                    $content .= '<table>';
                        $content .= '<tr>';
                            $content .= '<td>' . Localization::getLocale('new_password') . ':</td>';
                            $content .= '<td><input type="password" name="password"></td>';
                        $content .= '</tr>';
                        $content .= '<tr>';
                            $content .= '<td>' . Localization::getLocale('repeat_password') . ':</td>';
                            $content .= '<td><input type="password" name="confirmpassword"></td>';
                        $content .= '</tr>';
                        $content .= '<tr>';
                            $content .= '<td><input type="submit" value="' . Localization::getLocale('change') . '"></td>';
                        $content .= '</tr>';
                    $content .= '</table>';
                $content .= '</form>';
            }
            */
        } else {
            $content .= Localization::getLocale('since_you_are_already_logged_in_you_it_seems_like_you_remember_your_password_after_all');
        }

		return $content;
	}
}

/*
<div class="container">
		<div class="row">
				<div class="row">
						<div class="col-md-4 col-md-offset-4">
								<div class="panel panel-default">
										<div class="panel-body">
												<div class="text-center">
													<img src="https://cloud.digitalocean.com/assets/cloud-logo-0efc9110ac89b1ea38fc7ee2475a3e87.svg" class="login" height="70">
													<h3 class="text-center">Forgot Password?</h3>
													<p>If you have forgotten your password - reset it here.</p>
														<div class="panel-body">
															
															<form class="form"><!--start form--><!--add form action as needed-->
																<fieldset>
																	<div class="form-group">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
																			<!--EMAIL ADDRESS-->
																			<input id="emailInput" placeholder="email address" class="form-control" oninvalid="setCustomValidity('Please enter a valid email address!')" onchange="try{setCustomValidity('')}catch(e){}" required="" type="email">
																		</div>
																	</div>
																	<div class="form-group">
																		<input class="btn btn-lg btn-primary btn-block" value="Send My Password" type="submit">
																	</div>
																</fieldset>
															</form><!--/end form-->
															
														</div>
												</div>
										</div>
								</div>
						</div>
				</div>
		</div>
</div>
*/
?>