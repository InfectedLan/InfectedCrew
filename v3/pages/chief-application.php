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

require_once 'chief.php';
require_once 'session.php';
require_once 'handlers/applicationhandler.php';
require_once 'handlers/eventhandler.php';
require_once 'interfaces/page.php';

class ChiefApplicationsPage extends ChiefPage implements IPage {
	public function getTitle() {
		return 'Søknader';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if ($user->hasPermission('*') ||
				$user->hasPermission('chief.applications')) {
				$pendingApplicationList = null;
				$queuedApplicationList = null;
				$acceptedApplicationList = null;
					
				if ($user->hasPermission('*')) {
					$pendingApplicationList = ApplicationHandler::getPendingApplications();
					$queuedApplicationList = ApplicationHandler::getQueuedApplications();
					$acceptedApplicationList = ApplicationHandler::getAcceptedApplications();
				} else if ($user->hasPermission('chief.applications') && 
						   $user->isGroupMember()) {
					$group = $user->getGroup();
					$pendingApplicationList = ApplicationHandler::getPendingApplicationsForGroup($group);
					$queuedApplicationList = ApplicationHandler::getQueuedApplicationsForGroup($group);
					$acceptedApplicationList = ApplicationHandler::getAcceptedApplicationsForGroup($group);
				}
				
				$content .= '<script src="scripts/chief-applications.js"></script>';
				
				$content .= '<h3>Åpne søknader:</h3>';
				
				if (!empty($pendingApplicationList)) {
					$content .= '<table>';
						$content .= '<tr>';
							$content .= '<th>Søker\'s navn</th>';
							$content .= '<th>Crew</th>';
							$content .= '<th>Dato søkt</th>';
							$content .= '<th>Status</th>';
						$content .= '</tr>';
						
						foreach ($pendingApplicationList as $application) {
							$applicationUser = $application->getUser();
							
							$content .= '<tr>';
								$content .= '<td><a href="index.php?page=my-profile&id=' . $applicationUser->getId() . '">' . $applicationUser->getFullName() . '</a></td>';
								$content .= '<td>' . $application->getGroup()->getTitle() . '</td>';
								$content .= '<td>' . date('d.m.Y H:i', $application->getOpenedTime()) . '</td>';
								$content .= '<td>' . $application->getStateAsString() . '</td>';
								$content .= '<td><input type="button" value="Vis" onClick="viewApplication(' . $application->getId() . ')"></td>';
								$content .= '<td><input type="button" value="Sett i kø" onClick="queueApplication(' . $application->getId() . ')"></td>';
							$content .= '</tr>';
						}
					$content .= '</table>';
				} else {
					$content .= '<p>Det er ingen søknader som venter på godkjenning.</p>';
				}
				
				$content .= '<h3>Søknader i kø:</h3>';
				
				if (!empty($queuedApplicationList)) {
					$content .= '<table>';
						$content .= '<tr>';
							$content .= '<th>Plass</th>';
							$content .= '<th>Søker\'s navn</th>';
							$content .= '<th>Crew</th>';
							$content .= '<th>Dato søkt</th>';
							$content .= '<th>Status</th>';
						$content .= '</tr>';
						
						$index = 1;
						
						foreach ($queuedApplicationList as $application) {
							$applicationUser = $application->getUser();
						
							$content .= '<tr>';
								$content .= '<td>' . $index . '</td>';
								$content .= '<td><a href="index.php?page=my-profile&id=' . $applicationUser->getId() . '">' . $applicationUser->getFullName() . '</a></td>';
								$content .= '<td>' . $application->getGroup()->getTitle() . '</td>';
								$content .= '<td>' . date('d.m.Y H:i', $application->getOpenedTime()) . '</td>';
								$content .= '<td>' . $application->getStateAsString() . '</td>';
								$content .= '<td><input type="button" value="Vis" onClick="viewApplication(' . $application->getId() . ')"></td>';
								$content .= '<td><input type="button" value="Fjern fra kø" onClick="unqueueApplication(' . $application->getId() . ')"></td>';
							$content .= '</tr>';
							
							$index++;
						}
					$content .= '</table>';
				} else {
					$content .= '<p>Det er ingen søknader i køen.</p>';
				}
				
				$content .= '<h3>Tidligere søknader:</h3>';
				
				if (!empty($acceptedApplicationList)) {
					$content .= '<table>';
						$content .= '<tr>';
							$content .= '<th>Arrangement</th>';
							$content .= '<th>Søker\'s navn</th>';
							$content .= '<th>Crew</th>';
							$content .= '<th>Dato søkt</th>';
						$content .= '</tr>';
						
						foreach ($acceptedApplicationList as $application) {
							$applicationUser = $application->getUser();
							
							$content .= '<tr>';
								$content .= '<td>' . $application->getEvent()->getTitle() . '</td>';
								$content .= '<td><a href="index.php?page=my-profile&id=' . $applicationUser->getId() . '">' . $applicationUser->getFullName() . '</a></td>';
								$content .= '<td>' . $application->getGroup()->getTitle() . '</td>';
								$content .= '<td>' . date('d.m.Y H:i', $application->getOpenedTime()) . '</td>';
								$content .= '<td><input type="button" value="Vis" onClick="viewApplication(' . $application->getId() . ')"></td>';
							$content .= '</tr>';
						}
					$content .= '</table>';
				} else {
					$content .= '<p>Det er ingen godkjente søknader i arkivet.</p>';
				}
			} else {
				$content .= 'Bare crew ledere kan se søknader.';
			}
		} else {
			$content .= 'Du er ikke logget inn!';
		}

		return $content;
	}
}
?>