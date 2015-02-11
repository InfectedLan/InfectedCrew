<?php
require_once 'session.php';
require_once 'handlers/slidehandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->isGroupMember()) {
		$group = $user->getGroup();
		
		if ($user->hasPermission('*') || 
			$user->hasPermission('event.screen')) {
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
					
					foreach ($slideList as $value) {
						echo '<tr>';
							echo '<form class="slide-edit" method="post">';
								echo '<input type="hidden" name="id" value="' . $value->getId() . '">';
								echo '<td><input type="text" name="title" value="' . $value->getTitle() . '"></td>';
								echo '<td><textarea name="content">' . $value->getContent() . '</textarea></td>';
								echo '<td>';
									echo '<input type="time" name="startTime" value="' . date('H:i', $value->getStartTime()) . '">';
									echo '<input type="date" name="startDate" value="' . date('Y-m-d', $value->getStartTime()) . '"><br>';
								echo '</td>';
								echo '<td>';
									echo '<input type="time" name="endTime" value="' . date('H:i', $value->getEndTime()) . '">';
									echo '<input type="date" name="endDate" value="' . date('Y-m-d', $value->getEndTime()) . '"><br>';
								echo '</td>';
								
								if ($value->isPublished()) {
									echo '<td><input type="checkbox" name="published" value="1" checked></td>';
								} else {
									echo '<td><input type="checkbox" name="published" value="1"></td>';
								}
								
								echo '<td><input type="submit" value="Endre"></td>';
							echo '</form>';
							
							echo '<td><input type="button" value="Fjern" onClick="removeSlide(' . $value->getId() . ')"></td>';
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
		echo '<p>Du er ikke medlem av en gruppe.</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>