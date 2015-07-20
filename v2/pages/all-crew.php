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
require_once 'handlers/teamhandler.php';
require_once 'handlers/grouphandler.php';
require_once 'pages/crew.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if (isset($_GET['id'])) {
		if ($user->isGroupMember()) {	
			if (isset($_GET['teamId'])) {
				$team = TeamHandler::getTeam($_GET['teamId']);

				if ($team != null) {
					displayTeamWithInfo($team);
				}
			} else {
				$group = GroupHandler::getGroup($_GET['id']);

				if ($group != null) {
					displayGroupWithInfo($group);
				}
			}
		} else {
			echo 'Du er ikke i crew.';
		}
	} else {
		$groupList = GroupHandler::getGroups();
		
		foreach ($groupList as $group) {	
			displayGroupWithInfo($group);
		}
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>