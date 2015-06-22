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

require_once 'chief.php';
require_once 'session.php';
require_once 'handlers/avatarhandler.php';
require_once 'interfaces/page.php';

class ChiefAvatarPage extends ChiefPage implements IPage {
	public function getTitle() {
		return 'Profilbilder';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if ($user->hasPermission('*') ||
				$user->hasPermission('chief.avatar')) {
				$content .= '<h3>Godkjenn profilbilder</h3>';
				
				$pendingAvatarList = AvatarHandler::getPendingAvatars();
				
				if (!empty($pendingAvatarList)) {
					$index = 0;
				
					foreach ($pendingAvatarList as $avatar) {
						$avatarUser = $avatar->getUser();
					
						$content .= '<div class="';
							if ($index % 2 == 0) {
								$content .= 'avatarLeft';
							} else {
								$content .= 'avatarRight';
							}
						$content .= '">';
							$content .= '<p>' . $avatarUser->getDisplayName() . '</p>';
							$content .= '<img src="../api/' . $avatarUser->getAvatar()->getSd() . '" width="300" height="200">';
							$content .= '<table>';
								$content .= '<tr>';
									$content .= '<td><input type="button" value="Godta" onClick="acceptAvatar(' . $avatar->getId() . ')"></td>';
									$content .= '<td><input type="button" value="Avslå" onClick="rejectAvatar(' . $avatar->getId() . ')"></td>';
								$content .= '</tr>';
							$content .= '</table>';
						$content .= '</div>';
							
						$index++;
					}

					$content .= '<script src="scripts/chief-avatar.js"></script>';
				} else {
					$content .= '<p>Det er ingen profilbilder som trenger godkjenning akkurat nå.</p>';
				}
			} else {
				$content .= '<p>Du har ikke rettigheter til dette!</p>';
			}
		} else {
			$content .= '<p>Du er ikke logget inn!</p>';
		}

		return $content;
	}
}
?>