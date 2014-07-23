<?php
require_once 'utils.php';

if (Utils::isAuthenticated()) {
	$user = Utils::getUser();
	
	if ($user->hasPermission('admin.events') ||
		$user->hasPermission('admin')) {
		
		echo '<h3>Arrangementer:</h3>';
		echo '<p>Her er en liste over Infected arrangementer som har vært eller skal være. Neste arrangement blir automatisk vist på hovedsiden.</p>';
		
		echo '<table>';
			echo '<tr>';
				echo '<th>Navn:</th>';
				echo '<th>Tema:</th>';
				echo '<th>Deltakere:</th>';
				echo '<th>Billettpris:</th>';
				echo '<th>Start:</th>';
				echo '<th>Slutt:</th>';
			echo '</tr>';
			
			$eventList = EventHandler::getEvents();
			
			foreach ($eventList as $value) {
				echo '<tr>';
					echo '<form name="input" action="scripts/process_event.php?action=3&id=' . $value->getId() . '" method="post">';
						echo '<td>';
							$when = date('m', $value->getStartTime()) == 2 ? 'Vinter' : 'Høst';
							
							echo 'Infected ' . $when . ' ' . date('Y', $value->getStartTime());
						echo '</td>';
						
						echo '<td><input type="text" name="theme" value="' . $value->getTheme() . '"></td>';
						echo '<td><input type="text" name="participants" value="' . $value->getParticipants() . '"></td>';
						echo '<td><input type="text" name="price" value="' . $value->getPrice() . '"></td>';			
						
						echo '<td><input type="date" name="startDate" value="' . date('Y-m-d', $value->getStartTime()) . '"><input type="time" name="startTime" value="' . date('H:i', $value->getStartTime()) . '"></td>';
						echo '<td><input type="date" name="endDate" value="' . date('Y-m-d', $value->getEndTime()) . '"><input type="time" name="endTime" value="' . date('H:i', $value->getEndTime()) . '"></td>';
						echo '<td><input type="submit" value="Endre"></td>';
					echo '</form>';
				echo '</tr>';
			}
		echo '</table>';
		
		echo '<h3>Legg til nytt arrangement:</h3>';
		echo '<p>Fyll ut feltene under for å legge til en ny side.</p>';
		echo '<form action="scripts/process_event.php?action=1" method="post">';
			echo '<table>';
				echo '<tr>';
					echo '<td>Tema:</td>';
					echo '<td><input type="text" name="theme"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Deltakere:</td>';
					echo '<td><input type="text" name="participants"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Billettpris:</td>';
					echo '<td><input type="text" name="price"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Start:</td>';
					echo '<td><input type="date" name="startDate"></td>';
					echo '<td><input type="time" name="startTime"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Slutt:</td>';
					echo '<td><input type="date" name="endDate"></td>';
					echo '<td><input type="time" name="endTime"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><input type="submit" value="Legg til"></td>';
				echo '</tr>';
			echo '</table>';
		echo '</form>';
	} else {
		echo 'Du har ikke rettigheter til dette!';
	}
} else {
	echo 'Du er ikke logget inn!';
}
?>