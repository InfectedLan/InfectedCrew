<?php
/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2017 Infected <http://infected.no/>.
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
require_once 'chief.php';

class ChiefTeamPage extends ChiefPage {
	public function hasParent(): bool {
		return true;
	}

	public function getTitle(): string {
		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if (isset($_GET['teamId'])) {
				$team = TeamHandler::getTeam($_GET['teamId']);

				if ($user->hasPermission('chief.team') ||
					$user->equals($team->getLeader())) {

					return $team->getGroup()->getTitle() . ':' . $team->getTitle();
				}
			} else if (isset($_GET['groupId']) ||
				$user->isGroupMember()) {
				$group = isset($_GET['groupId']) ? GroupHandler::getGroup($_GET['groupId']) : $user->getGroup();

				if ($user->hasPermission('*') ||
					$user->hasPermission('chief.team') ||
					$user->equals($group->getLeader()) ||
					$user->equals($group->getCoLeader())) {

					return 'Lagene i ' . $group->getTitle();
				}
			}
		}

		return 'Lag';
	}

	public function getContent(): string {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if (isset($_GET['teamId'])) {
				$team = TeamHandler::getTeam($_GET['teamId']);

				if ($user->hasPermission('*') ||
					$user->hasPermission('chief.team') ||
					$user->equals($team->getLeader())) {
					$group = $team->getGroup();

					$content .= '<div class="row">';
						$content .= '<div class="col-md-6">';
							$content .= '<div class="box">';
								$content .= '<div class="box-header">';
							  		$content .= '<h3 class="box-title">Medlemmer i ' . $group->getTitle() . ':' . $team->getTitle() . '</h3>';
								$content .= '</div><!-- /.box-header -->';
								$content .= '<div class="box-body">';
						  		$memberList = $team->getMembers();

									if (!empty($memberList)) {
										$content .= '<ul class="list-group">';

											foreach ($memberList as $userValue) {
												$content .= '<li class="list-group-item">';
													$content .= $userValue->getDisplayName();
													$content .= '<button type="button" class="btn btn-xs btn-primary pull-right" onClick="removeUserFromTeam(' . $userValue->getId() . ')">Fjern</button>';
												$content .= '</li>';
											}

										$content .= '</ul>';
										$content .= '<button type="button" class="btn btn-primary" onClick="removeUsersFromTeam(' . $team->getId() . ')">Fjern alle</button>';
									} else {
										$content .= '<p>Det er ingen medlemmer i ' . $group->getTitle() . ':' . $team->getTitle() . '.</p>';
									}

								$content .= '</div><!-- /.box-body -->';
							$content .= '</div><!-- /.box -->';
						$content .= '</div><!--/.col (left) -->';
						$content .= '<div class="col-md-6">';
							$content .= '<div class="box">';
								$content .= '<div class="box-header">';
							  	$content .= '<h3 class="box-title">Legg til medlemmer</h3>';
								$content .= '</div><!-- /.box-header -->';
								$content .= '<div class="box-body">';

									$freeUserList = $this->getFreeUsers($group);

									if (!empty($freeUserList)) {
										$content .= '<form class="chief-team-adduser" method="post">';
											$content .= '<input type="hidden" name="teamId" value="' . $team->getId() . '">';
											$content .= '<div class="form-group">';
								  			$content .= '<label>Velg bruker</label>';
								  			$content .= '<div class="input-group">';
												$content .= '<select class="form-control" name="userId" required>';

									  			foreach ($freeUserList as $userValue) {
														$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
													}

												$content .= '</select>';
												$content .= '<span class="input-group-btn">';
											  	$content .= '<button type="submit" class="btn btn-primary btn-flat">Legg til</button>';
												$content .= '</span>';
										  	$content .= '</div>';
											$content .= '</div>';
										$content .= '</form>';
									} else {
										$content .= '<p>Alle medlemmer av ' . $group->getTitle() . ' er allerede med i et lag.</p>';
									}

								$content .= '</div><!-- /.box-body -->';
							$content .= '</div><!-- /.box -->';
						$content .= '</div><!--/.col (right) -->';
				  	$content .= '</div><!-- /.row -->';
				} else {
					$content .= '<div class="box">';
						$content .= '<div class="box-body">';
							$content .= '<p>Du har ikke rettigheter til dette!</p>';
						$content .= '</div><!-- /.box-body -->';
					$content .= '</div><!-- /.box -->';
				}
			} else if (isset($_GET['groupId']) ||
				$user->isGroupMember()) {
				$group = isset($_GET['groupId']) ? GroupHandler::getGroup($_GET['groupId']) : $user->getGroup();

				if ($user->hasPermission('*') ||
					$user->hasPermission('chief.team') ||
					$user->equals($group->getLeader()) ||
					$user->equals($group->getCoLeader())) {

					$content .= '<div class="row">';
						$content .= '<div class="col-md-6">';
							$content .= '<div class="box">';
								$content .= '<div class="box-header">';
									$content .= '<h3 class="box-title">Putt et crewmedlem i et lag</h3>';
								$content .= '</div><!-- /.box-header -->';
								$content .= '<div class="box-body">';
									$content .= self::getAddUserToTeamForm($group);
								$content .= '</div><!-- /.box-body -->';
							$content .= '</div><!-- /.box -->';

							foreach ($group->getTeams() as $team) {
								$content .= '<div class="box">';
									$content .= '<div class="box-header">';
										$content .= '<h3 class="box-title">' . $team->getTitle() . '</h3>';
									$content .= '</div><!-- /.box-header -->';
									$content .= '<div class="box-body">';
										$memberList = $team->getMembers();

										if (!empty($memberList)) {
											$content .= '<ul>';

												foreach ($memberList as $member) {
													$content .= '<li></li>';
												}

											$content .= '<ul>';
										} else {
											$content .= '<p>Det er ingen medlemmer i dette laget.</p>';
										}

									$content .= '</div><!-- /.box-body -->';
								$content .= '</div><!-- /.box -->';
							}

						$content .= '</div><!-- ./col (right) -->';
					$content .= '</div><!-- /.row -->';
				} else {
					$content .= '<div class="box">';
						$content .= '<div class="box-body">';
							$content .= '<p>Du har ikke rettigheter til dette!</p>';
						$content .= '</div><!-- /.box-body -->';
					$content .= '</div><!-- /.box -->';
				}
			} else {
				$content .= '<div class="box">';
					$content .= '<div class="box-body">';
						$content .= '<p>Det er ikke spesifisert noe lag eller gruppe.</p>';
					$content .= '</div><!-- /.box-body -->';
				$content .= '</div><!-- /.box -->';
			}

			$content .= '<script src="scripts/chief-team.js"></script>';
		} else {
			$content .= '<div class="box">';
				$content .= '<div class="box-body">';
					$content .= '<p>Du er ikke logget inn!</p>';
				$content .= '</div><!-- /.box-body -->';
			$content .= '</div><!-- /.box -->';
		}

		return $content;
	}

	private function getFreeUsers($group) {
		$freeUserList = $group->getMembers();

		foreach ($freeUserList as $key => $freeUser) {
			if ($freeUser->isTeamMember()) {
				unset($freeUserList[$key]);
			}
		}

		return $freeUserList;
	}

	private function getAddUserToTeamForm(Group $group) {
		$content = null;

		$content .= '<form class="chief-team-adduser" method="post">';
			$content .= '<div class="form-group">';
				$content .= '<label>Bruker</label>';
				$content .= '<select class="form-control" name="userId">';
					$content .= '<option value="0">Ingen</option>';

					foreach (self::getFreeUsers($group) as $userValue) {
						$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
					}

				$content .= '</select>';
			$content .= '</div><!-- /.form group -->';
			$content .= '<div class="form-group">';
				$content .= '<label>Lag</label>';
				$content .= '<select class="form-control" name="teamId">';
					$content .= '<option value="0">Ingen</option>';

					foreach ($group->getTeams() as $team) {
						$content .= '<option value="' . $team->getId() . '">' . $team->getTitle() . '</option>';
					}

				$content .= '</select>';
			$content .= '</div><!-- /.form group -->';
			$content .= '<button type="submit" class="btn btn-primary">Putt i lag</button>';
		$content .= '</form>';

		return $content;
	}
}
?>
