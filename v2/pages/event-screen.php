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
require_once 'handlers/slidehandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('event.screen')) {
		echo '<script src="scripts/event-screen.js"></script>';
		echo '<h3>Slides</h3>';

		$slideList = SlideHandler::getSlides();

		if (!empty($slideList)) {
			echo '<table>';
				echo '<tr>';
					echo '<th>Navn</th>';
					echo '<th>Informasjon</th>';
					echo '<th>Start</th>';
					echo '<th>Slutt<th>';
					echo '<th>Publisert?</th>';
				echo '</tr>';

				foreach ($slideList as $slide) {
					echo '<tr>';
						echo '<form class="slide-edit" method="post">';
							echo '<input type="hidden" name="id" value="' . $slide->getId() . '">';
							echo '<td><input type="text" name="title" value="' . $slide->getTitle() . '"></td>';
							echo '<td><textarea name="content">' . $slide->getContent() . '</textarea></td>';
							echo '<td>';
								echo '<input type="time" name="startTime" value="' . date('H:i', $slide->getStartTime()) . '">';
								echo '<input type="date" name="startDate" value="' . date('Y-m-d', $slide->getStartTime()) . '"><br>';
							echo '</td>';
							echo '<td>';
								echo '<input type="time" name="endTime" value="' . date('H:i', $slide->getEndTime()) . '">';
								echo '<input type="date" name="endDate" value="' . date('Y-m-d', $slide->getEndTime()) . '"><br>';
							echo '</td>';

							if ($slide->isPublished()) {
								echo '<td><input type="checkbox" name="published" value="1" checked></td>';
							} else {
								echo '<td><input type="checkbox" name="published" value="1"></td>';
							}

							echo '<td><input type="submit" value="Endre"></td>';
						echo '</form>';

						echo '<td><input type="button" value="Fjern" onClick="removeSlide(' . $slide->getId() . ')"></td>';
					echo '</tr>';
				}
			echo '</table>';
		} else {
			echo '<p>Det er ikke opprettet noen slide\'er enda.';
		}

		echo '<h3>Legg til ny slide:</h3>';
		echo '<p>Fyll ut feltene under for å legge til en ny slide.</p>';

		echo '<table>';
			echo '<form class="slide-add" method="post">';
				echo '<tr>';
					echo '<td>Tittel:</td>';
					echo '<td><input type="text" name="title"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Innhold:</td>';
					echo '<td><textarea class="editor" name="content" rows="10" cols="80"></textarea></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Start tidspunkt:</td>';
					echo '<td><input type="time" name="startTime" value="' . date('H:i:s') . '"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td></td>';
					echo '<td><input type="date" name="startDate" value="' . date('Y-m-d') . '"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Slutt tidspunkt:</td>';
					echo '<td><input type="time" name="endTime" value="' . date('H:i:s') . '"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td></td>';
					echo '<td><input type="date" name="endDate" value="' . date('Y-m-d') . '"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><input type="submit" value="Legg til"></td>';
				echo '</tr>';
			echo '</form>';
		echo '</table>';
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>
