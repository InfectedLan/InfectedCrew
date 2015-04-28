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
require_once 'handlers/userhandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('developer.change-user')) {
		echo '<script src="scripts/developer-change-user.js"></script>';
		echo '<h1>Bytt bruker</h1>';
		echo '<p>Dette er en utvikler-funksjon som lar deg være logget inn som en annen bruker. <br>';
		echo 'Dette er en funksjon som ikke skal misbrukes, og må kun brukes i debug eller feilsøkings-sammenheng.</p>';
		
		echo '<form class="developer-changeuser" name="input" method="post">';
			echo '<table>';
				echo '<tr>';
					echo '<td>Bruker:</td>';
					echo '<td>';
						echo '<select class="chosen-select" name="userId" autofocus>';
							$userList = UserHandler::getUsers();
							
							foreach ($userList as $user) {
								echo '<option value="' . $user->getId() . '">' . $user->getDisplayName() . '</option>';
							}
						echo '</select>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><input type="submit" value="Bytt bruker"></td>';
				echo '</tr>';
			echo '</table>';
		echo '</form>';
	} else {
		echo 'Du har ikke rettigheter til dette.';
	}
} else {
	echo 'Du er ikke logget inn.';
}
?>