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
		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if ($user->hasPermission('*') ||
				$user->hasPermission('developer.switch.user')) {
				echo '<div class="row">';
					echo '<div class="col-md-4">';
						echo '<div class="box">';
							echo '<div class="box-header with-border">';
								echo '<h3 class="box-title">Bytt til en annen bruker</h3>';
							echo '</div>';
							echo '<div class="box-body">';
								echo '<p>Dette er en utvikler-funksjon som lar deg være logget inn som en annen bruker.</p>';
								echo '<p>Dette er en funksjon som <b>ikke</b> skal misbrukes, og må kun brukes i debug eller feilsøkings-sammenheng.</p>';

								echo '<form class="developer-switch-user" method="post">';
									echo '<div class="input-group">';
										echo '<select class="form-control" name="userId" autofocus>';
											$userList = UserHandler::getUsers();
											
											foreach ($userList as $user) {
												echo '<option value="' . $user->getId() . '">' . $user->getDisplayName() . '</option>';
											}
										echo '</select>';
				                    	echo '<span class="input-group-btn">';
				                      		echo '<button class="btn btn-info btn-flat" type="button">Bytt</button>';
				                    	echo '</span>';
				                 	echo '</div><!-- /input-group -->';
				                echo '</form>';
							echo '</div><!-- /.box-body -->';
						echo '</div><!-- /.box -->';
					echo '</div><!--/.col (left) -->';
				echo '</div><!-- /.row -->';

				echo '<script src="scripts/developer-switch-user.js"></script>';
			} else {
				echo '<div class="box">';
					echo '<div class="box-body">';
						echo 'Du har ikke rettigheter til dette!';
					echo '</div><!-- /.box-body -->';
				echo '</div><!-- /.box -->';
			}
		} else {
			echo '<div class="box">';
				echo '<div class="box-body">';
					echo '<p>Du er ikke logget inn!</p>';
				echo '</div><!-- /.box-body -->';
			echo '</div><!-- /.box -->';
		}
	}
}
?>