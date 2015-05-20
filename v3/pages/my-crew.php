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
require_once 'interfaces/page.php';
require_once 'traits/page.php';
require_once 'utils/crewutils.php';

class MyCrewPage implements IPage {
	use Page;

	public function getTitle() {
		return 'Mitt crew';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
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
					$group = $user->getGroup();
					
					if ($group != null) {
						$content .= '<div class="box">';
							$content .= '<div class="box-header with-border">';
								$content .= '<h3 class="box-title">' . $group->getTitle() . '</h3>';
							$content .= '</div>';
							$content .= '<div class="box-body">';

								$page = RestrictedPageHandler::getPageByName($group->getName());
						
								if ($page != null) {
									$content .= $page->getContent();
								}
								
								$content .=  $group->getDescription();

							$content .= '</div><!-- /.box-body -->';
						$content .= '</div><!-- /.box -->';

						$content .= CrewUtils::displayGroup($group);
					}
				}
			} else {
				$content .= '<div class="box">';
					$content .= '<div class="box-body">';
						$content .= '<p>Du er ikke i noe crew!</p>';
					$content .= '</div><!-- /.box-body -->';
				$content .= '</div><!-- /.box -->';
			}
		} else {
			$content .= '<div class="box">';
				$content .= '<div class="box-body">';
					$content .= '<p>Du er ikke logget inn!</p>';
				$content .= '</div><!-- /.box-body -->';
			$content .= '</div><!-- /.box -->';
		}

		return $content;
	}
}
?>