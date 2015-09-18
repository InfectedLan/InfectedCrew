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
require_once 'utils/dateutils.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('event.checklist')) {
		echo '<script src="scripts/event-checklist.js"></script>';
		echo '<style>';
			echo 'table {';
				echo 'border-spacing: 0px;';
			echo '}';

			echo 'th {';
				echo 'text-align: left;';
			echo '}';

			echo 'td {';
				echo 'padding: 4px;';
			echo '}';
		echo '</style>';

		echo '<h3>Sjekklister</h3>';
		echo '<p>Dette er sjekklistene dine, gå igjennom å huk av når ting er gjort, eller klikk nederet på siden for å endre dem.</p>';
		echo '<img src="images/checklist-description.jpg" alt="Beskrivelse av farger">';

		if ($user->isGroupMember()) {
			$group = $user->getGroup();
			$commonNoteList = NoteHandler::getNotesByGroupAndTeamAndUser($user);

			if (!empty($commonNoteList)) {
				echo '<h3>Sjekkliste for din stilling</h3>';
				echo getNotelist($commonNoteList, false);
			}
		}

		$privateNoteList = NoteHandler::getNotesByUser($user);

		if (!empty($privateNoteList)) {
			echo '<h3>Din private sjekkliste</h3>';
			echo getNotelist($privateNoteList, true);
		}

		if ($user->hasPermission('event.checklist.list')) {
			$noteList = NoteHandler::getNotes();

			if (!empty($noteList)) {
				echo '<h3>Alle\'s sjekkliste</h3>';
				echo getNotelist($noteList, false);
			}
		}

		if (empty($noteList) && empty($commonNoteList) && empty($privateNoteList)) {
			echo '<p>Det er ikke opprettet noe gjøremål i sjekklisten enda, du kan legge til gjøremål under.</p>';
		}

		echo '<a href="index.php?page=edit-checklist">Endre sjekklister</a></td>';
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}

function getNotelist(array $noteList, $private) {
	$content = null;

	if (Session::isAuthenticated()) {
		$user = Session::getCurrentUser();

		if ($user->isGroupMember()) {
			$group = $user->getGroup();

			$content .= '<table>';
				$content .= '<tr>';
					$content .= '<th>Ferdig?</th>';
					$content .= '<th>Oppgave</th>';
					$content .= '<th>Tidspunkt</th>';
					$content .= '<th>Ansvarlig</th>';
					$content .= '<th>Detaljer</th>';
				$content .= '</tr>';

				foreach ($noteList as $note) {
					$color = "#ffffff";

					if ($note->isDone()) { // Punker som er ferdig: Teskten blir grønn
						$color = "#44ce44"; // Green
					} else if ($note->isExpired()) { // Punkter som er over tiden: Tesksten blir rød
						$color = "#ff5151"; // Red
					} else if ($note->isDelegated() && $note->isUser($user)) { // Punkter du har fått delegert: En annen blåtone bakgrunn
						$color = "#8acfff"; // Blue tone
					} else if ($note->isDelegated() && $note->isOwner($user)) { // Punkter du har delegert bort: lyslilla bakgrunn
						$color = "#b289e1"; // Purple
					} else if (!$note->isPrivate() && $note->isUser($user)) { // Stilling: Blå bakgrunn
						$color = "#3f94ff"; // Blue
					}

					$content .= '<tr style="background: ' . $color . ';">';
						$content .= '<form class="event-checklist-check" method="post">';
							$content .= '<input type="hidden" name="id" value="' . $note->getId() . '">';
							$content .= '<td><input type="checkbox" name="done" value="1"' . ($note->isDone() ? ' checked' : null) . '></td>';
							$content .= '<td>' . $note->getTitle() . '</td>';
							$content .= '<td>';
								$secondsOffset = $note->getSecondsOffset();

								if ($secondsOffset >= -86400 && $secondsOffset <= 172800) {
									$content .= DateUtils::getDayFromInt(date('w', $note->getAbsoluteTime())) . ' ' . date('H:i', $note->getAbsoluteTime());
								} else {
									$week = abs(round($secondsOffset / 604800));

									$content .= $week . ' ' . ($week > 1 ? 'uker' : 'uke') . ' før';
								}

							$content .= '</td>';
						$content .= '</form>';
						$content .= '<td>' . ($note->hasOwner() || $note->hasUser($user) ? $note->getUser()->getFirstname() : 'Ingen') . '</td>';
						$content .= '<td>';
							$content .= '<div class="slidingBox">';
								$content .= '<a href="#" class="show_hide">Vis</a>';
								$content .= '<div class="details">' . $note->getContent() . '</div>';
							$content .= '</div>';
						$content .= '</td>';
					$content .= '</tr>';
				}

			$content .= '</table>';
		}
	}

	return $content;
}
?>
