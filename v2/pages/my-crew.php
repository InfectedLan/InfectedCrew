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
require_once 'handlers/restrictedpagehandler.php'; 

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->isGroupMember()) {
		if (isset($_GET['teamId'])) {
			$team = TeamHandler::getTeam($_GET['teamId']);
			
			if ($team != null) {
				$team->displayWithInfo();
			} else {
				echo '<p>Dette laget finnes ikke!</p>';
			}
		} else {
			$group = $user->getGroup();
			
			if ($group != null) {
				echo '<h3>' . $group->getTitle() . '</h3>';
			
				$page = RestrictedPageHandler::getPageByName($group->getName());
			
				if ($page != null) {
					echo $page->getContent();
				}
				
				echo  $group->getDescription();
				
				$group->display();
			} else {
				echo '<p>Dette crewet finnes ikke!</p>';
			}
		}
	} else {
		echo '<p>Du er ikke i noe crew!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>