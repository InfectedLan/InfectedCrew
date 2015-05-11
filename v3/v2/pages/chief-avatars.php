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
require_once 'handlers/avatarhandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('chief.avatars')) {
		echo '<script src="scripts/chief-avatars.js"></script>';
		echo '<h3>Godkjenn profilbilder</h3>';
		
		$pendingAvatarList = AvatarHandler::getPendingAvatars();
		
		if (!empty($pendingAvatarList)) {
			$index = 0;
		
			foreach ($pendingAvatarList as $avatar) {
				$avatarUser = $avatar->getUser();
			
				echo '<div class="';
					if ($index % 2 == 0) {
						echo 'avatarLeft';
					} else {
						echo 'avatarRight';
					}
				echo '">';
					echo '<p>' . $avatarUser->getDisplayName() . '</p>';
					echo '<img src="../api/' . $avatarUser->getAvatar()->getSd() . '" width="300" height="200">';
					echo '<table>';
						echo '<tr>';
							echo '<td><input type="button" value="Godta" onClick="acceptAvatar(' . $avatar->getId() . ')"></td>';
							echo '<td><input type="button" value="Avslå" onClick="rejectAvatar(' . $avatar->getId() . ')"></td>';
						echo '</tr>';
					echo '</table>';
				echo '</div>';
					
				$index++;
			}
		} else {
			echo '<p>Det er ingen profilbilder som trenger godkjenning akkurat nå.</p>';
		}
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>