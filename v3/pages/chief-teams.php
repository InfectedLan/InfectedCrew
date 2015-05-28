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
require_once 'interfaces/page.php';

class ChiefTeamsPage extends ChiefPage implements IPage {
	public function getTitle() {
		return 'Lag';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if ($user->isGroupMember()) {
				$group = $user->getGroup();
			
				if ($user->hasPermission('*') ||
					$user->hasPermission('chief.teams')) {
					$teamList = $user->getGroup()->getTeams();
					$userList = $group->getMembers();
					$content .= '<script src="scripts/chief-teams.js"></script>';

					$content .= '<div class="row">';
						$content .= '<div class="col-md-12">';
					  		$content .= '<div class="box box-solid">';
								$content .= '<div class="box-header with-border">';
						  			$content .= '<h3 class="box-title">Lag</h3>';
								$content .= '</div><!-- /.box-header -->';
								$content .= '<div class="box-body">';
						  
									if (!empty($teamList)) {
										$content .= '<table class="table table-bordered">';
											$content .= '<tr>';
												$content .= '<th>Navn</th>';
												$content .= '<th>Medlemmer</th>';
												$content .= '<th>Beskrivelse</th>';
												$content .= '<th>Shift-leder</th>';
											$content .= '</tr>';
											
											foreach ($teamList as $team) {
												$content .= '<tr>';
													$content .= '<form class="chief-teams-edit" method="post">';
														$content .= '<input type="hidden" name="teamId" value="' . $team->getId() . '">';
														$content .= '<input type="hidden" name="groupId" value="' . $group->getId() . '">';
														$content .= '<td>' . $group->getTitle() . ':<input type="text" name="title" value="' . $team->getTitle() . '"></td>';
														$content .= '<td>' . count($team->getMembers()) . '</td>';
														$content .= '<td><input type="text" name="description" value="' . $team->getDescription() . '"></td>';
														$content .= '<td>';
															$content .= '<select class="chosen-select" name="leader" data-placeholder="Velg en chief...">';
																$content .= '<option value="0"></option>';

																foreach ($userList as $userValue) {
																	if ($userValue->equals($team->getLeader())) {
																		$content .= '<option value="' . $userValue->getId() . '" selected>' . $userValue->getDisplayName() . '</option>';
																	} else {
																		$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
																	}
																}
															$content .= '</select>';
														$content .= '</td>';
														$content .= '<td><button type="submit" class="btn btn-primary">Endre</button></td>';
													$content .= '</form>';
													$content .= '<td><button class="btn btn-block btn-primary" onClick="removeTeam(' . $group->getId() . ', ' . $team->getId() . ')">Slett</button></td>';
												$content .= '</tr>';
											}

										$content .= '</table>';
									} else {
										$content .= '<p>Det finnes ikke noen lag i denne gruppen.</p>';
									}

								$content .= '</div><!-- /.box-body -->';
					  		$content .= '</div><!-- /.box -->';
						$content .= '</div><!-- ./col -->';
				  	$content .= '</div><!-- /.row -->';

				  	$content .= '<div class="row">';
						$content .= '<div class="col-md-6">';
					  		$content .= '<div class="box box-solid">';
								$content .= '<div class="box-header with-border">';
							  		$content .= '<h3 class="box-title">Medlemmer</h3>';
								$content .= '</div><!-- /.box-header -->';
								$content .= '<div class="box-body">';
									
									if (!empty($teamList)) {
										$freeUserList = getFreeUsers($group);

										if (!empty($freeUserList)) {
											$content .= '<table>';
												$content .= '<tr>';
													$content .= '<form class="chief-teams-adduser" method="post">';
														$content .= '<td>';
															$content .= '<select class="chosen-select" name="userId">';
																foreach ($freeUserList as $userValue) {
																	$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
																}
															$content .= '</select>';
														$content .= '</td>';
														$content .= '<td>';
															$content .= '<select class="chosen-select" name="teamId">';
																foreach ($teamList as $team) {
																	$content .= '<option value="' . $team->getId() . '">' . $team->getTitle() . '</option>';
																}
															$content .= '</select>';
														$content .= '</td>';
														$content .= '<td><input type="submit" value="Legg til"></td>';
													$content .= '</form>';
												$content .= '</tr>';
											$content .= '</table>';
										} else {
											$content .= '<p>Alle medlemmer av "' . $group->getTitle() . '" crew er allerede med i et lag.</p>';
										}
										
										foreach ($teamList as $team) {
											$memberList = $team->getMembers();
											
											$content .= '<h4>' . $group->getTitle() . ':' . $team->getTitle() . '</h4>';
											$content .= '<table>';
												if (!empty($memberList)) {
													foreach ($memberList as $userValue) {
														$content .= '<tr>';
															$content .= '<td>' . $userValue->getDisplayName() . '</td>';
															$content .= '<td><input type="button" value="Fjern" onClick="removeUserFromTeam(' . $userValue->getId() . ')"></td>';
														$content .= '</tr>';
													}
													
													if (count($teamList) > 1) {
														$content .= '<tr>';
															$content .= '<td><input type="button" value="Fjern alle" onClick="removeUsersFromTeam(' . $team->getId() . ')"></td>';
														$content .= '</tr>';
													}
												} else {
													$content .= '<i>Det er ingen medlemmer i ' . $group->getTitle() . ':' . $team->getTitle() . '.</i>';
												}
											$content .= '</table>';
										}
									} else {
										$content .= '<p>Det finnes ikke noen lag i denne gruppen.</p>';
									}

								$content .= '</div><!-- /.box-body -->';
							$content .= '</div><!-- /.box -->';
						$content .= '</div><!-- ./col -->';
						$content .= '<div class="col-md-4">';
						  	$content .= '<div class="box box-solid">';
								$content .= '<div class="box-header with-border">';
							  		$content .= '<h3 class="box-title">Legg til et nytt lag i "' . $group->getTitle() . '"</h3>';
								$content .= '</div><!-- /.box-header -->';
								$content .= '<div class="box-body">';
									$content .= '<form class="chief-teams-add" method="post">';
										$content .= '<input type="hidden" name="groupId" value="' . $group->getId() . '">';
										$content .= '<table>';
											$content .= '<tr>';
												$content .= '<td>';
													$content .= '<div class="form-group has-feedback">';
														$content .= '<input type="text" class="form-control" name="title" placeholder="Navn" required>';
													$content .= '</div>';
												$content .= '</td>';
											$content .= '<tr>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td>';
													$content .= '<div class="form-group has-feedback">';
														$content .= '<input type="text" class="form-control" name="description" placeholder="Beskrivelse" required>';
													$content .= '</div>';
												$content .= '</td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td>';
													$content .= '<div class="form-group">';
													  	$content .= '<select class="form-control chosen-select" name="leader" data-placeholder="Velg en chief...">';
														 	
														 	$content .= '<option value="0"></option>';
														
															foreach ($userList as $userValue) {
																$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
															}

													  	$content .= '</select>';
													$content .= '</div>';

													/*
													$content .= '<select class="chosen-select" name="leader" data-placeholder="Velg en chief...">';
														$content .= '<option value="0"></option>';
														
														foreach ($userList as $userValue) {
															$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
														}
													$content .= '</select>';
													*/
												$content .= '</td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td><button class="btn btn-block btn-primary">Legg til</button></td>';
											$content .= '</tr>';
										$content .= '</table>';
									$content .= '</form>';
								$content .= '</div><!-- /.box-body -->';
							$content .= '</div><!-- /.box -->';
						$content .= '</div><!-- ./col -->';
				  	$content .= '</div><!-- /.row -->';
				} else {
					$content .= '<p>Du har ikke rettigheter til dette!</p>';
				}
			} else {
				$content .= 'Du er ikke i noen gruppe!';
			}
		} else {
			$content .= '<p>Du er ikke logget inn!</p>';
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