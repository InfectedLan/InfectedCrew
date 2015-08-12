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
require_once 'objects/group.php';
require_once 'objects/team.php';

function displayGroupWithInfo(Group $group) {
	echo '<div class="crewParagraph">';
		echo '<h3>' . $group->getTitle() . '</h3>';
		echo $group->getDescription();
	echo '</div>';

	if (Session::isAuthenticated()) {
		displayGroup($group);
	}
}

function displayGroup(Group $group) {
	$user = Session::getCurrentUser();

	if ($user->isGroupMember()) {
		$memberList = $group->getMembers();

		if (!empty($memberList)) {
			$index = 0;

			foreach ($memberList as $member) {
				echo '<div class="';

					if ($index % 2 == 0) {
						echo 'crewEntryLeft';
					} else {
						echo 'crewEntryRight';
					}
				echo '">';
					$avatarFile = null;

					if ($member->hasValidAvatar()) {
						$avatarFile = $member->getAvatar()->getThumbnail();
					} else {
						$avatarFile = AvatarHandler::getDefaultAvatar($member);
					}

					echo '<a href="index.php?page=my-profile&id=' . $member->getId() . '"><img src="../api/' . $avatarFile . '" width="146" height="110" style="float: right;"></a>';
					echo '<p>Navn: ' . $member->getDisplayName() . '<br>';
					echo 'Stilling: ' . $member->getRole() . '<br>';
					echo 'Telefon: ' . $member->getPhoneAsString() . '<br>';
					echo 'E-post: ' . $member->getEmail() . '</p>';
				echo '</div>';

				$index++;
			}
		} else {
			echo '<p>Det er ingen medlemmer av dette laget.</p>';
		}
	}
}

function displayTeamWithInfo(Team $team) {
	echo '<div class="crewParagraph">';
		echo '<h3>' . $team->getTitle() . '</h3>';
		echo $team->getDescription();
	echo '</div>';

	if (Session::isAuthenticated()) {
		displayTeam($team);
	}
}

function displayTeam(Team $team) {
	$user = Session::getCurrentUser();

	if ($user->isGroupMember()) {
		$memberList = $team->getMembers();

		if (!empty($memberList)) {
			$index = 0;

			foreach ($memberList as $member) {
				echo '<div class="';

					if ($index % 2 == 0) {
						echo 'crewEntryLeft';
					} else {
						echo 'crewEntryRight';
					}
				echo '">';
					$avatarFile = null;

					if ($member->hasValidAvatar()) {
						$avatarFile = $member->getAvatar()->getThumbnail();
					} else {
						$avatarFile = AvatarHandler::getDefaultAvatar($member);
					}

					echo '<a href="index.php?page=my-profile&id=' . $member->getId() . '"><img src="../api/' . $avatarFile . '" width="146" height="110" style="float: right;"></a>';
					echo '<p>Navn: ' . $member->getDisplayName() . '<br>';
					echo 'Stilling: ' . $member->getRole() . '<br>';
					echo 'Telefon: ' . $member->getPhoneAsString() . '<br>';
					echo 'E-post: ' . $member->getEmail() . '</p>';
				echo '</div>';

				$index++;
			}
		} else {
			echo '<p>Det er ingen medlemmer av dette crewet.</p>';
		}
	}
}
?>
