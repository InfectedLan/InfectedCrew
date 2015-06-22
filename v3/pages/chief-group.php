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
require_once 'handlers/userhandler.php';
require_once 'handlers/grouphandler.php';
require_once 'interfaces/page.php';

class ChiefGroupPage extends ChiefPage implements IPage {
	public function getTitle() {
		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if (isset($_GET['groupId'])) {
				$group = GroupHandler::getGroup($_GET['groupId']);

				if ($user->hasPermission('*') ||
					$user->hasPermission('chief.group') ||
					$user->equals($group->getLeader()) ||
					$user->equals($group->getCoLeader())) {

					return $group->getTitle();
				}
			}
		}

		return 'Crewene';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
				
			if (isset($_GET['groupId'])) {
				$group = GroupHandler::getGroup($_GET['groupId']);

				if ($user->hasPermission('*') ||
					$user->hasPermission('chief.group') ||
					$user->equals($group->getLeader()) ||
					$user->equals($group->getCoLeader())) {

					$content .= '<div class="row">';
						$content .= '<div class="col-md-6">';
							$content .= '<div class="box">';
								$content .= '<div class="box-header">';
							  		$content .= '<h3 class="box-title">Medlemmer i ' . $group->getTitle() . '</h3>';
								$content .= '</div><!-- /.box-header -->';
								$content .= '<div class="box-body">';
						  		
									if (!empty($memberList)) {
										foreach ($memberList as $userValue) {
											$content .= $userValue->getDisplayName();
											$content .= '<button type="button" class="btn btn-primary" onClick="removeUserFromGroup(' . $userValue->getId() . ')">Fjern</button>';
										}
										
										if (count($groupList) > 1) {
											$content .= '<button type="button" class="btn btn-primary" onClick="removeUsersFromGroup(' . $group->getId() . ')">Fjern alle</button>';
										}
									} else {
										$content .= '<p>Det er ingen medlemmer i ' . $group->getTitle() . '.</p>';
									}

								$content .= '</div><!-- /.box-body -->';
							$content .= '</div><!-- /.box -->';
						$content .= '</div><!--/.col (left) -->';
						$content .= '<div class="col-md-6">';
							$content .= '<div class="box">';
								$content .= '<div class="box-header">';
							  		$content .= '<h3 class="box-title">Legg til et nytt lag i ' . $group->getTitle() . '</h3>';
								$content .= '</div><!-- /.box-header -->';
								$content .= '<div class="box-body">';
									$content .= '<form class="chief-teams-add" method="post">';
										$content .= '<input type="hidden" name="groupId" value="' . $group->getId() . '">';
										$content .= '<div class="form-group">';
								  			$content .= '<label>Navn</label>';
											$content .= '<input type="text" class="form-control" name="title" required>';
										$content .= '</div>';
										$content .= '<div class="form-group">';
										  	$content .= '<label>Beskrivelse</label>';
										  	$content .= '<textarea class="form-control" rows="3" name="content" placeholder="Skriv inn en beskrivese her..." required></textarea>';
										$content .= '</div>';
										$content .= '<div class="form-group">';
								  			$content .= '<label>Chief</label>';
								  			$content .= '<select class="form-control" name="leader" required>';
								  				$content .= '<option value="0"></option>';
										
												foreach ($userList as $userValue) {
													$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
												}
												
											$content .= '</select>';
										$content .= '</div>';
										$content .= '<button type="submit" class="btn btn-primary">Endre</button>';
									$content .= '</form>';
								$content .= '</div><!-- /.box-body -->';
							$content .= '</div><!-- /.box -->';
							$content .= '<div class="box">';
								$content .= '<div class="box-header">';
							  		$content .= '<h3 class="box-title">Legg til en bruker i ' . $group->getTitle() . '</h3>';
								$content .= '</div><!-- /.box-header -->';
								$content .= '<div class="box-body">';

									$freeUserList = UserHandler::getNonMemberUsers();
					
									if (!empty($freeUserList)) {
										$content .= '<form class="chief-groups-adduser" method="post">';
											$content .= '<input type="hidden" name="groupId" value="' . $group->getId() . '">';
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
										$content .= '<p>Alle registrerte medlemmer er allerede med i et crew.</p>';
									}

								$content .= '</div><!-- /.box-body -->';
							$content .= '</div><!-- /.box -->';
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
				if ($user->hasPermission('*') ||
					$user->hasPermission('chief.group')) {

					$content .= '<div class="row">';
						$content .= '<div class="col-md-6">';
							
							$content .= '<div class="box">';
				                $content .= '<div class="box-header">';
				                  	$content .= '<h3 class="box-title">Oversikt</h3>';
				                $content .= '</div><!-- /.box-header -->';
				                $content .= '<div class="box-body">';
				                 	$content .= '<table class="table table-bordered">';
					                    $content .= '<tr>';
					                     	$content .= '<th>Navn</th>';
					                     	$content .= '<th>Beskrivelse</th>';
					                     	$content .= '<th>Antall medlemmer</th>';
					                    $content .= '</tr>';

										foreach (GroupHandler::getGroups() as $group) {
											$content .= '<tr>';
						                      	$content .= '<td>' . $group->getTitle() . '</td>';
						                      	$content .= '<td>' . $group->getDescription() . '</td>';
						                      	$content .= '<td><span class="badge">' . count($group->getMembers()) . '</span></td>';
						                    $content .= '</tr>';
										}

					                    /*
					                    <tr>
					                      <td>2.</td>
					                      <td>Clean database</td>
					                      <td>
					                        <div class="progress progress-xs">
					                          <div class="progress-bar progress-bar-yellow" style="width: 70%"></div>
					                        </div>
					                      </td>
					                      <td><span class="badge bg-yellow">70%</span></td>
					                    </tr>
					                    <tr>
					                      <td>3.</td>
					                      <td>Cron job running</td>
					                      <td>
					                        <div class="progress progress-xs progress-striped active">
					                          <div class="progress-bar progress-bar-primary" style="width: 30%"></div>
					                        </div>
					                      </td>
					                      <td><span class="badge bg-light-blue">30%</span></td>
					                    </tr>
					                    <tr>
					                      <td>4.</td>
					                      <td>Fix and squish bugs</td>
					                      <td>
					                        <div class="progress progress-xs progress-striped active">
					                          <div class="progress-bar progress-bar-success" style="width: 90%"></div>
					                        </div>
					                      </td>
					                      <td><span class="badge bg-green">90%</span></td>
					                    </tr>
					                    */
				                  	$content .= '</table>';
				                $content .= '</div><!-- /.box-body -->';
				            $content .= '</div><!-- /.box -->';

							$groupList = GroupHandler::getGroups();
						
							if (!empty($groupList)) {
								$userList = UserHandler::getMemberUsers();

								foreach ($groupList as $group) {
								  	$content .= '<div class="box">';
										$content .= '<div class="box-header">';
									  		$content .= '<h3 class="box-title">' . $group->getTitle() . '</h3>';
										$content .= '</div><!-- /.box-header -->';
										$content .= '<div class="box-body">';
											$content .= '<form class="chief-groups-edit" method="post">';
												$content .= '<input type="hidden" name="id" value="' . $group->getId() . '">';
												$content .= '<div class="form-group">';
										  			$content .= '<label>Navn</label>';
													$content .= '<input type="text" class="form-control" name="title" value="' . $group->getTitle() . '" required>';
												$content .= '</div>';
												$content .= '<div class="form-group">';
										  			$content .= '<label>Antall medlemmer <span class="badge">' . count($group->getMembers()) . '</span></label>';
												$content .= '</div>';
												$content .= '<div class="form-group">';
												  	$content .= '<label>Beskrivelse</label>';
												  	$content .= '<textarea class="form-control" rows="3" name="content" placeholder="Skriv inn en beskrivese her..." required>';
												  		$content .= $group->getDescription();
												  	$content .='</textarea>';
												$content .= '</div>';
												$content .= '<div class="form-group">';
										  			$content .= '<label>Chief</label>';
										  			$content .= '<select class="form-control" name="leader" required>';
										  				$content .= '<option value="0"></option>';
												
														foreach ($userList as $userValue) {
															if ($userValue->equals($group->getLeader())) {
																$content .= '<option value="' . $userValue->getId() . '" selected>' . $userValue->getDisplayName() . '</option>';
															} else {
																$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
															}
														}
														
													$content .= '</select>';
												$content .= '</div>';
												$content .= '<div class="form-group">';
										  			$content .= '<label>Co-chief</label>';
										  			$content .= '<select class="form-control" name="leader" required>';
										  				$content .= '<option value="0"></option>';
												
														foreach ($userList as $userValue) {
															if ($userValue->equals($group->getCoLeader())) {
																$content .= '<option value="' . $userValue->getId() . '" selected>' . $userValue->getDisplayName() . '</option>';
															} else {
																$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
															}
														}
														
													$content .= '</select>';
												$content .= '</div>';
												$content .= '<div class="btn-group" role="group" aria-label="...">';
													$content .= '<button type="button" class="btn btn-primary" onClick="viewGroup(' . $group->getId() . ')">Vis</button>';
													$content .= '<button type="button" class="btn btn-primary" onClick="viewTeam(' . $group->getId() . ')">Vis lag</button>';
										  			$content .= '<button type="submit" class="btn btn-primary">Endre</button>';
													/*
													$content .= '<button type="button" class="btn btn-primary" onClick="removeGroup(' . $group->getId() . ')">Slett</button>';
													*/
												$content .= '</div>';
								  			$content .= '</form>';
										$content .= '</div><!-- /.box-body -->';
									$content .= '</div><!-- /.box -->';
								}
							}

						$content .= '</div><!--/.col (left) -->';
						$content .= '<div class="col-md-6">';
						  	$content .= '<div class="box">';
								$content .= '<div class="box-header">';
							  		$content .= '<h3 class="box-title">Legg til et nytt crew</h3>';
								$content .= '</div><!-- /.box-header -->';
								$content .= '<div class="box-body">';
									$content .= '<p>Fyll ut feltene under for å legge til en ny gruppe.</p>';

									$content .= '<form class="chief-groups-add" method="post">';
										$content .= '<div class="form-group">';
											$content .= '<label>Navn</label>';
											$content .= '<input type="text" class="form-control" name="title" required>';
									  	$content .= '</div><!-- /.form group -->';
									  	$content .= '<div class="form-group">';
										  	$content .= '<label>Beskrivelse</label>';
										  	$content .= '<textarea class="form-control" rows="3" name="content" placeholder="Skriv inn en beskrivese her..." required></textarea>';
										$content .= '</div><!-- /.form group -->';
										$content .= '<div class="form-group">';
								  			$content .= '<label>Chief</label>';
								  			$content .= '<select class="form-control" name="leader" required>';
								  				$content .= '<option value="0"></option>';
										
												foreach ($userList as $userValue) {
													if ($userValue->equals($group->getLeader())) {
														$content .= '<option value="' . $userValue->getId() . '" selected>' . $userValue->getDisplayName() . '</option>';
													} else {
														$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
													}
												}
												
											$content .= '</select>';
										$content .= '</div>';
										$content .= '<div class="form-group">';
								  			$content .= '<label>Co-chief</label>';
								  			$content .= '<select class="form-control" name="leader" required>';
								  				$content .= '<option value="0"></option>';
										
												foreach ($userList as $userValue) {
													if ($userValue->equals($group->getCoLeader())) {
														$content .= '<option value="' . $userValue->getId() . '" selected>' . $userValue->getDisplayName() . '</option>';
													} else {
														$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
													}
												}
												
											$content .= '</select>';
										$content .= '</div>';
									  	$content .= '<button type="submit" class="btn btn-primary">Legg til</button>';
									$content .= '</form>';
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
			}

			$content .= '<script src="scripts/chief-group.js"></script>';
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

/*
$teamList = $group->getTeams();
					$userList = $group->getMembers();

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
*/
?>