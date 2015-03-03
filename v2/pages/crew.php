/*
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2015 Infected <http://infected.no/>.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

<?php
require_once 'session.php';
require_once 'handlers/grouphandler.php';
require_once 'handlers/teamhandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if (isset($_GET['id'])) {
		if ($user->isGroupMember()) {	
			if (isset($_GET['teamId'])) {
				$team = TeamHandler::getTeam($_GET['teamId']);

				if ($team != null) {
					$team->displayWithInfo();
				}
			} else {
				$group = GroupHandler::getGroup($_GET['id']);

				if ($group != null) {
					$group->displayWithInfo();
				}
			}
		} else {
			echo 'Du er ikke i crew.';
		}
	} else {
		$groupList = GroupHandler::getGroups();
		
		foreach ($groupList as $group) {	
			$group->displayWithInfo();
		}
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>