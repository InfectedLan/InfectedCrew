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
require_once 'handlers/eventhandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('compo.edit')) {
        $event = EventHandler::getCurrentEvent();
		echo '<script src="scripts/compo.js"></script>';

		echo '<p>Fyll ut feltene under for å legge til en ny compo.</p>';

		echo '<form class="compo-add" method="post">';
			echo '<table>';
				echo '<tr>';
					echo '<td>Navn:</td>';
					echo '<td><input type="text" name="title" placeholder="Skriv inn et navn her..." required></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Tag:</td>';
					echo '<td><input type="text" name="tag" placeholder="Skriv inn en tag her..." required></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Spill-modus:</td>';
					echo '<td><input type="text" name="mode" placeholder="Skriv inn et spill-modus her..."></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Beskrivelse:</td>';
					echo '<td><textarea class="editor" name="description"></textarea></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Start-tidspunkt:</td>';
					echo '<td>';
                    echo '<input type="time" name="startTime" value="' . date('H:i', $event->getStartTime()) . '" required>';
						echo '<br>';
						echo '<input type="date" name="startDate" value="' . date('Y-m-d', $event->getStartTime()) . '" required>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Påmeldingsfrist:</td>';
					echo '<td>';
                    	echo '<input type="time" name="registrationEndTime" value="' . date('H:i', $event->getStartTime()-7200) . '" required>';
						echo '<br>';
						echo '<input type="date" name="registrationEndDate" value="' . date('Y-m-d', $event->getStartTime()-7200) . '" required>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Lag-størrelse:</td>';
					echo '<td><input type="number" name="teamSize" min="1" value="1" required></td>';
				echo '</tr>';
                echo '<tr>';
                	echo '<td>Maks deltagere(0 er uendelig):</td>';
                	echo '<td><input type="number" name="maxTeamCount" min="0" value="0" required></td>';
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
