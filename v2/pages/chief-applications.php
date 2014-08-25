<?php
require_once 'session.php';
require_once 'handlers/applicationhandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('chief.applications') ||
		$user->isGroupLeader()) {
		$group = $user->getGroup();
		$pendingApplicationList = null;
			
		if ($user->hasPermission('*') ||
			$user->hasPermission('chief.applications')) {
			$pendingApplicationList = ApplicationHandler::getPendingApplications();
		} else if ($user->isGroupLeader() &&  $user->isGroupMember()) {
			$pendingApplicationList = ApplicationHandler::getPendingApplicationsForGroup($user->getGroup());
		}
		
		echo '<script src="scripts/chief-applications.js"></script>';
		echo '<h1>Søknader</h1>';
		echo '<p>Det er for øyeblikket ' . count($pendingApplicationList) . ' søknader som trenger behandling.</p>';
		
		foreach ($pendingApplicationList as $application) {
			echo '<table>';
				echo '<tr>';
					echo '<td>Status:</td>';
					echo '<td>';
						switch ($application->getState()) {
							case 1:
								echo '<b>Ubehandlet</b>';
								break;
								
							case 2:
								echo '<b>Godkjent</b>';
								break;
								
							case 3:
								echo '<b>Avslått</b>';
								break;
						}
					echo '</td>';
				echo '</tr>';
			
				$applicationUser = $application->getUser();
			
				echo '<tr>';
					echo '<td>Gruppe:</td>';
					echo '<td>' . $application->getGroup()->getTitle() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Dato søkt:</td>';
					echo '<td>' . date('d.m.Y', $application->getDatetime()) . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Søkers navn:</td>';
					echo '<td>' . $applicationUser->getFullname(). '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>E-post:</td>';
					echo '<td>' . $applicationUser->getEmail() . '</td>';
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
					echo '<td>' . $application->getContent() . '</td>';
				echo '</tr>';
			echo '</table>';
			
			switch ($application->getState()) {
				case 1:
					echo '<form class="chief-applications-reject" method="post">';
						echo '<input type="hidden" name="id" value="' . $application->getId() . '">';
						echo '<textarea id="editor1" name="reason" rows="10" cols="80" placeholder="Skriv hvorfor du vil avslå her."></textarea>';
						echo '<script>';
							// Replace the <textarea id="editor1"> with a CKEditor
							// instance, using default configuration.
							echo 'CKEDITOR.replace(\'editor1\');';
						echo '</script>';
						echo '<input type="submit" value="Avslå">';
					echo '</form>';
					echo '<input type="button" value="Godkjenn" onClick="acceptApplication(' . $application->getId() . ')">';
					break;
					
				case 3:
					echo 'Begrunnelse for avslåelse: <i>' . $application->getReason() . '</i>';
					break;
			}
		}
	} else {
		echo 'Bare crew ledere kan se søknader.';
	}
} else {
	echo 'Du er ikke logget inn!';
}