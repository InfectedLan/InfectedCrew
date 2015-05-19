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
		$user = Session::getCurrentUser();
		
		if ($user->isGroupMember()) {
			$memberList = $group->getMembers();
			
			if (!empty($memberList)) {
				echo '<div class="row">';

					foreach ($memberList as $member) {
						if ($member->hasValidAvatar()) {
							$avatarFile = $member->getAvatar()->getThumbnail();
						} else {
							$avatarFile = AvatarHandler::getDefaultAvatar($member);
						}

						echo '<div class="col-md-3">';
					    	echo '<div class="thumbnail">';
					      		echo '<a href="?page=my-profile&id=' . $member->getId() . '">';
					      			echo '<img src="../api/' . $avatarFile . '" class="img-circle" alt="' . $member->getDisplayName() . '\'s profile">';
					      		echo '</a>';
					      		echo '<div class="caption">';
					      			echo '<p class="text-center">';
						        		echo '<small>' . $member->getDisplayName() . '</small><br>';
						        		echo '<small>' . $member->getRole() . '</small><br>';
						        		echo '<small>Telefon: ' . $member->getPhoneAsString() . '</small><br>';
										echo '<small>E-post: ' . $member->getEmail() . '</small><br>';
									echo '</p>';
					      		echo '</div>';
					   		echo '</div>';
					  	echo '</div>';
					}

				echo '</div>';
			} else {
				echo '<p>Det er ingen medlemmer i dette crewet.</p>';
			}
		}
	}

	public static function displayTeam(Team $team) {
		$user = Session::getCurrentUser();
		
		if ($user->isGroupMember()) {
			$memberList = $team->getMembers();
			
			if (!empty($memberList)) {
				echo '<div class="row">';
				
					foreach ($memberList as $member) {
						if ($member->hasValidAvatar()) {
							$avatarFile = $member->getAvatar()->getThumbnail();
						} else {
							$avatarFile = AvatarHandler::getDefaultAvatar($member);
						}

						echo '<div class="col-md-3">';
					    	echo '<div class="thumbnail">';
					      		echo '<a href="?page=my-profile&id=' . $member->getId() . '">';
					      			echo '<img src="../api/' . $avatarFile . '" class="img-circle" alt="' . $member->getDisplayName() . '\'s profile">';
					      		echo '</a>';
					      		echo '<div class="caption">';
					      			echo '<p class="text-center">';
						        		echo '<small>' . $member->getDisplayName() . '</small><br>';
						        		echo '<small>' . $member->getRole() . '</small><br>';
						        		echo '<small>Telefon: ' . $member->getPhoneAsString() . '</small><br>';
										echo '<small>E-post: ' . $member->getEmail() . '</small><br>';
									echo '</p>';
					      		echo '</div>';
					   		echo '</div>';
					  	echo '</div>';
					}

				echo '</div>';
			} else {
				echo '<p>Det er ingen medlemmer i dette laget.</p>';
			}
		}
	}
}
?>