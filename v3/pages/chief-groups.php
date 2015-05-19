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
		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if ($user->hasPermission('*') ||
				$user->hasPermission('chief.groups')) {
				$groupList = GroupHandler::getGroups();
				echo '<script src="scripts/chief-groups.js"></script>';
				
				if (!empty($groupList)) {
					echo '<table>';
						echo '<tr>';
							echo '<th>Navn</th>';
							echo '<th>Medlemmer</th>';
							echo '<th>Beskrivelse</th>';
							echo '<th>Chief/Co-chief</th>';
						echo '</tr>';
						
						$userList = UserHandler::getMemberUsers();
						
						foreach ($groupList as $group) {
							echo '<tr>';
								echo '<form class="chief-groups-edit" method="post">';
									echo '<input type="hidden" name="id" value="' . $group->getId() . '">';
									echo '<td><input type="text" name="title" value="' . $group->getTitle() . '" required></td>';
									echo '<td>' . count($group->getMembers()) . '</td>';
									echo '<td><input type="text" name="description" value="' . $group->getDescription() . '" required></td>';
									echo '<td>';
										echo '<select class="chosen-select select" name="leader" data-placeholder="Velg en chief...">';
											echo '<option value="0"></option>';
											
											foreach ($userList as $userValue) {
												if ($userValue->equals($group->getLeader())) {
													echo '<option value="' . $userValue->getId() . '" selected>' . $userValue->getDisplayName() . '</option>';
												} else {
													echo '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
												}
											}
										echo '</select>';
										echo '<br>';
										echo '<select class="chosen-select select" name="coleader" data-placeholder="Velg en co-chief...">';
											echo '<option value="0"></option>';
											
											foreach ($userList as $userValue) {
												if ($userValue->equals($group->getCoLeader())) {
													echo '<option value="' . $userValue->getId() . '" selected>' . $userValue->getDisplayName() . '</option>';
												} else {
													echo '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
												}
											}
										echo '</select>';
									echo '</td>';
									echo '<td><input type="submit" value="Endre"></td>';
								echo '</form>';
							echo '</tr>';
						}
						
						echo '<tr>';
							echo '<td>Totalt:</td>';
							echo '<td>' . count($userList) . '</td>';
						echo '</tr>';
					echo '</table>';
					
					echo '<h3>Legg til et nytt crew</h3>';
					echo '<form class="chief-groups-add" method="post">';
						echo '<table>';
							echo '<tr>';
								echo '<td>Navn:</td>';
								echo '<td><input type="text" name="title" required></td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td>Beskrivelse:</td>';
								echo '<td><input type="text" name="description" required></td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td>Chief:</td>';
								echo '<td>';
									echo '<select class="chosen-select" name="leader" data-placeholder="Velg en chief...">';
										echo '<option value="0"></option>';
										
										foreach ($userList as $userValue) {
											echo '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
										}
									echo '</select>';
								echo '</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td>Co-chief:</td>';
								echo '<td>';
									echo '<select class="chosen-select" name="coleader" data-placeholder="Velg en co-chief...">';
										echo '<option value="0"></option>';
										
										foreach ($userList as $userValue) {
											echo '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
										}
									echo '</select>';
								echo '</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td><input type="submit" value="Legg til"></td>';
							echo '</tr>';
						echo '</table>';
					echo '</form>';
					
					echo '<h3>Medlemmer</h3>';
					
					$freeUserList = UserHandler::getNonMemberUsers();
					
					if (!empty($freeUserList)) {
						echo '<table>';
							echo '<tr>';
								echo '<form class="chief-groups-adduser" method="post">';
									echo '<td>';
										echo '<select class="chosen-select" name="userId">';
											foreach ($freeUserList as $userValue) {
												echo '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
											}
										echo '</select>';
									echo '</td>';
									echo '<td>';
										echo '<select class="chosen-select" name="groupId">';
											foreach ($groupList as $group) {
												echo '<option value="' . $group->getId() . '">' . $group->getTitle() . '</option>';
											}
										echo '</select>';
									echo '</td>';
									echo '<td><input type="submit" value="Legg til"></td>';
								echo '</form>';
							echo '</tr>';
						echo '</table>';
					} else {
						echo '<p>Alle registrerte medlemmer er allerede med i et crew.</p>';
					}
					
					foreach ($groupList as $group) {
						$memberList = $group->getMembers();
						
						echo '<h4>' . $group->getTitle() . '</h4>';
						echo '<table>';
							if (!empty($memberList)) {
								foreach ($memberList as $userValue) {
									echo '<tr>';
										echo '<td>' . $userValue->getDisplayName(). '</td>';
										echo '<td><input type="button" value="Fjern" onClick="removeUserFromGroup(' . $userValue->getId() . ')"></td>';
									echo '</tr>';
								}
								
								if (count($groupList) > 1) {
									echo '<tr>';
										echo '<td><input type="button" value="Fjern alle" onClick="removeUsersFromGroup(' . $group->getId() . ')"></td>';
									echo '</tr>';
								}
							} else {
								echo '<i>Det er ingen medlemmer i ' . $group->getTitle() . '.</i>';
							}
						echo '</table>';
					}
				} else {
					echo '<p>Det finnes ingen grupper enda!</p>';
				}
			} else {
				echo '<p>Du har ikke rettigheter til dette!</p>';
			}
		} else {
			echo '<p>Du er ikke logget inn!</p>';
		}
	}
}
?>