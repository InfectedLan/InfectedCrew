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
require_once 'interfaces/page.php';
require_once 'handlers/teamhandler.php';
require_once 'handlers/grouphandler.php';
require_once 'pages/page.php';
require_once 'utils/crewutils.php';

class AllCrewPage implements IPage {
	use Page;

	public function getTitle() {
		return 'Crew';
	}

	public function getContent() {
		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if (isset($_GET['id'])) {
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
						} else {

						}
					} else {
						$group = GroupHandler::getGroup($_GET['id']);

						if ($group != null) {
							echo '<div class="box">';
								echo '<div class="box-header with-border">';
									echo '<h3 class="box-title">' . $group->getTitle() . '</h3>';
								echo '</div>';
								echo '<div class="box-body">';

									echo $group->getDescription();

								echo '</div><!-- /.box-body -->';
							echo '</div><!-- /.box -->';

							CrewUtils::displayGroup($group);
						}
					}
				} else {
					echo '<p>Du er ikke i crew.</p>';
				}
			} else {
				$groupList = GroupHandler::getGroups();
				
				foreach ($groupList as $group) {
					echo '<div class="box">';
						echo '<div class="box-header with-border">';
							echo '<h3 class="box-title">' . $group->getTitle() . '</h3>';
						echo '</div>';
						echo '<div class="box-body">';

							echo $group->getDescription();

						echo '</div><!-- /.box-body -->';
						echo '<div class="box-footer">';
			            	CrewUtils::displayGroup($group);
			            echo '</div><!-- /.box-footer-->';
					echo '</div><!-- /.box -->';
				}
			}
		} else {
			echo '<p>Du er ikke logget inn!</p>';
		}
	}
}
?>