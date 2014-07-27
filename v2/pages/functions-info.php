<?php
require_once 'session.php';
require_once 'handlers/agendahandler.php';
require_once 'handlers/slidehandler.php';

$site = 'https://infected.no/v7/';
$returnPage = basename(__FILE__, '.php');

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->isGroupMember()) {
		$group = $user->getGroup();
		
		if ($user->isGroupLeader() || 
			$group->getId() == 15 || 
			$group->getId() == 26 || 
			$user->hasPermission('admin') || 
			$user->hasPermission('crew-admin') ||
			$user->hasPermission('function-info')) {
			echo '<h1>Infoskjerm</h1>';
			
			echo '<h3>Agenda</h3>';
			
			$agendaList = AgendaHandler::getAgendas();
			
			if (!empty($agendaList)) {
				echo '<table>';
					echo '<tr>';
						echo '<th>Dato:</th>';
						echo '<th>Tid:</th>';
						echo '<th>Navn:</th>';
						echo '<th>Informasjon:</th>';
					echo '</tr>';
					
					foreach ($agendaList as $agenda) {
						echo '<tr>';
							echo '<form action="scripts/process_agenda.php?action=3&id=' . $agenda->getId() . '&returnPage=' . $returnPage . '" method="post">';
								echo '<td><input type="date" name="date" value="' . date('Y-m-d', $agenda->getDatetime()) . '"></td>';
								echo '<td><input type="time" name="time" value="' . date('H:i', $agenda->getDatetime()) . '"></td>';
								echo '<td><input type="text" name="name" value="' . $agenda->getName() . '"></td>';
								echo '<td><input type="text" name="description" value="' . $agenda->getDescription() . '"></td>';
								echo '<td><input type="submit" value="Endre"></td>';
							echo '</form>';
							echo '<form name="input" action="scripts/process_agenda.php?action=2&id=' . $agenda->getId() . '&returnPage=' .  $returnPage . '" method="post">';
								echo '<td><input type="submit" value="Slett"></td>';
							echo '</form>';
						echo '</tr>';
					}
				echo '</table>';
			} else {
				echo '<p>Det er ikke opprettet noen agenda\'er enda.';
			}
			
			echo '<h3>Legg til ny agenda:</h3>';
			echo '<p>Fyll ut feltene under for å legge til en ny agenda.</p>';
			
			echo '<form action="scripts/process_agenda.php?action=1&returnPage=' . $returnPage . '" method="post">';
				echo '<table>';
					echo '<tr>';
						echo '<td>Dato:</td>';
						echo '<td><input type="date" name="date"></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Start:</td>';
						echo '<td><input type="time" name="time"></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Navn:</td>';
						echo '<td><input type="text" name="name"></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Informasjon:</td>';
						echo '<td><input type="text" name="description"></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td><input type="submit" value="Legg til"></td>';
					echo '</tr>';
				echo '</table>';
			echo '</form>';
			
			echo '<h3>Slides</h3>';
			
			$slideList = SlideHandler::getSlides();
			
			if (!empty($slideList)) {
				echo '<table>';
					echo '<tr>';
						echo '<th>Start:</th>';
						echo '<th>Slutt:</th>';
						echo '<th>Navn:</th>';
						echo '<th>Informasjon:</th>';
						echo '<th>Publisert?</th>';
					echo '</tr>';
					
					foreach ($slideList as $slide) {
						echo '<tr>';
							echo '<form action="scripts/process_slide.php?action=3&id=' . $slide->getId() . '&returnPage=' . $returnPage . '" method="post">';
								echo '<td><input type="date" name="startDate" value="' . date('Y-m-d', $slide->getStart()) . '">';
								echo '<input type="time" name="startTime" value="' . date('H:i', $slide->getStart()) . '"></td>';
								echo '<td><input type="date" name="endDate" value="' . date('Y-m-d', $slide->getEnd()) . '">';
								echo '<input type="time" name="endTime" value="' . date('H:i', $slide->getEnd()) . '"></td>';
								echo '<td><input type="text" name="title" value="' . htmlspecialchars($slide->getTitle(), ENT_QUOTES) . '"></td>';
								echo '<td><input type="text" name="content" value="' . htmlspecialchars($slide->getContent(), ENT_QUOTES) . '"></td>';
								if ($slide->isPublished()) {
									echo '<td><input type="checkbox" name="published" value="1" checked></td>';
								} else {
									echo '<td><input type="checkbox" name="published" value="1"></td>';
								}
								echo '<td><input type="submit" value="Endre"></td>';
							echo '</form>';
							echo '<form name="input" action="scripts/process_slide.php?action=2&id=' . $slide->getId() . '&returnPage=' .  $returnPage . '" method="post">';
								echo '<td><input type="submit" value="Slett"></td>';
							echo '</form>';
						echo '</tr>';
					}
				echo '</table>';
			} else {
				echo '<p>Det er ikke opprettet noen slide\'er enda.';
			}
			
			echo '<h3>Legg til ny slide:</h3>';
			echo '<p>Fyll ut feltene under for å legge til en ny slide.</p>';
			
			echo '<form action="scripts/process_slide.php?action=1&returnPage=' . $returnPage . '" method="post">';
				echo '<table>';
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
						echo '<td>Tittel:</td>';
						echo '<td><input type="text" name="title"></td>';
					echo '</tr>';
				echo '</table>';
				echo '<textarea id="editor1" name="content" rows="10" cols="80"></textarea>';
				echo '<script>';
					// Replace the <textarea id="editor1"> with a CKEditor
					// instance, using default configuration.
					echo 'CKEDITOR.replace(\'editor1\');';
				echo '</script>';
				echo '<input type="submit" value="Legg til">';
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