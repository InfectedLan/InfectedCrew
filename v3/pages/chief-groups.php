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

class ChiefGroupsPage extends ChiefPage implements IPage {
	public function getTitle() {
		return 'Crew';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if ($user->hasPermission('*') ||
				$user->hasPermission('chief.groups')) {
				
				if (isset($_GET['groupId'])) {
					$group = GroupHandler::getGroup($_GET['groupId']);

					/*
					$content .= '<h3>Medlemmer</h3>';
					
					$freeUserList = UserHandler::getNonMemberUsers();
					
					if (!empty($freeUserList)) {
						$content .= '<table>';
							$content .= '<tr>';
								$content .= '<form class="chief-groups-adduser" method="post">';
									$content .= '<td>';
										$content .= '<select class="chosen-select" name="userId">';
											foreach ($freeUserList as $userValue) {
												$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
											}
										$content .= '</select>';
									$content .= '</td>';
									$content .= '<td>';
										$content .= '<select class="chosen-select" name="groupId">';
											foreach ($groupList as $group) {
												$content .= '<option value="' . $group->getId() . '">' . $group->getTitle() . '</option>';
											}
										$content .= '</select>';
									$content .= '</td>';
									$content .= '<td><input type="submit" value="Legg til"></td>';
								$content .= '</form>';
							$content .= '</tr>';
						$content .= '</table>';
					} else {
						$content .= '<p>Alle registrerte medlemmer er allerede med i et crew.</p>';
					}
					*/
						

					$content .= '<div class="box">';
						$content .= '<div class="box-header">';
					  		$content .= '<h3 class="box-title">' . $group->getTitle() . '</h3>';
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
								$content .= '<i>Det er ingen medlemmer i ' . $group->getTitle() . '.</i>';
							}

						$content .= '</div><!-- /.box-body -->';
					$content .= '</div><!-- /.box -->';
				} else {
					$content .= '<div class="row">';
						$content .= '<div class="col-md-6">';
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
										  			$content .= '<label>Antall medlemmer</label>';
													$content .= '<b>' . count($group->getMembers()) . '</b>';
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
										  			$content .= '<button type="submit" class="btn btn-primary">Endre</button>';
													$content .= '<button type="button" class="btn btn-primary" onClick="viewGroup(' . $group->getId() . ')">Vis</button>';
												$content .= '</div>';
								  			$content .= '</form>';
										$content .= '</div><!-- /.box-body -->';
									$content .= '</div><!-- /.box -->';
								}
							} else {
								$content .= '<div class="box">';
									$content .= '<div class="box-body">';
										$content .= '<p>Det har ikke blitt opprettet noen grupper enda.</p>';
									$content .= '</div><!-- /.box-body -->';
								$content .= '</div><!-- /.box -->';
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
				}

				$content .= '<script src="scripts/chief-groups.js"></script>';
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
					$content .= '<p>Du er ikke logget inn!</p>';
				$content .= '</div><!-- /.box-body -->';
			$content .= '</div><!-- /.box -->';
		}

		return $content;
	}
}
?>