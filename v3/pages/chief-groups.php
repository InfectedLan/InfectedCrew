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
				$groupList = GroupHandler::getGroups();
				$content .= '<script src="scripts/chief-groups.js"></script>';
				
				if (!empty($groupList)) {
					$content .= '<table>';
						$content .= '<tr>';
							$content .= '<th>Navn</th>';
							$content .= '<th>Medlemmer</th>';
							$content .= '<th>Beskrivelse</th>';
							$content .= '<th>Chief/Co-chief</th>';
						$content .= '</tr>';
						
						$userList = UserHandler::getMemberUsers();
						
						foreach ($groupList as $group) {
							$content .= '<tr>';
								$content .= '<form class="chief-groups-edit" method="post">';
									$content .= '<input type="hidden" name="id" value="' . $group->getId() . '">';
									$content .= '<td><input type="text" name="title" value="' . $group->getTitle() . '" required></td>';
									$content .= '<td>' . count($group->getMembers()) . '</td>';
									$content .= '<td><input type="text" name="description" value="' . $group->getDescription() . '" required></td>';
									$content .= '<td>';
										$content .= '<select class="chosen-select select" name="leader" data-placeholder="Velg en chief...">';
											$content .= '<option value="0"></option>';
											
											foreach ($userList as $userValue) {
												if ($userValue->equals($group->getLeader())) {
													$content .= '<option value="' . $userValue->getId() . '" selected>' . $userValue->getDisplayName() . '</option>';
												} else {
													$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
												}
											}
										$content .= '</select>';
										$content .= '<br>';
										$content .= '<select class="chosen-select select" name="coleader" data-placeholder="Velg en co-chief...">';
											$content .= '<option value="0"></option>';
											
											foreach ($userList as $userValue) {
												if ($userValue->equals($group->getCoLeader())) {
													$content .= '<option value="' . $userValue->getId() . '" selected>' . $userValue->getDisplayName() . '</option>';
												} else {
													$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
												}
											}
										$content .= '</select>';
									$content .= '</td>';
									$content .= '<td><input type="submit" value="Endre"></td>';
								$content .= '</form>';
							$content .= '</tr>';
						}
						
						$content .= '<tr>';
							$content .= '<td>Totalt:</td>';
							$content .= '<td>' . count($userList) . '</td>';
						$content .= '</tr>';
					$content .= '</table>';
					
					$content .= '<h3>Legg til et nytt crew</h3>';
					$content .= '<form class="chief-groups-add" method="post">';
						$content .= '<table>';
							$content .= '<tr>';
								$content .= '<td>Navn:</td>';
								$content .= '<td><input type="text" name="title" required></td>';
							$content .= '</tr>';
							$content .= '<tr>';
								$content .= '<td>Beskrivelse:</td>';
								$content .= '<td><input type="text" name="description" required></td>';
							$content .= '</tr>';
							$content .= '<tr>';
								$content .= '<td>Chief:</td>';
								$content .= '<td>';
									$content .= '<select class="chosen-select" name="leader" data-placeholder="Velg en chief...">';
										$content .= '<option value="0"></option>';
										
										foreach ($userList as $userValue) {
											$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
										}
									$content .= '</select>';
								$content .= '</td>';
							$content .= '</tr>';
							$content .= '<tr>';
								$content .= '<td>Co-chief:</td>';
								$content .= '<td>';
									$content .= '<select class="chosen-select" name="coleader" data-placeholder="Velg en co-chief...">';
										$content .= '<option value="0"></option>';
										
										foreach ($userList as $userValue) {
											$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
										}
									$content .= '</select>';
								$content .= '</td>';
							$content .= '</tr>';
							$content .= '<tr>';
								$content .= '<td><input type="submit" value="Legg til"></td>';
							$content .= '</tr>';
						$content .= '</table>';
					$content .= '</form>';
					
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
					
					foreach ($groupList as $group) {
						$memberList = $group->getMembers();
						
						$content .= '<h4>' . $group->getTitle() . '</h4>';
						$content .= '<table>';
							if (!empty($memberList)) {
								foreach ($memberList as $userValue) {
									$content .= '<tr>';
										$content .= '<td>' . $userValue->getDisplayName(). '</td>';
										$content .= '<td><input type="button" value="Fjern" onClick="removeUserFromGroup(' . $userValue->getId() . ')"></td>';
									$content .= '</tr>';
								}
								
								if (count($groupList) > 1) {
									$content .= '<tr>';
										$content .= '<td><input type="button" value="Fjern alle" onClick="removeUsersFromGroup(' . $group->getId() . ')"></td>';
									$content .= '</tr>';
								}
							} else {
								$content .= '<i>Det er ingen medlemmer i ' . $group->getTitle() . '.</i>';
							}
						$content .= '</table>';
					}
				} else {
					$content .= '<p>Det finnes ingen grupper enda!</p>';
				}
			} else {
				$content .= '<p>Du har ikke rettigheter til dette!</p>';
			}
		} else {
			$content .= '<p>Du er ikke logget inn!</p>';
		}

		return $content;
	}
}
?>