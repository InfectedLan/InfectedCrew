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
		$queuedApplicationList = null;
			
		if ($user->hasPermission('*') || 
			$user->hasPermission('chief.applications')) {
			$pendingApplicationList = ApplicationHandler::getPendingApplications();
			$queuedApplicationList = ApplicationHandler::getQueuedApplications();
		} else if ($user->isGroupLeader() && $user->isGroupMember()) {
			$pendingApplicationList = ApplicationHandler::getPendingApplicationsForGroup($user->getGroup());
			$queuedApplicationList = ApplicationHandler::getQueuedApplicationsForGroup($user->getGroup());
		}
		
		echo '<script src="scripts/chief-applications.js"></script>';
		echo '<h3>Søknader</h3>';
		
		echo '<h3>Åpene søknader:</h3>';
		
		if (!empty($pendingApplicationList)) {
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
						echo '<td><a href="index.php?page=my-profile&id=' . $applicationUser->getId() . '">' . $applicationUser->getDisplayName() . '</a></td>';
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
						echo '<td><input type="button" value="Slett" onClick="removeApplication(' . $application->getId() . ')"></td>';
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
					echo '<th>Søker\'s navn</th>';
					echo '<th>Crew</th>';
					echo '<th>Dato</th>';
					echo '<th>Status</th>';
				echo '</tr>';
				
				foreach ($queuedApplicationList as $application) {
					$applicationUser = $application->getUser();
				
					echo '<tr>';
						echo '<td><a href="index.php?page=my-profile&id=' . $applicationUser->getId() . '">' . $applicationUser->getDisplayName() . '</a></td>';
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
						echo '<td><input type="button" value="Slett" onClick="removeApplication(' . $application->getId() . ')"></td>';
					echo '</tr>';
				}
			echo '</table>';
		} else {
			echo '<p>Det er ingen søknader i køen.</p>';
		}
	} else {
		echo 'Bare crew ledere kan se søknader.';
	}
} else {
	echo 'Du er ikke logget inn!';
}