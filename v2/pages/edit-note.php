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
require_once 'handlers/notehandler.php';
require_once 'handlers/userhandler.php';
require_once 'handlers/grouphandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('event.checklist')) {
		if (isset($_GET['id']) &&
			is_numeric($_GET['id'])) {
			$note = NoteHandler::getNote($_GET['id']);

			if ($note != null) {
				echo '<script src="scripts/edit-note.js"></script>';
				echo '<h3>Endre gjøremål</h3>';

				echo editNote($note);
			} else {
				echo '<p>Gjøremålet finnes ikke.</p>';
			}
		} else {
			echo '<p>Ingen gjøremål spesifisert.</p>';
		}
	} else {
		echo '<p>Du har ikke rettigheter til dette.</p>';
	}
} else {
	echo '<p>Du er ikke logget inn.</p>';
}

function editNote(Note $note) {
	$content = null;

	if (Session::isAuthenticated()) {
		$user = Session::getCurrentUser();

		if ($user->isGroupMember()) {
			$group = $user->getGroup();

			$content .= '<table>';
				$content .= '<form class="edit-note" method="post">';
					$content .= '<input type="hidden" name="id" value="' . $note->getId() . '">';
					$content .= '<tr>';
						$content .= '<td><b>Oppgave</b></td>';
						$content .= '<td><input type="text" name="title" value="' . $note->getTitle() . '" placeholder="Skriv inn et gjøremål her..." style="width: 250px;" required></td>';
					$content .= '</tr>';
					$content .= '<tr>';
						$content .= '<td><b>Detaljer</b></td>';
						$content .= '<td><textarea name="content" rows="4" cols="50" placeholder="Skriv detaljer rundt gjøremålet her..." required>' . $note->getContent() . '</textarea></td>';
					$content .= '</tr>';
					$content .= '<tr>';
						$content .= '<td><b>Dag</b></td>';
						$content .= '<td>';
							$content .= '<select class="chosen-select edit-note-secondsOffset" name="secondsOffset">';
								$secondsOffset = $note->getSecondsOffset();

								// Søndag.
								$content .= '<option value="172800"' . ($secondsOffset <= 172800 && $secondsOffset > 86400 ? ' selected' : null) . '>Søndag</option>';

								// Lørdag.
								$content .= '<option value="86400"' . ($secondsOffset <= 86400 && $secondsOffset > 0 ? ' selected' : null) . '>Lørdag</option>';

								// Fredag.
								$content .= '<option value="0"' . ($secondsOffset <= 0 && $secondsOffset > -86400 ? ' selected' : null) . '>Fredag</option>';

								// Torsdag.
								$content .= '<option value="-86400"' . ($secondsOffset <= -86400 && $secondsOffset > -172800 ? ' selected' : null) . '>Torsdag</option>';

								// Adding weeks.
								for ($week = 1; $week <= 8; $week++) {
									$seconds = -$week * 604800;

									$content .= '<option value="' . $seconds . '"' . ($secondsOffset <= $seconds && $secondsOffset > ($seconds - 604800) ? ' selected' : null) . '>' . $week . ' ' . ($week > 1 ? 'uker' : 'uke') . ' før</option>';
								}

							$content .= '</select>';
						$content .= '</td>';
					$content .= '</tr>';
					$content .= '<tr>';
						$content .= '<td><b>Tidspunkt</b></td>';
						$content .= '<td>';
							$content .= '<input type="time" name="time" class="edit-note-time" placeholder="00:00" value="' . gmdate('H:i', $note->getTime()) . '">';
						$content .= '</td>';
					$content .= '</tr>';

					if (!$note->isPrivate()) {
						if ($user->hasPermission('*') ||
							$user->isGroupLeader() ||
							$user->isGroupCoLeader() ||
							$note->isOwner($user)) {
							$content .= '<tr>';
								$content .= '<td><b>Delegert til</b></td>';
								$content .= '<td>';

									if ($user->hasPermission('event.checklist.delegate')) {
										$content .= '<select class="chosen-select" name="groupId">';

											foreach (GroupHandler::getGroups() as $group) {
												$content .= '<option value="' . $group->getId() . '"' . ($note->hasGroup() && $group->equals($note->getGroup()) ? ' selected' : null) . '>' . $group->getTitle() . '</option>';
											}

										$content .= '</select><br>';
									}

									if ($user->hasPermission('event.checklist.delegate') ||
										($note->hasGroup() && ($user->isGroupLeader() || $user->isGroupCoLeader()))) {
										$content .= '<select class="chosen-select" name="teamId">';
											$content .= '<option value="0">Ingen</option>';

											$teamList = $user->hasPermission('event.checklist.delegate') ? TeamHandler::getTeams() : $note->getGroup()->getTeams();

											foreach ($teamList as $team) {
												$content .= '<option value="' . $team->getId() . '"' . ($note->hasTeam() && $team->equals($note->getTeam()) ? ' selected' : null) . '>' . $team->getGroup()->getTitle() . ':' . $team->getTitle() . '</option>';
											}

										$content .= '</select><br>';
									}

									if ($user->hasPermission('event.checklist.delegate') ||
										$note->isOwner($user)) {
										$content .= '<select class="chosen-select" name="userId">';
											$content .= '<option value="0">Ingen</option>';

											if ($user->hasPermission('event.checklist.delegate')) {
												$memberList = UserHandler::getMemberUsers();
											} else if ($user->isGroupLeader()) {
												$memberList = $group->getMembers();
											} else if ($user->isTeamMember() && $user->isTeamLeader()) {
												$memberList = $user->getTeam()->getMembers();
											}

											foreach ($memberList as $member) {
												if (!$member->equals($user)) {
													$content .= '<option value="' . $member->getId() . '"' . ($note->hasUser() && $member->equals($note->getUser()) ? ' selected' : null) . '>' . $member->getDisplayName() . '</option>';
												}
											}

										$content .= '</select>';
									}

								$content .= '</td>';
							$content .= '</tr>';
						}

						if ($user->hasPermission('event.checklist.watchlist') ||
							$note->isOwner($user)) {
							$content .= '<tr>';
								$content .= '<td><b>Tilskuere</b></td>';
								$content .= '<td>';
									$content .= '<select multiple class="chosen-select" name="watchingUserIdList[]" data-placeholder="Velg brukere...">';

										$watchingUserList = NoteHandler::getWatchingUsers($note);

										foreach (UserHandler::getMemberUsers() as $member) {
											$content .= '<option value="' . $member->getId() . '"' . (in_array($member, $watchingUserList) ? ' selected' : null) . '>' . $member->getDisplayName() . '</option>';
										}

									$content .= '</select>';
								$content .= '</td>';
							$content .= '</tr>';
						}
					}

					$content .= '<tr>';
						$content .= '<td><input type="submit" value="Endre"></td>';
					$content .= '</tr>';
				$content .= '</table>';
			$content .= '</form>';
		}
	}

	return $content;
}
?>
