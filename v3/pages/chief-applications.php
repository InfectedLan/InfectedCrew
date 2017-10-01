<?php
/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2015 Infected <http://infected.no/>.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3.0 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'session.php';
require_once 'handlers/applicationhandler.php';
require_once 'handlers/eventhandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('chief.applications')) {
		$pendingApplicationList = null;
		$queuedApplicationList = null;
		$previousApplicationList = null;

		if ($user->hasPermission('*')) {
			$pendingApplicationList = ApplicationHandler::getPendingApplications();
			$queuedApplicationList = ApplicationHandler::getQueuedApplications();
			$previousApplicationList = ApplicationHandler::getPreviousApplications();
		} else if ($user->hasPermission('chief.applications') && $user->isGroupMember()) {
			$group = $user->getGroup();
			$pendingApplicationList = ApplicationHandler::getPendingApplicationsByGroup($group);
			$queuedApplicationList = ApplicationHandler::getQueuedApplicationsByGroup($group);
			$previousApplicationList = ApplicationHandler::getPreviousApplicationsByGroup($group);
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
						echo '<td><a href="index.php?page=user-profile&id=' . $applicationUser->getId() . '">' . $applicationUser->getFullName() . '</a></td>';
						echo '<td>' . $application->getGroup()->getTitle() . '</td>';
						echo '<td>' . date('d.m.Y H:i', $application->getOpenedTime()) . '</td>';
						echo '<td>' . $application->getStateAsString() . '</td>';
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
						echo '<td><a href="index.php?page=user-profile&id=' . $applicationUser->getId() . '">' . $applicationUser->getFullName() . '</a></td>';
						echo '<td>' . $application->getGroup()->getTitle() . '</td>';
						echo '<td>' . date('d.m.Y H:i', $application->getOpenedTime()) . '</td>';
						echo '<td>' . $application->getStateAsString() . '</td>';
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

		if (!empty($previousApplicationList)) {
			echo '<table>';
				echo '<tr>';
					echo '<th>Arrangement</th>';
					echo '<th>Søker\'s navn</th>';
					echo '<th>Crew</th>';
					echo '<th>Søketidspunkt</th>';
					echo '<th>Svartidspunkt</th>';
					echo '<th>Status</th>';
				echo '</tr>';

				foreach ($previousApplicationList as $application) {
					$applicationUser = $application->getUser();

					echo '<tr>';
						echo '<td>' . $application->getEvent()->getTitle() . '</td>';
						echo '<td><a href="index.php?page=user-profile&id=' . $applicationUser->getId() . '">' . $applicationUser->getFullName() . '</a></td>';
						echo '<td>' . $application->getGroup()->getTitle() . '</td>';
						echo '<td>' . date('d.m.Y H:i', $application->getOpenedTime()) . '</td>';
						echo '<td>' . date('d.m.Y H:i', $application->getClosedTime()) . '</td>';
						echo '<td>' . $application->getStateAsString() . '</td>';
						echo '<td><input type="button" value="Vis" onClick="viewApplication(' . $application->getId() . ')"></td>';
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
?>
