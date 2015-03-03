/*
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

<?php
require_once 'session.php';
require_once 'handlers/restrictedpagehandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('chief.my-crew')) {
		if (isset($_GET['id']) &&
			is_numeric($_GET['id'])) {
			$page = RestrictedPageHandler::getPage($_GET['id']);
			
			if ($page != null) {
				if ($user->hasPermission('*') ||
					$user->hasPermission('chief.my-crew') &&
					$user->getGroup()->equals($page->getGroup())) {
					echo '<script src="scripts/edit-restricted-page.js"></script>';
					echo '<h3>Du endrer nå siden "' . $page->getTitle() . '"</h3>';
				
					echo '<form class="edit-restricted-page-edit" method="post">';
						echo '<input type="hidden" name="id" value="' . $page->getId() . '">';
						echo '<table>';
							echo '<tr>';
								echo '<td>Navn:</td>';
								echo '<td><input type="text" name="title" value="' . $page->getTitle() . '"> (Dette blir navnet på siden).</td>';
							echo '</tr>';
							
							if ($user->getGroup()->equals($page->getGroup())) {
								$group = $user->getGroup();
								
								echo '<tr>';
									echo '<td>Tilgang:</td>';
									echo '<td>';
										echo '<select class="chosen-select select" name="teamId">';	
											echo '<option value="0">Alle</option>';
											
											foreach ($group->getTeams() as $team) {
												if ($team->equals($page->getTeam())) {
													echo '<option value="' . $team->getId() . '" selected>' . $team->getTitle() . '</option>';
												} else {
													echo '<option value="' . $team->getId() . '">' . $team->getTitle() . '</option>';
												}
											}
										echo '</select>';
									echo '</td>';
								echo '</tr>';
							}
							
							echo '<tr>';
								echo '<td>Innhold:</td>';
								echo '<td>';
									echo '<textarea class="editor" name="content" rows="10" cols="80">' . $page->getContent() . '</textarea>';
								echo '</td>';
							echo '</tr>';
						echo '</table>';
						echo '<input type="submit" value="Endre">';
					echo '</form>';
				} else {
					$message = 'Du har ikke rettighet er til dette.';
				}
			} else {
				echo '<p>Siden finnes ikke.</p>';
			}
		}
	}
}
?>