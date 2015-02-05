<?php
require_once 'session.php';
require_once 'handlers/eventhandler.php';
require_once 'handlers/locationhandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('admin.events')) {
		echo '<script src="scripts/admin-events.js"></script>';
		echo '<h3>Arrangementer:</h3>';
		echo '<p>Her er en liste over Infected arrangementer som har vært eller skal være. Neste arrangement blir automatisk vist på hovedsiden.</p>';
		
		echo '<table>';
			echo '<tr>';
				echo '<th>Navn:</th>';
				echo '<th>Tema:</th>';
				echo '<th>Sted:</th>';
				echo '<th>Deltakere:</th>';
				echo '<th>Booking:</th>';
				echo '<th>Seatmap:</th>';
				echo '<th>Start:</th>';
				echo '<th>Slutt:</th>';
			echo '</tr>';
			
			$eventList = EventHandler::getEvents();
			
			foreach ($eventList as $event) {
				echo '<tr>';
					echo '<form class="admin-events-edit" name="input" method="post">';
						echo '<input type="hidden" name="id" value="' . $event->getId() . '">';
						echo '<td>' . $event->getTitle() . '</td>';
						echo '<td><input type="text" name="theme" value="' . $event->getTheme() . '"></td>';
						echo '<td>';
							echo '<select class="chosen-select" name="location" required>';
								echo '<option value="' . $event->getLocation()->getId() . '">' . $event->getLocation()->getTitle() . '</option>';
							echo '</select>';
						echo '</td>';
						echo '<td><input type="number" name="participants" value="' . $event->getParticipants() . '" required></td>';
						echo '<td>';
							echo '<input type="date" name="bookingDate" value="' . date('Y-m-d', $event->getBookingTime()) . '" required>';
							echo '<input type="time" name="bookingTime" value="' . date('H:i', $event->getBookingTime()) . '" required>';
						echo '</td>';
						echo '<td><input type="button" value="Vis" onClick="viewSeatmap(' . $event->getSeatmap()->getId() . ')"></td>';
						echo '<td>';
							echo '<input type="date" name="startDate" value="' . date('Y-m-d', $event->getStartTime()) . '" required>';
							echo '<input type="time" name="startTime" value="' . date('H:i', $event->getStartTime()) . '" required>';
						echo '</td>';
						echo '<td>';
							echo '<input type="date" name="endDate" value="' . date('Y-m-d', $event->getEndTime()) . '" required>';
							echo '<input type="time" name="endTime" value="' . date('H:i', $event->getEndTime()) . '" required>';
						echo '</td>';
						echo '<td><input type="submit" value="Endre"></td>';
					echo '</form>';
					
					if ($user->hasPermission('*')) {
						echo '<td><input type="button" value="Slett" onClick="removeEvent(' . $event->getId() . ')"></td>';
					}
				echo '</tr>';
			}
		echo '</table>';
		
		echo '<h3>Legg til nytt arrangement:</h3>';
		echo '<p>Fyll ut feltene under for å legge til en ny side.</p>';
		echo '<form class="admin-events-add" method="post">';
			echo '<table>';
				echo '<tr>';
					echo '<td>Tema:</td>';
					echo '<td><input type="text" name="theme" required></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Sted:</td>';
					echo '<td>';
						echo '<select class="chosen-select" name="location">';
							foreach (LocationHandler::getLocations() as $location) {
								echo '<option value="' . $location->getId() . '">' . $location->getTitle() . '</option>';
							}
						echo '</select>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Deltakere:</td>';
					echo '<td><input type="number" name="participants" required></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Booking:</td>';
					echo '<td><input type="date" name="bookingDate" placeholder="' . date('Y-m-d') . '" required></td>';
					echo '<td><input type="time" name="bookingTime" placeholder="' . date('H:i:s') . '" required></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Start:</td>';
					echo '<td><input type="date" name="startDate" placeholder="' . date('Y-m-d') . '" required></td>';
					echo '<td><input type="time" name="startTime" placeholder="' . date('H:i:s') . '" required></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Slutt:</td>';
					echo '<td><input type="date" name="endDate" placeholder="' . date('Y-m-d') . '" required></td>';
					echo '<td><input type="time" name="endTime" placeholder="' . date('H:i:s') . '" required></td>';
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