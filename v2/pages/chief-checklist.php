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

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->isGroupMember()) {
		$group = $user->getGroup();

		if ($user->hasPermission('chief.checklist')) {
			echo '<script src="scripts/chief-checklist.js"></script>';
			echo '<h3>' . $user->getDisplayName() . '\'s sjekkliste</h3>';

			echo '<p>Dette er sjekklister der du kan legge til punkter du/andre skal huske på.<br>';
			echo 'Du har en privat sjekkliste som bare er din, om du har en spesiell stilling, har du en for den og.<br>';
			echo 'Kan velge å få et e-postvarsel tidsfristen holder på å løpe ut.</p>';

			/*
			$noteList = array();

			if ($user->isGroupLeader() || $user->isGroupCoLeader()) {
				$noteList = NoteHandler::getNotesByGroup($group);
			} else if ($user->isTeamMember() && $user->isTeamLeader()) {
				$noteList = NoteHandler::getNotesByTeam($user->getTeam());
			}
			*/

			$noteList = NoteHandler::getNotesByGroupAndUser($group, $user);

			if (!empty($noteList)) {
				echo '<h3>Sjekkliste for ' . $group->getTitle() . '</h3>';

				echo printNotelist($noteList, false);
			}

			$privateNoteList = NoteHandler::getNotesByUser($user);

			if (!empty($privateNoteList)) {
				echo '<h3>Din private sjekkliste</h3>';

				echo printNotelist($privateNoteList, true);
			}

			if (empty($noteList) && empty($privateNoteList)) {
				echo '<p>Det er ikke opprettet noe gjøremål i sjekklisten enda, du kan legge til gjøremål under.</p>';
			}

			/*
			echo '<h3>Legg til ny side:</h3>';
			echo '<p>Fyll ut feltene under for å legge til en ny side.</p>';
			echo '<form class="chief-my-crew-add" method="post">';
				echo '<input type="hidden" name="groupId" value="' . $group->getId() . '">';
				echo '<table>';
					echo '<tr>';
						echo '<td>Navn:</td>';
						echo '<td><input type="text" name="title"> (Dette blir navnet på siden).</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Tilgang:</td>';
						echo '<td>';
							echo '<select class="chosen-select select" name="teamId">';
								echo '<option value="0">Alle</option>';

								foreach ($group->getTeams() as $team) {
									echo '<option value="' . $team->getId() . '">' . $team->getTitle() . '</option>';
								}
							echo '</select>';
						echo '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Innhold:</td>';
						echo '<td>';
							echo '<textarea class="editor" name="content" rows="10" cols="80"></textarea>';
						echo '</td>';
					echo '</tr>';
				echo '</table>';
				echo '<input type="submit" value="Legg til">';
			echo '</form>';
			*/
		} else {
			echo '<p>Du har ikke rettigheter til dette!</p>';
		}
	} else {
		echo '<p>Du er ikke medlem av en gruppe.</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}

function printNotelist(array $noteList, $private) {
	$content = null;


	if (Session::isAuthenticated()) {
		$user = Session::getCurrentUser();

		if ($user->isGroupMember()) {
			$group = $user->getGroup();

			$content .= '<table>';
				$content .= '<tr>';
					$content .= '<th>Gjort?</th>';
					$content .= '<th>Oppgave</th>';
					$content .= '<th>Frist</th>';

					if (!$private) {
						$content .= '<th>Deleger</th>';
					}

					$content .= '<th>Varsling</th>';
				$content .= '</tr>';

				foreach ($noteList as $note) {
					$content .= '<tr>';
						$content .= '<form class="chief-note-edit" method="post">';
							$content .= '<input type="hidden" name="id" value="' . $note->getId() . '">';

							if ($note->isDone()) {
								$content .= '<td><input type="checkbox" name="done" value="1" checked></td>';
							} else {
								$content .= '<td><input type="checkbox" name="done" value="1"></td>';
							}

							$content .= '<td><input type="text" name="content" value="' . $note->getContent() . '" required></a></td>';
							$content .= '<td>';
								$content .= '<input type="time" name="deadlineTime" value="' . date('H:i', $note->getDeadlineTime()) . '">';
								$content .= '<br>';
								$content .= '<input type="date" name="deadlineDate" value="' . date('Y-m-d', $note->getDeadlineTime()) . '">';
							$content .= '</td>';

							if (!$private) {
								$content .= '<td>';
									$content .= '<select class="chosen-select" name="userId">';
										$content .= '<option value="0">Meg</option>';

										foreach ($group->getMembers() as $member) {
											if ($note->hasUser() && $member->equals($note->getUser())) {
												$content .= '<option value="' . $member->getId() . '" selected>' . $member->getDisplayName(). '</option>';
											} else {
												$content .= '<option value="' . $member->getId() . '">' . $member->getDisplayName(). '</option>';
											}
										}

									$content .= '</select>';
								$content .= '</td>';
							}

							$content .= '<td>';
								$content .= '<select class="chosen-select" name="notificationTimeBeforeOffset">';
									$content .= '<option value="0">Ingen</option>';

									// Adding days.
									for ($day = 1; $day <= 6; $day++) {
										$seconds = $day * 86400;

										if ($seconds == $note->getNotificationTimeBeforeOffset()) {
											$content .= '<option value="' . $seconds . '" selected>' . $day . ' ' . ($day > 1 ? 'dager' : 'dag') . ' før</option>';
										} else {
											$content .= '<option value="' . $seconds . '">' . $day . ' ' . ($day > 1 ? 'dager' : 'dag') . ' før</option>';
										}
									}

									// Adding weeks.
									for ($week = 1; $week <= 6; $week++) {
										$seconds = $week * 604800;

										if ($seconds == $note->getNotificationTimeBeforeOffset()) {
										 $content .= '<option value="' . $seconds . '" selected>' . $week . ' ' . ($week > 1 ? 'uker' : 'uke') . ' før</option>';
										} else {
											$content .= '<option value="' . $seconds . '">' . $week . ' ' . ($week > 1 ? 'uker' : 'uke') . ' før</option>';
										}
									}

								$content .= '</select>';
							$content .= '</td>';
							$content .= '<td><input type="submit" value="Endre"></td>';
							$content .= '<td><input type="button" value="Fjern" onClick="removeNote(' . $note->getId() . ')"></td>';
						$content .= '</form>';
					$content .= '</tr>';
				}

			$content .= '</table>';
		}
	}

	return $content;
}
?>
