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
		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if ($user->isGroupMember()) {
				$group = $user->getGroup();
			
				if ($user->hasPermission('*') ||
					$user->hasPermission('chief.teams')) {
					$teamList = $user->getGroup()->getTeams();
					$userList = $group->getMembers();
					echo '<script src="scripts/chief-teams.js"></script>';

					echo '<div class="row">';
						echo '<div class="col-md-12">';
					  		echo '<div class="box box-solid">';
								echo '<div class="box-header with-border">';
						  			echo '<h3 class="box-title">Lag</h3>';
								echo '</div><!-- /.box-header -->';
								echo '<div class="box-body">';
						  
									if (!empty($teamList)) {
										echo '<table class="table table-bordered">';
											echo '<tr>';
												echo '<th>Navn</th>';
												echo '<th>Medlemmer</th>';
												echo '<th>Beskrivelse</th>';
												echo '<th>Shift-leder</th>';
											echo '</tr>';
											
											foreach ($teamList as $team) {
												echo '<tr>';
													echo '<form class="chief-teams-edit" method="post">';
														echo '<input type="hidden" name="teamId" value="' . $team->getId() . '">';
														echo '<input type="hidden" name="groupId" value="' . $group->getId() . '">';
														echo '<td>' . $group->getTitle() . ':<input type="text" name="title" value="' . $team->getTitle() . '"></td>';
														echo '<td>' . count($team->getMembers()) . '</td>';
														echo '<td><input type="text" name="description" value="' . $team->getDescription() . '"></td>';
														echo '<td>';
															echo '<select class="chosen-select" name="leader" data-placeholder="Velg en chief...">';
																echo '<option value="0"></option>';

																foreach ($userList as $userValue) {
																	if ($userValue->equals($team->getLeader())) {
																		echo '<option value="' . $userValue->getId() . '" selected>' . $userValue->getDisplayName() . '</option>';
																	} else {
																		echo '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
																	}
																}
															echo '</select>';
														echo '</td>';
														echo '<td><button class="btn btn-block btn-primary">Endre</button></td>';
													echo '</form>';
													echo '<td><button class="btn btn-block btn-primary" onClick="removeTeam(' . $group->getId() . ', ' . $team->getId() . ')">Slett</button></td>';
												echo '</tr>';
											}

										echo '</table>';
									} else {
										echo '<p>Det finnes ikke noen lag i denne gruppen.</p>';
									}

								echo '</div><!-- /.box-body -->';
					  		echo '</div><!-- /.box -->';
						echo '</div><!-- ./col -->';
				  	echo '</div><!-- /.row -->';

				  	echo '<div class="row">';
						echo '<div class="col-md-6">';
					  		echo '<div class="box box-solid">';
								echo '<div class="box-header with-border">';
							  		echo '<h3 class="box-title">Medlemmer</h3>';
								echo '</div><!-- /.box-header -->';
								echo '<div class="box-body">';
									
									if (!empty($teamList)) {
										$freeUserList = getFreeUsers($group);

										if (!empty($freeUserList)) {
											echo '<table>';
												echo '<tr>';
													echo '<form class="chief-teams-adduser" method="post">';
														echo '<td>';
															echo '<select class="chosen-select" name="userId">';
																foreach ($freeUserList as $userValue) {
																	echo '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
																}
															echo '</select>';
														echo '</td>';
														echo '<td>';
															echo '<select class="chosen-select" name="teamId">';
																foreach ($teamList as $team) {
																	echo '<option value="' . $team->getId() . '">' . $team->getTitle() . '</option>';
																}
															echo '</select>';
														echo '</td>';
														echo '<td><input type="submit" value="Legg til"></td>';
													echo '</form>';
												echo '</tr>';
											echo '</table>';
										} else {
											echo '<p>Alle medlemmer av "' . $group->getTitle() . '" crew er allerede med i et lag.</p>';
										}
										
										foreach ($teamList as $team) {
											$memberList = $team->getMembers();
											
											echo '<h4>' . $group->getTitle() . ':' . $team->getTitle() . '</h4>';
											echo '<table>';
												if (!empty($memberList)) {
													foreach ($memberList as $userValue) {
														echo '<tr>';
															echo '<td>' . $userValue->getDisplayName() . '</td>';
															echo '<td><input type="button" value="Fjern" onClick="removeUserFromTeam(' . $userValue->getId() . ')"></td>';
														echo '</tr>';
													}
													
													if (count($teamList) > 1) {
														echo '<tr>';
															echo '<td><input type="button" value="Fjern alle" onClick="removeUsersFromTeam(' . $team->getId() . ')"></td>';
														echo '</tr>';
													}
												} else {
													echo '<i>Det er ingen medlemmer i ' . $group->getTitle() . ':' . $team->getTitle() . '.</i>';
												}
											echo '</table>';
										}
									} else {
										echo '<p>Det finnes ikke noen lag i denne gruppen.</p>';
									}

								echo '</div><!-- /.box-body -->';
							echo '</div><!-- /.box -->';
						echo '</div><!-- ./col -->';
						echo '<div class="col-md-4">';
						  	echo '<div class="box box-solid">';
								echo '<div class="box-header with-border">';
							  		echo '<h3 class="box-title">Legg til et nytt lag i "' . $group->getTitle() . '"</h3>';
								echo '</div><!-- /.box-header -->';
								echo '<div class="box-body">';
									echo '<form class="chief-teams-add" method="post">';
										echo '<input type="hidden" name="groupId" value="' . $group->getId() . '">';
										echo '<table>';
											echo '<tr>';
												echo '<td>';
													echo '<div class="form-group has-feedback">';
														echo '<input type="text" class="form-control" name="title" placeholder="Navn" required>';
													echo '</div>';
												echo '</td>';
											echo '<tr>';
											echo '</tr>';
											echo '<tr>';
												echo '<td>';
													echo '<div class="form-group has-feedback">';
														echo '<input type="text" class="form-control" name="description" placeholder="Beskrivelse" required>';
													echo '</div>';
												echo '</td>';
											echo '</tr>';
											echo '<tr>';
												echo '<td>';
													echo '<div class="form-group">';
													  	echo '<select class="form-control chosen-select" name="leader" data-placeholder="Velg en chief...">';
														 	
														 	echo '<option value="0"></option>';
														
															foreach ($userList as $userValue) {
																echo '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
															}

													  	echo '</select>';
													echo '</div>';

													/*
													echo '<select class="chosen-select" name="leader" data-placeholder="Velg en chief...">';
														echo '<option value="0"></option>';
														
														foreach ($userList as $userValue) {
															echo '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
														}
													echo '</select>';
													*/
												echo '</td>';
											echo '</tr>';
											echo '<tr>';
												echo '<td><button class="btn btn-block btn-primary">Legg til</button></td>';
											echo '</tr>';
										echo '</table>';
									echo '</form>';
								echo '</div><!-- /.box-body -->';
							echo '</div><!-- /.box -->';
						echo '</div><!-- ./col -->';
				  	echo '</div><!-- /.row -->';
				} else {
					echo '<p>Du har ikke rettigheter til dette!</p>';
				}
			} else {
				echo 'Du er ikke i noen gruppe!';
			}
		} else {
			echo '<p>Du er ikke logget inn!</p>';
		}
	}

	public function getFreeUsers($group) {
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