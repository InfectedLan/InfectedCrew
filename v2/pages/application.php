<?php
require_once 'session.php';
require_once 'handlers/applicationhandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('chief.applications') ||
		$user->isGroupLeader()) {
		
		if (isset($_GET['id'])) {
			$application = ApplicationHandler::getApplication($_GET['id']);
			
			if ($application != null) {
				echo '<script src="scripts/application.js"></script>';
				echo '<h3>Søknad</h3>';
				
				echo '<table>';
					echo '<tr>';
						echo '<td>Status:</td>';
						echo '<td>' . $application->getStateAsString() . '</td>';
					echo '</tr>';
				
					$applicationUser = $application->getUser();
				
					echo '<tr>';
						echo '<td>Søkers navn:</td>';
						echo '<td><a href="index.php?page=my-profile&id=' . $applicationUser->getId() . '">' . $applicationUser->getFullname(). '</a></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Dato søkt:</td>';
						echo '<td>' . date('d.m.Y H:i', $application->getOpenedTime()) . '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Crew:</td>';
						echo '<td>' . $application->getGroup()->getTitle() . '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>E-post:</td>';
						echo '<td><a href="mailto:someone@example.com">' . $applicationUser->getEmail() . '</a></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Telefon:</td>';
						echo '<td>' . $applicationUser->getPhone() . '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Alder:</td>';
						echo '<td>' . $applicationUser->getAge() . '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Søknad:</td>';
						echo '<td>' . wordwrap($application->getContent(), 64, '<br>') . '</td>';
					echo '</tr>';
				echo '</table>';
				
				switch ($application->getState()) {
					case 1:
						echo '<form class="chief-applications-reject" method="post">';
							echo '<input type="hidden" name="id" value="' . $application->getId() . '">';
							echo '<textarea class="editor" name="comment" rows="10" cols="80" placeholder="Skriv hvorfor du vil avslå her."></textarea>';
							echo '<input type="submit" value="Avslå">';
						echo '</form>';
						echo '<input type="button" value="Godkjenn" onClick="acceptApplication(' . $application->getId() . ')">';
						
						if (!$application->isQueued()) {
							echo '<input type="button" value="Sett i kø" onClick="queueApplication(' . $application->getId() . ')">';
						} else {
							echo '<input type="button" value="Fjern fra kø" onClick="unqueueApplication(' . $application->getId() . ')">';
						}
						
						break;
						
					case 3:
						echo 'Begrunnelse for avslåelse: <i>' . $application->getComment() . '</i>';
						break;
				}
			} else {
				echo '<p>Den angitte søknaden finnes ikke.</p>';
			}
		} else {
			echo '<p>Ingen søknad spesifisert.</p>';
		}
	} else {
		echo 'Bare crew ledere kan se søknader.';
	}
} else {
	echo 'Du er ikke logget inn!';
}