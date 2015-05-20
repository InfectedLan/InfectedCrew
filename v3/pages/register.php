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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'session.php';
require_once 'settings.php';
require_once 'interfaces/page.php';
require_once 'traits/page.php';
require_once 'utils/dateutils.php';

class RegisterPage implements IPage {
	use Page;

	public function getTitle() {
		return null;
	}

	public function getContent() {
        $content = null;

		if (!Session::isAuthenticated()) {
            $content .= '<body class="register-page">';
    			$content .= '<div class="register-box">';
    				$content .= '<div class="register-logo">';
    					$content .= '<a href="."><b>' . Settings::name . '</b> Crew</a>';
    				$content .= '</div>';	

    				$content .= '<div class="register-box-body">';
    					$content .= '<p class="login-box-msg">Fyll ut skjemaet for å registrere deg.</p>';

    					$content .= '<form class="register" method="post">';
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
    							$content .= '<span class="glyphicon glyphicon-user form-control-feedback"></span>';
    						$content .= '</div>';
    						$content .= '<div class="form-group has-feedback">';
    							$content .= '<input type="email" class="form-control" name="email" placeholder="E-post" required>';
    							$content .= '<span class="glyphicon glyphicon-envelope form-control-feedback"></span>';
    						$content .= '</div>';
    						$content .= '<div class="form-group has-feedback">';
    							$content .= '<input type="email" class="form-control" name="confirmemail" placeholder="Bekreft e-post" required>';
    							$content .= '<span class="glyphicon glyphicon-repeat form-control-feedback"></span>';
    						$content .= '</div>';
    						$content .= '<div class="form-group has-feedback">';
    							$content .= '<input type="password" class="form-control" name="password" placeholder="Passord" required>';
    							$content .= '<span class="glyphicon glyphicon-lock form-control-feedback"></span>';
    						$content .= '</div>';
    						$content .= '<div class="form-group has-feedback">';
    							$content .= '<input type="password" class="form-control" name="confirmpassword" placeholder="Bekreft passord" required>';
    							$content .= '<span class="glyphicon glyphicon-repeat form-control-feedback"></span>';
    						$content .= '</div>';
    						$content .= '<div class="form-group">';
    						   $content .= '<div class="form-inline">';
    								$content .= '<div class="radio">';
    									$content .= '<label><input type="radio" name="gender" value="0" checked> Mann</label>';
    								$content .= '</div>';
    								$content .= '<div class="radio">';
    									$content .= '<label><input type="radio" name="gender" value="1"> Kvinne</label>';
    								$content .= '</div>';
    							$content .= '</div>';
    						$content .= '</div>';
    						$content .= '<div class="form-group">';
    							$content .= '<label>Fødselsdato</label>';
    							$content .= '<div class="form-inline">';
    								$content .= '<select class="form-control" name="birthday">';
    									for ($day = 1; $day <= 31; $day++) {
    										$content .= '<option value="' . $day . '">' . $day . '</option>';
    									}
    								$content .= '</select>';
    								$content .= '<select class="form-control" name="birthmonth">';
    									for ($month = 1; $month <= 12; $month++) {
    										$content .= '<option value="' . $month . '">' . DateUtils::getMonthFromInt($month) . '</option>';
    									}
    								$content .= '</select>';
    								$content .= '<select class="form-control" name="birthyear">';
    									for ($year = date('Y') - 100; $year <= date('Y'); $year++) {
    										if ($year == date('Y') - 18) {
    										  $content .= '<option value="' . $year . '" selected>' . $year . '</option>';
    										} else {
    										  $content .= '<option value="' . $year . '">' . $year . '</option>';
    										}
    									}
    								$content .= '</select>';
    							$content .= '</div>';
    						$content .= '</div>';
    						$content .= '<div class="form-group has-feedback">';
    							$content .= '<input type="tel" class="form-control" data-inputmask="\'mask\': \'99 99 99 99\'" name="phone" placeholder="Telefon" data-mask required>';
    							$content .= '<span class="glyphicon glyphicon-earphone form-control-feedback"></span>';
    						$content .= '</div>';
    						$content .= '<div class="form-group has-feedback">';
    							$content .= '<input type="text" class="form-control" name="address" placeholder="Adresse" required>';
    							$content .= '<span class="glyphicon glyphicon-globe form-control-feedback"></span>';
    						$content .= '</div>';
    						$content .= '<div class="form-group has-feedback">';
    							$content .= '<label>Postnummer</label>';
    							$content .= '<div class="form-inline">';
    								$content .= '<input type="number" class="postalcode" name="postalcode" min="1" max="10000" required>';
    								$content .= '<span class="city">Hvalstad</span>';
    							$content .= '</div>';
    						$content .= '</div>';
    						$content .= '<div class="form-group has-feedback">';
    							$content .= '<input type="text" class="form-control" name="nickname" placeholder="Kallenavn (Valgfritt)">';
    							$content .= '<span class="glyphicon glyphicon-user form-control-feedback"></span>';
    						$content .= '</div>';
    						$content .= '<div class="form-group has-feedback">';
    							$content .= '<input type="tel" class="form-control" data-inputmask="\'mask\': \'99 99 99 99\'" name="emergencycontactphone" placeholder="Foresatte\'s telefon" data-mask required>';
    							$content .= '<span class="glyphicon glyphicon-phone-alt form-control-feedback"></span>';
    						$content .= '</div>';
    						$content .= '<div class="row">';
    							$content .= '<div class="col-xs-8">';
    								$content .= '<div class="checkbox icheck">';
    									$content .= '<label><input type="checkbox"> I agree to the <a href="#">terms</a></label>';
    								$content .= '</div>';
    							$content .= '</div><!-- /.col -->';
    							$content .= '<div class="col-xs-4">';
    								$content .= '<button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>';
    							$content .= '</div><!-- /.col -->';
    						$content .= '</div>';
    					$content .= '</form>';
    					$content .= '<a href="login.html" class="text-center">I already have a membership</a>';
    				$content .= '</div><!-- /.form-box -->';
    			$content .= '</div><!-- /.register-box -->';

    			$content .= '<!-- jQuery 2.1.4 -->';
    			$content .= '<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>';
    			$content .= '<!-- Bootstrap 3.3.2 JS -->';
    			$content .= '<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>';
    			$content .= '<!-- iCheck -->';
    			$content .= '<script src="plugins/iCheck/icheck.min.js" type="text/javascript"></script>';
    			$content .= '<!-- InputMask -->';
    			$content .= '<script src="plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>';
    			$content .= '<script src="plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>';
    			$content .= '<script src="plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>';
    			$content .= '<script>';
    				$content .= '$(function () {';
    					$content .= '$(\'input\').iCheck({';
    						$content .= 'checkboxClass: \'icheckbox_square-blue\',';
    						$content .= 'radioClass: \'iradio_square-blue\',';
    						$content .= 'increaseArea: \'20%\''; // optional
    					$content .= '});';
    					$content .= '$(\'[data-mask]\').inputmask();';
    				$content .= '});';
    			$content .= '</script>';

    			$content .= '<script src="../api/scripts/register.js"></script>';
    			$content .= '<script src="../api/scripts/lookupCity.js"></script>';
            $content .= '</body>';
		} else {
            $content .= '<p>Du er allerde registrert.</p>';
		}

        return $content;
	}
}
?>