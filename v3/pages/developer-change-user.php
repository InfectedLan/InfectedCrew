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

require_once 'developer.php';
require_once 'session.php';
require_once 'handlers/userhandler.php';
require_once 'interfaces/page.php';

class DeveloperSwitchUserPage extends DeveloperPage implements IPage {
	public function getTitle() {
		return 'Bytt bruker';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if ($user->hasPermission('developer.change-user')) {
				$content .= '<script src="scripts/developer-change-user.js"></script>';

				$content .= '<div class="row">';
					$content .= '<div class="col-md-4">';
						$content .= '<div class="box">';
							$content .= '<div class="box-body">';
								$content .= '<p>Dette er en utvikler-funksjon som lar deg være logget inn som en annen bruker.</p>';
								$content .= '<p>Dette er en funksjon som <b>ikke</b> skal misbrukes, og må kun brukes i debug eller feilsøkings-sammenheng.</p>';

								$content .= '<form class="developer-switch-user" method="post">';
									$content .= '<div class="input-group">';
										$content .= '<select class="form-control" name="userId" autofocus>';
											$userList = UserHandler::getUsers();

											foreach ($userList as $user) {
												$content .= '<option value="' . $user->getId() . '">' . $user->getDisplayName() . '</option>';
											}

										$content .= '</select>';
                  	$content .= '<span class="input-group-btn">';
                    	$content .= '<button class="btn btn-info btn-flat" type="button">Bytt</button>';
                  	$content .= '</span>';
                 	$content .= '</div><!-- /input-group -->';
                $content .= '</form>';
							$content .= '</div><!-- /.box-body -->';
						$content .= '</div><!-- /.box -->';
					$content .= '</div><!--/.col (left) -->';
				$content .= '</div><!-- /.row -->';
			} else {
				$content .= '<div class="box">';
					$content .= '<div class="box-body">';
						$content .= '<p>Du har ikke rettigheter til dette.</p>';
					$content .= '</div><!-- /.box-body -->';
				$content .= '</div><!-- /.box -->';
			}
		} else {
			$content .= '<div class="box">';
				$content .= '<div class="box-body">';
					$content .= '<p>Du er ikke logget inn.</p>';
				$content .= '</div><!-- /.box-body -->';
			$content .= '</div><!-- /.box -->';
		}

		return $content;
	}
}
?>
