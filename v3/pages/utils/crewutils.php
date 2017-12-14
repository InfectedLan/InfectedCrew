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

require_once 'handlers/avatarhandler.php';
require_once 'objects/group.php';
require_once 'objects/team.php';

class CrewUtils {
	public static function displayGroup(Group $group) {
		return self::displayUsers($group->getMembers());
	}

	public static function displayTeam(Team $team) {
		return self::displayUsers($team->getMembers());
	}

	public static function displayUsers(array $userList) {
		$content = null;

		if (!empty($userList)) {
			$content = '<div class="row">';

			foreach ($userList as $user) {
				if ($user->hasValidAvatar()) {
					$avatarFile = $user->getAvatar()->getThumbnail();
				} else {
					$avatarFile = $user->getDefaultAvatar();
				}

				$content .= '<div class="col-md-3">';
					$content .= '<div class="thumbnail">';
				  		$content .= '<a href="?page=my-profile&id=' . $user->getId() . '">';
							$content .= '<div class="avatar-circle" style="background-image: url(\'' . $avatarFile . '\'); " alt="' . htmlentities($user->getDisplayName()) . '\'s profilbilde"></div>';
				  		$content .= '</a>';
				  		$content .= '<div class="caption">';
				  			$content .= '<p class="text-center">';
								$content .= '<small>' . $user->getDisplayName() . '</small><br>';
								$content .= '<small>' . $user->getRole() . '</small><br>';
							$content .= '</p>';
				  		$content .= '</div>';
			   		$content .= '</div>';
			  	$content .= '</div>';
			}

			$content .= '</div>';
		}

		return $content;
	}
}
?>
