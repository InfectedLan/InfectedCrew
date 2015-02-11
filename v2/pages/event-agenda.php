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
					
					foreach ($agendaList as $value) {
						echo '<tr>';
							echo '<form class="agenda-edit" method="post">';
								echo '<input type="hidden" name="id" value="' . $value->getId() . '">';
								echo '<td><input type="text" name="title" value="' . $value->getTitle() . '"></td>';
								echo '<td><textarea name="description">' . $value->getContent() . '</textarea></td>';
								echo '<td>';
									echo '<input type="time" name="startTime" value="' . date('H:i', $value->getStartTime()) . '">';
									echo '<br>';
									echo '<input type="date" name="startDate" value="' . date('Y-m-d', $value->getStartTime()) . '">';
								echo '</td>';
								
								if ($value->isPublished()) {
									echo '<td><input type="checkbox" name="published" value="1" checked></td>';
								} else {
									echo '<td><input type="checkbox" name="published" value="1"></td>';
								}
								
								echo '<td><input type="submit" value="Endre"></td>';
							echo '</form>';
							echo '<td><input type="button" value="Fjern" onClick="removeAgenda(' . $value->getId() . ')"></td>';
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