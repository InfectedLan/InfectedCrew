<?php
require_once 'session.php';
require_once 'handlers/applicationhandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('chief.applications')) {
		$group = $user->getGroup();
		$pendingApplicationList = null;
		$queuedApplicationList = null;
		$acceptedApplicationList = null;
			
		if ($user->hasPermission('*') || 
			$user->hasPermission('chief.applications')) {
			$pendingApplicationList = ApplicationHandler::getPendingApplications();
			$queuedApplicationList = ApplicationHandler::getQueuedApplications();
			$acceptedApplicationList = ApplicationHandler::getAcceptedApplications();
		} else if ($user->isGroupLeader() && $user->isGroupMember()) {
			$pendingApplicationList = ApplicationHandler::getPendingApplicationsForGroup($group);
			$queuedApplicationList = ApplicationHandler::getQueuedApplicationsForGroup($group);
			$acceptedApplicationList = ApplicationHandler::getAcceptedApplicationsForGroup($group);
		}
		
		echo '<script src="scripts/chief-applications.js"></script>';
		echo '<h3>Søknader</h3>';
		
		echo '<h3>Åpne søknader:</h3>';
		
		if (!empty($pendingApplicationList)) {
			echo '<table>';
				echo '<tr>';
					echo '<th>Søker\'s navn</th>';
					echo '<th>Crew</th>';
					echo '<th>Dato søkt</th>';
					echo '<th>Status</th>';
				echo '</tr>';
				
				foreach ($pendingApplicationList as $application) {
					$applicationUser = $application->getUser();
					
					echo '<tr>';
						echo '<td><a href="index.php?page=my-profile&id=' . $applicationUser->getId() . '">' . $applicationUser->getFullName() . '</a></td>';
						echo '<td>' . $application->getGroup()->getTitle() . '</td>';
						echo '<td>' . date('d.m.Y H:i', $application->getOpenedTime()) . '</td>';
						echo '<td>';
							if ($application->isQueued()) {
									echo '<b>Står i kø</b>';
							} else {
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
							}
							echo '</td>';
						echo '<td><input type="button" value="Vis" onClick="viewApplication(' . $application->getId() . ')"></td>';
						echo '<td><input type="button" value="Sett i kø" onClick="queueApplication(' . $application->getId() . ')"></td>';
					echo '</tr>';
				}
			echo '</table>';
		} else {
			echo '<p>Det er ingen søknader som venter på godkjenning.</p>';
		}
		
		echo '<h3>Søknader i kø:</h3>';
		
		if (!empty($queuedApplicationList)) {
			echo '<table>';
				echo '<tr>';
					echo '<th>Plass</th>';
					echo '<th>Søker\'s navn</th>';
					echo '<th>Crew</th>';
					echo '<th>Dato søkt</th>';
					echo '<th>Status</th>';
				echo '</tr>';
				
				$index = 1;
				
				foreach ($queuedApplicationList as $application) {
					$applicationUser = $application->getUser();
				
					echo '<tr>';
						echo '<td>' . $index . '</td>';
						echo '<td><a href="index.php?page=my-profile&id=' . $applicationUser->getId() . '">' . $applicationUser->getFullName() . '</a></td>';
						echo '<td>' . $application->getGroup()->getTitle() . '</td>';
						echo '<td>' . date('d.m.Y H:i', $application->getOpenedTime()) . '</td>';
						echo '<td>';
							if ($application->isQueued()) {
									echo '<b>Står i kø</b>';
							} else {
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
							}
							echo '</td>';
						echo '<td><input type="button" value="Vis" onClick="viewApplication(' . $application->getId() . ')"></td>';
						echo '<td><input type="button" value="Fjern fra kø" onClick="unqueueApplication(' . $application->getId() . ')"></td>';
					echo '</tr>';
					
					$index++;
				}
			echo '</table>';
		} else {
			echo '<p>Det er ingen søknader i køen.</p>';
		}
		
		echo '<h3>Tidligere søknader:</h3>';
		
		if (!empty($acceptedApplicationList)) {
			echo '<table>';
				echo '<tr>';
					echo '<th>Søker\'s navn</th>';
					echo '<th>Crew</th>';
					echo '<th>Dato søkt</th>';
				echo '</tr>';
				
				foreach ($acceptedApplicationList as $application) {
					$applicationUser = $application->getUser();
					
					echo '<tr>';
						echo '<td><a href="index.php?page=my-profile&id=' . $applicationUser->getId() . '">' . $applicationUser->getFullName() . '</a></td>';
						echo '<td>' . $application->getGroup()->getTitle() . '</td>';
						echo '<td>' . date('d.m.Y H:i', $application->getOpenedTime()) . '</td>';
					echo '</tr>';
				}
			echo '</table>';
		} else {
			echo '<p>Det er ingen godkjente søknader i arkivet.</p>';
		}
	} else {
		echo 'Bare crew ledere kan se søknader.';
	}
} else {
	echo 'Du er ikke logget inn!';
}