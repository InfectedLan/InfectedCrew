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

	if ($user->hasPermission('chief.checklist')) {
		echo '<script src="scripts/chief-checklist.js"></script>';
		echo '<h3>Sjekklister</h3>';

		echo '<p>Dette er sjekklistene dine, gå igjennom å huk av når ting er gjort, eller klikk nederet på siden for å endre dem.</p>';

		if ($user->isGroupMember()) {
			$group = $user->getGroup();
			$commonNoteList = NoteHandler::getNotesByGroupAndTeamAndUser($user);

			if (!empty($commonNoteList)) {
				echo '<h3>Sjekkliste for ' . $group->getTitle() . '</h3>';
				echo printNotelist($commonNoteList, false);
			}
		}

		$privateNoteList = NoteHandler::getNotesByUser($user);

		if (!empty($privateNoteList)) {
			echo '<h3>Din private sjekkliste</h3>';
			echo printNotelist($privateNoteList, true);
		}

		if (empty($commonNoteList) && empty($privateNoteList)) {
			echo '<p>Det er ikke opprettet noe gjøremål i sjekklisten enda, du kan legge til gjøremål under.</p>';
		}

		echo '<a href="index.php?page=edit-checklist">Endre sjekklister</a></td>';
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
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
					$content .= '<th>Detaljer</th>';
					$content .= '<th>Når?</th>';
				$content .= '</tr>';

				foreach ($noteList as $note) {
					$content .= '<tr>';
						$content .= '<form class="chief-checklist-check" method="post">';
							$content .= '<input type="hidden" name="id" value="' . $note->getId() . '">';
							$content .= '<td><input type="checkbox" name="done" value="1"' . ($note->isDone() ? ' checked' : null) . '></td>';
							$content .= '<td>' . $note->getTitle() . '</td>';
							$content .= '<td>' . $note->getContent() . '</td>';
							$content .= '<td>' . DateUtils::getDayFromInt(date('w', $note->getAbsoluteTime())) . ' den ' . date('d', $note->getAbsoluteTime()) . '. ' . DateUtils::getMonthFromInt(date('m', $note->getAbsoluteTime())) . ' kl. ' . date('H:i', $note->getAbsoluteTime()) . '</td>';
							$content .= '<td><input type="submit" value="Endre"></td>';
						$content .= '</form>';
					$content .= '</tr>';
				}

			$content .= '</table>';
		}
	}

	return $content;
}
?>
