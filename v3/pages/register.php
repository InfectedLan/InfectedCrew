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
require_once 'utils/dateutils.php';
require_once 'page.php';

class RegisterPage extends Page {
	public function getTitle(): ?string {
		return 'Register';
	}

    public function getContent(User $user = null): string {
		$content = null;

		if (!Session::isAuthenticated()) {
			$content .= '<body class="register-page">';
                $content .= '<div class="modal modal-danger fade">';
                    $content .= '<div class="modal-dialog">';
                        $content .= '<div class="modal-content">';
                            $content .= '<div class="modal-header">';
                                $content .= '<h4 class="modal-title">Feilmelding</h4>';
                            $content .= '</div>';
                            $content .= '<div class="modal-body"></div>';
                                $content .= '<div class="modal-footer">';
                                    $content .= '<button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Gå tilbake</button>';
                                $content .= '</div>';
                            $content .= '</div>';
                        $content .= '</div>';
                    $content .= '</div>';
                $content .= '</div>';
                $content .= '<div class="modal modal-success fade">';
                    $content .= '<div class="modal-dialog">';
                        $content .= '<div class="modal-content">';
                            $content .= '<div class="modal-header">';
                                $content .= '<h4 class="modal-title">Registrering fullført</h4>';
                            $content .= '</div>';
                            $content .= '<div class="modal-body"></div>';
                                $content .= '<div class="modal-footer">';
                                    $content .= '<button type="button" class="btn btn-outline pull-left" data-dismiss="modal" onclick="$(location).attr(\'href\', \'.\')">Gå tilbake</button>';
                                $content .= '</div>';
                            $content .= '</div>';
                        $content .= '</div>';
                    $content .= '</div>';
                $content .= '</div>';
				$content .= '<div class="register-box">';
					$content .= '<div class="register-logo">';
						$content .= '<a href="."><b>' . Settings::name . '</b> Crew</a>';
					$content .= '</div>';
					$content .= '<div class="register-box-body">';
						$content .= '<p class="login-box-msg">Fyll ut skjemaet for å registrere deg.</p>';
						$content .= '<form class="register">';
                            $content .= '<div class="form-group has-feedback">';
                                $content .= '<input type="text" class="form-control" name="firstname" placeholder="Fornavn" required>';
                                $content .= '<span class="glyphicon glyphicon-user form-control-feedback"></span>';
                            $content .= '</div>';
                            $content .= '<div class="form-group has-feedback">';
                                $content .= '<input type="text" class="form-control" name="lastname" placeholder="Etternavn" required>';
                                $content .= '<span class="glyphicon glyphicon-user form-control-feedback"></span>';
                            $content .= '</div>';
                            $content .= '<div class="form-group has-feedback">';
                                $content .= '<input type="text" class="form-control" name="username" placeholder="Brukernavn" required>';
                                $content .= '<span class="glyphicon glyphicon-tag form-control-feedback"></span>';
                            $content .= '</div>';
                            $content .= '<div class="form-group has-feedback">';
                                $content .= '<input type="email" class="form-control" name="email" placeholder="E-post" required>';
                                $content .= '<span class="glyphicon glyphicon-envelope form-control-feedback"></span>';
                            $content .= '</div>';
                            $content .= '<div class="form-group has-feedback">';
                                $content .= '<input type="password" class="form-control" name="password" placeholder="Passord" required>';
                                $content .= '<span class="glyphicon glyphicon-lock form-control-feedback"></span>';
                            $content .= '</div>';
                            $content .= '<div class="form-group has-feedback">';
                                $content .= '<input type="password" class="form-control" name="confirm-password" placeholder="Bekreft passord" required>';
                                $content .= '<span class="glyphicon glyphicon-repeat form-control-feedback"></span>';
                            $content .= '</div>';
                            $content .= '<div class="form-group has-feedback">';
                                $content .= '<div class="row">';
                                    $content .= '<div class="col-md-4">';
                                        $content .= '<div class="radio">';
                                            $content .= '<label><input type="radio" name="gender" value="0" checked> Mann</label>';
                                        $content .= '</div>';
                                    $content .= '</div>';
                                    $content .= '<div class="col-md-4">';
                                        $content .= '<div class="radio">';
                                            $content .= '<label><input type="radio" name="gender" value="1"> Kvinne</label>';
                                        $content .= '</div>';
                                    $content .= '</div>';
                                $content .= '</div>';
                            $content .= '</div>';
                            $content .= '<div class="form-group has-feedback">';
                                $content .= '<input type="date" class="form-control" name="birthdate" placeholder="Fødselsdato" required>';
                                $content .= '<span class="glyphicon glyphicon-calendar form-control-feedback"></span>';
                            $content .= '</div>';
                            $content .= '<div class="form-group has-feedback">';
                                $content .= '<input type="tel" class="form-control" data-inputmask="\'mask\': \'99 99 99 99\'" name="phone" placeholder="Telefon" data-mask required>';
                                $content .= '<span class="glyphicon glyphicon-earphone form-control-feedback"></span>';
                            $content .= '</div>';
                            $content .= '<div class="form-group has-feedback">';
                                $content .= '<input type="text" class="form-control" name="address" placeholder="Adresse" required>';
                                $content .= '<span class="glyphicon glyphicon-map-marker form-control-feedback"></span>';
                            $content .= '</div>';
                            $content .= '<div class="row">';
                                $content .= '<div class="col-md-6">';
                                    $content .= '<div class="form-group has-feedback">';
                                        $content .= '<input type="number" class="form-control postalcode" name="postal-code" min="1" max="9999" placeholder="Sted" required>';
                                        $content .= '<span class="glyphicon glyphicon-globe form-control-feedback"></span>';
                                    $content .= '</div>';
                                $content .= '</div>';
                                $content .= '<div class="col-md-6">';
                                    $content .= '<div class="form-group has-feedback">';
                                        $content .= '<span class="form-control city"></span>';
                                    $content .= '</div>';
                                $content .= '</div>';
                            $content .= '</div>';
                            $content .= '<div class="form-group has-feedback">';
                                $content .= '<input type="tel" class="form-control" data-inputmask="\'mask\': \'99 99 99 99\'" name="emergency-contact-phone" placeholder="Foresatte\'s telefon" data-mask>';
                                $content .= '<span class="glyphicon glyphicon-phone-alt form-control-feedback"></span>';
                            $content .= '</div>';
                            $content .= '<div class="row">';
                                $content .= '<div class="col-md-8">';
                                    // TODO: Add legal stuff here that the user have to accept.
                                    /*
                                    $content .= '<div class="checkbox">';
                                        $content .= '<label><input type="checkbox"> Jeg godtar <a href="#">vilkårene</a></label>';
                                    $content .= '</div>';
                                    */
                                $content .= '</div>';
                                $content .= '<div class="col-md-4">';
                                    $content .= '<button type="submit" class="btn btn-primary btn-block btn-flat">Registrer</button>';
                                $content .= '</div>';
                            $content .= '</div>';
                        $content .= '</form>';
                        $content .= '<a href="." class="text-center">Tilbake til innlogging</a>';
                    $content .= '</div>';
                $content .= '</div>';

				// InputMask
				$content .= '<script src="plugins/input-mask/jquery.inputmask.js"></script>';
				$content .= '<script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>';
                $content .= '<script src="pages/scripts/register.js"></script>';
                $content .= '<script src="../api/scripts/lookupCity.js"></script>';
		  	$content .= '</body>';
		}

    	return $content;
	}
}