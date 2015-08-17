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
require_once 'handlers/compohandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('*') ||
		$user->hasPermission('event.compo')) {
		echo '<script src="scripts/event-compo.js"></script>';
		echo '<h3>Compo\'er</h3>';

		$compoList = CompoHandler::getCompos();

		if (!empty($compoList)) {
			echo '<table>';
				echo '<tr>';
					echo '<th>Navn:</th>';
					echo '<th>Tag:</th>';
					echo '<th>Beskrivelse:</th>';
					echo '<th>Modus:</th>';
					echo '<th>Premiepris:</th>';
					echo '<th>Start-tidspunkt:</th>';
					echo '<th>Påmeldingsfrist:</th>';
					echo '<th>Lag-størrelse:</th>';
				echo '</tr>';

				foreach ($compoList as $compo) {
					echo '<tr>';
						echo '<form class="compo-edit" method="post">';
							echo '<input type="hidden" name="id" value="' . $compo->getId() . '">';
							echo '<td><input type="text" name="title" value="' . $compo->getTitle() . '"></td>';
							echo '<td><input type="text" name="tag" value="' . $compo->getTag() . '"></td>';
							echo '<td><textarea name="description">' . $compo->getDescription() . '</textarea></td>';
							echo '<td><input type="text" name="mode" value="' . $compo->getMode() . '"></td>';
							echo '<td><input type="number" name="price"></td>';
							echo '<td>';
								echo '<input type="time" name="startTime" value="' . date('H:i', $compo->getStartTime()) . '">';
								echo '<br>';
								echo '<input type="date" name="startDate" value="' . date('Y-m-d', $compo->getStartTime()) . '">';
							echo '</td>';
							echo '<td>';
								echo '<input type="time" name="registrationEndTime" value="' . date('H:i', $compo->getRegistrationEndTime()) . '">';
								echo '<br>';
								echo '<input type="date" name="registrationEndDate" value="' . date('Y-m-d', $compo->getRegistrationEndTime()) . '">';
							echo '</td>';
							echo '<td><input type="number" name="teamSize" min="1"></td>';
							echo '<td><input type="submit" value="Endre"></td>';
						echo '</form>';
					echo '</tr>';
				}
			echo '</table>';
		} else {
			echo '<p>Det er ikke opprettet noen compo\'er enda.';
		}

		echo '<h3>Legg til ny compo:</h3>';
		echo '<p>Fyll ut feltene under for å legge til en ny compo.</p>';

		echo '<form class="compo-add" method="post">';
			echo '<table>';
				echo '<tr>';
					echo '<td>Navn:</td>';
					echo '<td><input type="text" name="title"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Tag:</td>';
					echo '<td><input type="text" name="tag"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Beskrivelse:</td>';
					echo '<td><textarea class="editor" name="description"></textarea></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Modus:</td>';
					echo '<td><input type="text" name="mode"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Premiepris:</td>';
					echo '<td><input type="number" name="price"></td>';
				echo '</tr>';

				echo '<tr>';
					echo '<td>Start-tidspunkt:</td>';
					echo '<td>';
						echo '<input type="time" name="startTime" value="' . date('H:i:s', $compo->getStartTime()) . '">';
						echo '<br>';
						echo '<input type="date" name="startDate" value="' . date('Y-m-d', $compo->getStartTime()) . '">';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Påmeldingsfrist:</td>';
					echo '<td>';
						echo '<input type="time" name="registrationEndTime" value="' . date('H:i:s') . '">';
						echo '<br>';
						echo '<input type="date" name="registrationEndDate" value="' . date('Y-m-d') . '">';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Lag-størrelse:</td>';
					echo '<td><input type="number" name="teamSize" min="1"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><input type="submit" value="Legg til"></td>';
				echo '</tr>';
			echo '</table>';
		echo '</form>';
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>
