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

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('chief.applications') ||
		$user->isGroupLeader()) {
		
		if (isset($_GET['id'])) {
			$application = ApplicationHandler::getApplication($_GET['id']);
			
			if ($application != null) {
				$applicationUser = $application->getUser();

				echo '<script src="scripts/application.js"></script>';
				echo '<h3>Søknad</h3>';

				$applicationList = ApplicationHandler::getUserApplications($applicationUser);

				if (!empty($applicationList)) {
					echo '<p>Denne brukeren har også søkt til disse crewene:</p>';

					echo '<table>';
						foreach ($applicationList as $value) {
							if (!$application->equals($value)) {
								$group = $value->getGroup();

								echo '<tr>';
									echo '<td>' . $group->getTitle() . '</td>';
								echo '</tr>';
							}
						}
					echo '</table>';
				}

				// TODO: Add information about the user here.

				echo '<table>';
					echo '<tr>';
						echo '<td>Status:</td>';
						echo '<td>' . $application->getStateAsString() . '</td>';
					echo '</tr>';
				
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
						echo '<td><a href="mailto:' . $applicationUser->getEmail() . '">' . $applicationUser->getEmail() . '</a></td>';
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
?>