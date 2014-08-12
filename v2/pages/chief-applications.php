<?php
require_once 'session.php';
require_once 'handlers/applicationhandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('admin') ||
		$user->isGroupMember() && $user->isGroupLeader()) {
		$group = $user->getGroup();
		$applicationList = ApplicationHandler::getPendingApplications($group);
		
		echo '<h1>Søknader</h1>';
		echo '<i>Det er for øyeblikket</i><b> ' . count($applicationList) . ' </b><i>søknader som trenger behandling.</i>';
		
		foreach ($applicationList as $application) {
			$state = $application->getState();
			
			echo '<table>';
				echo '<tr>';
					echo '<td>Status:</td>';
					echo '<td>';
						if ($state == 1) {
							echo '<b>Ubehandlet</b>';
						} else if ($state == 2) {
							echo '<b>Godkjent</b>';
						} else if ($state == 3) {
							echo '<b>Avslått</b>';
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
					echo '<td><b>' . $applicationUser->getFirstname() . ' "' . $applicationUser->getNickname() . '" ' . $applicationUser->getLastname() . '</b></td>';
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
			
			switch ($state) {
				case 1:
					// TODO: Pass $application->getId() to JavaScript.
					echo '<form class="application-reject" action="" method="post">';
						echo '<textarea id="editor1" name="content" rows="10" cols="80" placeholder="Skriv hvorfor du vil avslå her."></textarea>';
						echo '<script>';
							// Replace the <textarea id="editor1"> with a CKEditor
							// instance, using default configuration.
							echo 'CKEDITOR.replace(\'editor1\');';
						echo '</script>';
						echo '<input type="submit" value="Avslå">';
					echo '</form>';
					// TODO: Pass $application->getId() to JavaScript.
					echo '<form class="application-accept" action="" method="post">';
						echo '<input type="submit" value="Godkjenn">';
					echo '</form>';
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