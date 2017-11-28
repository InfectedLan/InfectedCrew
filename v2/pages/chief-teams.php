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
require_once 'handlers/grouphandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('*') ||
		$user->hasPermission('chief.team')) {
			echo '<script src="scripts/chief-teams.js"></script>';

			if ($user->hasPermission('*') && !isset($_GET['groupId'])) {
				$groupList = GroupHandler::getGroups();
				echo '<h3>Crewliste</h3>';
				echo '<p>Velg et crew du vil administere lagene for.</p>';

				if (!empty($groupList)) {
					echo '<table>';
						echo '<tr>';
							echo '<th>Crew</th>';
							echo '<th>Antall lag</th>';
						echo '</tr>';

						foreach ($groupList as $group) {
							echo '<tr>';
								echo '<td>' . $group->getTitle() . '</td>';
								echo '<td>' . count($group->getTeams()) . '</td>';
								echo '<td><input type="button" value="Vis" onClick="viewGroup(' . $group->getId() . ')"></td>';
							echo '</tr>';
						}
					echo '</table>';
				} else {
					echo '<p>Det finnes ingen grupper for dette arrangementet.</p>';
				}
			} else {
				$group = isset($_GET['groupId']) ? GroupHandler::getGroup($_GET['groupId']) : $user->getGroup();

				if ($group != null) {
					$teamList = $group->getTeams();
					$userList = $group->getMembers();

					echo '<h3>Lag i ' . $group->getTitle() . '</h3>';

					if (!empty($teamList)) {
						echo '<table>';
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
													if ($team->isLeader($userValue)) {
														echo '<option value="' . $userValue->getId() . '" selected>' . $userValue->getDisplayName() . '</option>';
													} else {
														echo '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
													}
												}
											echo '</select>';
										echo '</td>';
										echo '<td><input type="submit" value="Endre"></td>';
									echo '</form>';
									echo '<td><input type="button" value="Slett" onClick="removeTeam(' . $team->getId() . ')"></td>';
								echo '</tr>';
							}
						echo '</table>';
					}

					echo '<h3>Legg til et nytt lag i "' . $group->getTitle() . '"</h3>';
					echo '<form class="chief-teams-add" method="post">';
						echo '<input type="hidden" name="groupId" value="' . $group->getId() . '">';
						echo '<table>';
							echo '<tr>';
								echo '<td>Navn:</td>';
								echo '<td><input type="text" name="title" required></td>';
							echo '<tr>';
							echo '</tr>';
							echo '<tr>';
								echo '<td>Beskrivelse:</td>';
								echo '<td><input type="text" name="description" required></td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td>Shift-leder:</td>';
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
								echo '<td><input type="submit" value="Legg til"></td>';
							echo '</tr>';
						echo '</table>';
					echo '</form>';

					if (!empty($teamList)) {
						echo '<h3>Medlemmer</h3>';

						$freeUserList = $team->getMembers(); // TODO: Verify this, removed getFreeUsers($group); here to support multi-teams.

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
											echo '<td><input type="button" value="Fjern" onClick="removeUserFromTeam(' . $userValue->getId() . ', ' . $team->getId() . ')"></td>';
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
				} else {
					echo '<p>Den angitte gruppen finnes ikke!</p>';
				}
			}
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}

// TODO: Do this in SQL.
function getFreeUsers($group) {
	$freeUserList = $group->getMembers();

	foreach ($freeUserList as $key => $freeUser) {
		if ($freeUser->isTeamMember()) {
			unset($freeUserList[$key]);
		}
	}

	return $freeUserList;
}
?>
