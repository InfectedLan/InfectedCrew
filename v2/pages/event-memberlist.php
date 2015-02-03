<?php
require_once 'session.php';
require_once 'handlers/eventhandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->isGroupMember()) {
		$group = $user->getGroup();
		
		if ($user->hasPermission('*') || 
			$user->hasPermission('event.memberlist')) {
			echo '<script src="scripts/event-memberlist.js"></script>';
			echo '<h3>Medlemsliste</h3>';
			
			echo '<p>Velg år du vil hente ut medlemsliste for samt maksimal alder på medlemmene du vil ha med.</p>';
			
			echo '<form class="memberlist" method="post">';
				echo '<table>';
					echo '<tr>';
						echo '<td>År:</td>';
						echo '<td>';
							echo '<select name="year">';
								$eventList = EventHandler::getEvents();
								
								for ($year = date('Y', reset($eventList)->getStartTime()); $year <= date('Y'); $year++) {
									if ($year == date('Y')) {
										echo '<option value="' . $year . '" selected>' . $year . '</option>';
									} else {
										echo '<option value="' . $year . '">' . $year . '</option>';
									}
								}
							echo '</select>';
						echo '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Aldersgrense:</td>';
						echo '<td>';
							echo '<select name="ageLimit">';
								for ($age = 1; $age <= 100; $age++) {
									if ($age == 20) {
										echo '<option value="' . $age . '" selected>' . $age . '</option>';
									} else {
										echo '<option value="' . $age . '">' . $age . '</option>';
									}
								}
							echo '</select> År';
						echo '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td><input type="submit" value="Hent"></td>';
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