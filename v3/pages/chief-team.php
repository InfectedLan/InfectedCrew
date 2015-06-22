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

require_once 'chief.php';
require_once 'session.php';
require_once 'handlers/teamhandler.php';
require_once 'interfaces/page.php';

class ChiefTeamPage extends ChiefPage implements IPage {
	public function getTitle() {
		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if (isset($_GET['teamId'])) {
				$team = TeamHandler::getTeam($_GET['teamId']);

				if ($user->hasPermission('*') ||
					$user->hasPermission('chief.team') ||
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

	public function getContent() {
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

							$teamList = $group->getTeams();
				
							if (!empty($teamList)) {
								$userList = UserHandler::getMemberUsers();

								foreach ($teamList as $team) {
								  	$content .= '<div class="box">';
										$content .= '<div class="box-header">';
									  		$content .= '<h3 class="box-title">' . $group->getTitle() . ':' . $team->getTitle() . '</h3>';
										$content .= '</div><!-- /.box-header -->';
										$content .= '<div class="box-body">';
											$content .= '<form class="chief-team-edit" method="post">';
												$content .= '<input type="hidden" name="teamId" value="' . $team->getId() . '">';
												$content .= '<input type="hidden" name="groupId" value="' . $group->getId() . '">';
												$content .= '<div class="form-group">';
										  			$content .= '<label>Navn</label>';
													$content .= '<input type="text" class="form-control" name="title" value="' . $team->getTitle() . '" required>';
												$content .= '</div>';
												$content .= '<div class="form-group">';
										  			$content .= '<label>Antall medlemmer <span class="badge">' . count($team->getMembers()) . '</span></label>';
												$content .= '</div>';
												$content .= '<div class="form-group">';
												  	$content .= '<label>Beskrivelse</label>';
												  	$content .= '<textarea class="form-control" rows="3" name="content" placeholder="Skriv inn en beskrivese her..." required>';
												  		$content .= $team->getDescription();
												  	$content .='</textarea>';
												$content .= '</div>';
												$content .= '<div class="form-group">';
										  			$content .= '<label>Chief</label>';
										  			$content .= '<select class="form-control" name="leader" required>';
										  				$content .= '<option value="0"></option>';
												
														foreach ($userList as $userValue) {
															if ($userValue->equals($team->getLeader())) {
																$content .= '<option value="' . $userValue->getId() . '" selected>' . $userValue->getDisplayName() . '</option>';
															} else {
																$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
															}
														}
														
													$content .= '</select>';
												$content .= '</div>';

												$content .= '<div class="btn-group" role="group" aria-label="...">';
													$content .= '<button type="button" class="btn btn-primary" onClick="viewTeam(' . $team->getId() . ')">Vis</button>';
													$content .= '<button type="button" class="btn btn-primary" onClick="viewGroup(' . $group->getId() . ')">Vis crew</button>';
										  			$content .= '<button type="submit" class="btn btn-primary">Endre</button>';
													/*
													$content .= '<button type="button" class="btn btn-primary" onClick="removeTeam(' . $team->getId() . ')">Slett</button>';
													*/
												$content .= '</div>';
								  			$content .= '</form>';
										$content .= '</div><!-- /.box-body -->';
									$content .= '</div><!-- /.box -->';
								}
							} else {
								$content .= '<p>Det finnes ikke noen lag i denne gruppen.</p>';
							}

						$content .= '</div><!-- ./col -->';
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
}
?>