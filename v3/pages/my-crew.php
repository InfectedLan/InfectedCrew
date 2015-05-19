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
require_once 'handlers/restrictedpagehandler.php'; 
require_once 'utils/crewutils.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->isGroupMember()) {
		if (isset($_GET['teamId'])) {
			$team = TeamHandler::getTeam($_GET['teamId']);

			if ($team != null) {
				echo '<div class="box">';
					echo '<div class="box-header with-border">';
						echo '<h3 class="box-title">' . $team->getTitle() . '</h3>';
					echo '</div>';
					echo '<div class="box-body">';

						echo $team->getDescription();

					echo '</div><!-- /.box-body -->';	
					echo '<div class="box-footer">';
		            	CrewUtils::displayTeam($team);
		            echo '</div><!-- /.box-footer-->';
				echo '</div><!-- /.box -->';
			}
		} else {
			$group = $user->getGroup();
			
			if ($group != null) {
				echo '<div class="box">';
					echo '<div class="box-header with-border">';
						echo '<h3 class="box-title">' . $group->getTitle() . '</h3>';
					echo '</div>';
					echo '<div class="box-body">';

						$page = RestrictedPageHandler::getPageByName($group->getName());
				
						if ($page != null) {
							echo $page->getContent();
						}
						
						echo  $group->getDescription();

					echo '</div><!-- /.box-body -->';
				echo '</div><!-- /.box -->';

				CrewUtils::displayGroup($group);
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