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
require_once 'handlers/pagehandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('admin.website')) {
		if (isset($_GET['id']) &&
			is_numeric($_GET['id'])) {
			$page = PageHandler::getPage($_GET['id']);
				
			if ($page != null) {
				echo '<script src="scripts/edit-page.js"></script>';
				echo '<h3>Du endrer nå siden "' . $page->getTitle() . '"</h3>';
				
				echo '<form class="edit-page" method="post">';
					echo '<input type="hidden" name="id" value="' . $page->getId() . '">';
					echo '<table>';
						echo '<tr>';
							echo '<td>Tittel:</td>';
							echo '<td><input type="text" name="title" value="' . $page->getTitle() . '"> (Dette blir også navnet på linken til siden).</td>';
						echo '</tr>';
					echo '</table>';
					echo '<textarea name="content" rows="10" cols="80">' . $page->getContent() . '</textarea>';
					echo '<input type="submit" value="Endre">';
				echo '</form>';
			} else {
				echo '<p>Siden finnes ikke.</p>';
			}
		}
	}
}
?>