/*
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2015 Infected <http://infected.no/>.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

<?php
require_once 'session.php';
require_once 'handlers/agendahandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->isGroupMember()) {
		$group = $user->getGroup();
		
		if ($user->hasPermission('*') || 
			$user->hasPermission('event.agenda')) {
			echo '<script src="scripts/event-agenda.js"></script>';
			echo '<h3>Agenda</h3>';
			
			$agendaList = AgendaHandler::getAgendas();
			
			if (!empty($agendaList)) {
				echo '<table>';
					echo '<tr>';
						echo '<th>Navn:</th>';
						echo '<th>Informasjon:</th>';
						echo '<th>Tid:</th>';
					echo '</tr>';
					
					foreach ($agendaList as $agenda) {
						echo '<tr>';
							echo '<form class="agenda-edit" method="post">';
								echo '<input type="hidden" name="id" value="' . $agenda->getId() . '">';
								echo '<td><input type="text" name="title" value="' . $agenda->getTitle() . '"></td>';
								echo '<td><textarea name="description">' . $agenda->getDescription() . '</textarea></td>';
								echo '<td>';
									echo '<input type="time" name="startTime" value="' . date('H:i', $agenda->getStartTime()) . '">';
									echo '<br>';
									echo '<input type="date" name="startDate" value="' . date('Y-m-d', $agenda->getStartTime()) . '">';
								echo '</td>';
								
								if ($agenda->isPublished()) {
									echo '<td><input type="checkbox" name="published" value="1" checked></td>';
								} else {
									echo '<td><input type="checkbox" name="published" value="1"></td>';
								}
								
								echo '<td><input type="submit" value="Endre"></td>';
							echo '</form>';
							echo '<td><input type="button" value="Fjern" onClick="removeAgenda(' . $agenda->getId() . ')"></td>';
						echo '</tr>';
					}
				echo '</table>';
			} else {
				echo '<p>Det er ikke opprettet noen agenda\'er enda.';
			}
			
			echo '<h3>Legg til ny agenda:</h3>';
			echo '<p>Fyll ut feltene under for å legge til en ny agenda.</p>';
			
			echo '<form class="agenda-add" method="post">';
				echo '<table>';
					echo '<tr>';
						echo '<td>Navn:</td>';
						echo '<td><input type="text" name="title"></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Informasjon:</td>';
						echo '<td><textarea class="editor" name="description"></textarea></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Tid:</td>';
						echo '<td><input type="time" name="startTime" value="' . date('H:i:s') . '"></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Dato:</td>';
						echo '<td><input type="date" name="startDate" value="' . date('Y-m-d') . '"></td>';
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
		echo '<p>Du er ikke medlem av en gruppe.</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>