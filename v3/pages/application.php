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
require_once 'interfaces/page.php';
require_once 'traits/page.php';

class ApplicationPage implements IPage {
	use Page;

	public function getTitle() {
		return 'Søknad';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if ($user->hasPermission('*') ||
				$user->hasPermission('chief.applications') ||
				$user->isGroupLeader()) {
				
				if (isset($_GET['id'])) {
					$application = ApplicationHandler::getApplication($_GET['id']);
					
					if ($application != null) {
						$applicationUser = $application->getUser();
						$content .= '<script src="scripts/application.js"></script>';

						$applicationList = ApplicationHandler::getUserApplications($applicationUser);

						if (!empty($applicationList)) {
							$content .= '<p>Denne brukeren har også levert søknad til følgende crew:</p>';
							$content .= '<ul>';
								foreach ($applicationList as $applicationValue) {
									if (!$application->equals($applicationValue)) {
										$group = $applicationValue->getGroup();
										
										$content .= '<li><a href="index.php?page=application&id=' . $applicationValue->getId() . '">' . $group->getTitle() . '</a></li>';
									}
								}
							$content .= '</ul>';
						}

						$content .= '<table>';
							$content .= '<tr>';
								$content .= '<td><b>Status:</b></td>';
								$content .= '<td>' . $application->getStateAsString() . '</td>';
							$content .= '</tr>';
						
							$content .= '<tr>';
								$content .= '<td><b>Søkers navn:</b></td>';
								$content .= '<td><a href="index.php?page=my-profile&id=' . $applicationUser->getId() . '">' . $applicationUser->getFullname(). '</a></td>';
							$content .= '</tr>';
							$content .= '<tr>';
								$content .= '<td><b>Dato søkt:</b></td>';
								$content .= '<td>' . date('d.m.Y H:i', $application->getOpenedTime()) . '</td>';
							$content .= '</tr>';
							$content .= '<tr>';
								$content .= '<td><b>Crew:</b></td>';
								$content .= '<td>' . $application->getGroup()->getTitle() . '</td>';
							$content .= '</tr>';
							$content .= '<tr>';
								$content .= '<td><b>E-post:</b></td>';
								$content .= '<td><a href="mailto:' . $applicationUser->getEmail() . '">' . $applicationUser->getEmail() . '</a></td>';
							$content .= '</tr>';
							$content .= '<tr>';
								$content .= '<td><b>Telefon:</b></td>';
								$content .= '<td>' . $applicationUser->getPhone() . '</td>';
							$content .= '</tr>';
							$content .= '<tr>';
								$content .= '<td><b>Alder:</b></td>';
								$content .= '<td>' . $applicationUser->getAge() . '</td>';
							$content .= '</tr>';
							$content .= '<tr>';
								$content .= '<td><b>Søknad:</b></td>';
								$content .= '<td>' . wordwrap($application->getContent(), 64, '<br>') . '</td>';
							$content .= '</tr>';
						$content .= '</table>';
						
						switch ($application->getState()) {
							case 1:
								$content .= '<form class="chief-applications-reject" method="post">';
									$content .= '<input type="hidden" name="id" value="' . $application->getId() . '">';
									$content .= '<textarea class="editor" name="comment" rows="10" cols="80" placeholder="Skriv hvorfor du vil avslå her."></textarea>';
									$content .= '<input type="submit" value="Avslå">';
								$content .= '</form>';
								$content .= '<input type="button" value="Godkjenn" onClick="acceptApplication(' . $application->getId() . ')">';
								
								if (!$application->isQueued()) {
									$content .= '<input type="button" value="Sett i kø" onClick="queueApplication(' . $application->getId() . ')">';
								} else {
									$content .= '<input type="button" value="Fjern fra kø" onClick="unqueueApplication(' . $application->getId() . ')">';
								}
								
								break;
								
							case 3:
								$content .= 'Begrunnelse for avslåelse: <i>' . $application->getComment() . '</i>';
								break;
						}
					} else {
						$content .= 'Den angitte søknaden finnes ikke.';
					}
				} else {
					$content .= 'Ingen søknad spesifisert.';
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