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
		echo '<p>Det er for øyeblikket ' . count($pendingApplicationList) . ' søknader som venter på godkjenning.</p>';
		
		echo '<table>';
			echo '<tr>';
				echo '<th>Søker\'s navn</th>';
				echo '<th>Crew</th>';
				echo '<th>Dato</th>';
				echo '<th>Status</th>';
			echo '</tr>';
			
			foreach ($pendingApplicationList as $application) {
				$applicationUser = $application->getUser();
			
				echo '<tr>';
					echo '<td>' . $applicationUser->getDisplayName() . '</td>';
					echo '<td>' . $application->getGroup()->getTitle() . '</td>';
					echo '<td>' . date('d.m.Y', $application->getDatetime()) . '</td>';
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
					echo '<td><input type="button" value="Vis" onClick="viewApplication(' . $application->getId() . ')"></td>';
					echo '<td><input type="button" value="Slett" onClick="removeApplication(' . $application->getId() . ')"></td>';
				echo '</tr>';
			}
		echo '</table>';
	} else {
		echo 'Bare crew ledere kan se søknader.';
	}
} else {
	echo 'Du er ikke logget inn!';
}