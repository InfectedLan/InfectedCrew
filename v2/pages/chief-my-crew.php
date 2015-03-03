<?php
/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2015 Infected <http://infected.no/>.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'session.php';
require_once 'handlers/restrictedpagehandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->isGroupMember()) {
		$group = $user->getGroup();
		
		if ($user->hasPermission('*') || 
			$user->hasPermission('chief.my-crew')) {
			echo '<script src="scripts/chief-my-crew.js"></script>';
			echo '<h3>Mine sider</h3>';
			
			$pageList = RestrictedPageHandler::getAllPagesForGroup($group);
			
			if (!empty($pageList)) {
				echo '<table>';
					echo '<tr>';
						echo '<th>Navn</th>';
						echo '<th>Tilgang</th>';
					echo '</tr>';
				
					// Loop through the pages.
					foreach ($pageList as $page) {
						$team = $page->getTeam();
						
						echo '<tr>';
							echo '<td><a href="index.php?page=' . $page->getName() . '">' . $page->getTitle() . '</a></td>';
							echo '<td>' . ($team != null ? $team->getTitle() : 'Alle') . '</td>';
							echo '<td><input type="button" value="Endre" onClick="editPage(' . $page->getId() . ')"></td>';
							echo '<td><input type="button" value="Slett" onClick="removePage(' . $page->getId() . ')"></td>';
						echo '</tr>';
					}
				echo '</table>';
			} else {
				echo '<p>Det er ikke opprettet noen sider enda, du kan legge til en ny side under.</p>';
			}
			
			echo '<h3>Legg til ny side:</h3>';
			echo '<p>Fyll ut feltene under for å legge til en ny side.</p>';
			echo '<form class="chief-my-crew-add" method="post">';
				echo '<input type="hidden" name="groupId" value="' . $group->getId() . '">';
				echo '<table>';
					echo '<tr>';
						echo '<td>Navn:</td>';
						echo '<td><input type="text" name="title"> (Dette blir navnet på siden).</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Tilgang:</td>';
						echo '<td>';
							echo '<select class="chosen-select select" name="teamId">';	
								echo '<option value="0">Alle</option>';
								
								foreach ($group->getTeams() as $team) {
									echo '<option value="' . $team->getId() . '">' . $team->getTitle() . '</option>';
								}
							echo '</select>';
						echo '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Innhold:</td>';
						echo '<td>';
							echo '<textarea class="editor" name="content" rows="10" cols="80"></textarea>';
						echo '</td>';
					echo '</tr>';
				echo '</table>';
				echo '<input type="submit" value="Legg til">';
			echo '</form>';
		} else {
			echo '<p>Du har ikke rettigheter til dette!</p>';
		}
	} else {
		echo '<p>Du er ikke medlem av en gruppe.</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>