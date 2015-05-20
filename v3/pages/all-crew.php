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
require_once 'traits/page.php';
require_once 'utils/crewutils.php';

class AllCrewPage implements IPage {
	use Page;

	public function getTitle() {
		return 'Crew';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if (isset($_GET['id'])) {
				if ($user->isGroupMember()) {
					if (isset($_GET['teamId'])) {
						$team = TeamHandler::getTeam($_GET['teamId']);

						if ($team != null) {
							$content .= '<div class="box">';
								$content .= '<div class="box-header with-border">';
									$content .= '<h3 class="box-title">' . $team->getTitle() . '</h3>';
								$content .= '</div>';
								$content .= '<div class="box-body">';

									$content .= $team->getDescription();

								$content .= '</div><!-- /.box-body -->';	
							$content .= '</div><!-- /.box -->';

							$content .= CrewUtils::displayTeam($team);
						}
					} else {
						$group = GroupHandler::getGroup($_GET['id']);

						if ($group != null) {
							$content .= '<div class="box">';
								$content .= '<div class="box-header with-border">';
									$content .= '<h3 class="box-title">' . $group->getTitle() . '</h3>';
								$content .= '</div>';
								$content .= '<div class="box-body">';

									$content .= $group->getDescription();

								$content .= '</div><!-- /.box-body -->';
							$content .= '</div><!-- /.box -->';

							$content .= CrewUtils::displayGroup($group);
						}
					}
				} else {
					$content .= '<p>Du er ikke i crew.</p>';
				}
			} else {
				$groupList = GroupHandler::getGroups();
				
				foreach ($groupList as $group) {
					$content .= '<div class="box">';
						$content .= '<div class="box-header with-border">';
							$content .= '<h3 class="box-title">' . $group->getTitle() . '</h3>';
						$content .= '</div>';
						$content .= '<div class="box-body">';

							$content .= $group->getDescription();

						$content .= '</div><!-- /.box-body -->';
					$content .= '</div><!-- /.box -->';

					$content .= CrewUtils::displayGroup($group);
				}
			}
		} else {
			$content .= '<p>Du er ikke logget inn!</p>';
		}

		return $content;
	}
}
?>