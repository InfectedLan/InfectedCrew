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
require_once 'localization.php';
require_once 'handlers/userhandler.php';

$id = isset($_GET['id']) ? $_GET['id'] : Session::getCurrentUser()->getId();

if (Session::isAuthenticated()) {
  $user = Session::getCurrentUser();

  if ($user->hasPermission('user.note')) {
    $editUser = UserHandler::getUser($id);

    if ($editUser != null) {
      echo '<script src="scripts/edit-user-note.js"></script>';

			echo '<h3>Endrer notat på bruker ' . $editUser->getDisplayName() . '</h3>';

			echo '<table>';
				echo '<form class="edit-user-note" method="post">';
					echo '<input type="hidden" name="id" value="' . $editUser->getId() . '">';
					echo '<tr>';
						echo '<td>Notat:</td>';
            echo '<td><textarea name="content" rows="10" cols="80">' . ($editUser->hasNote() ? $editUser->getNote() : null) . '</textarea></td>';
					echo '</tr>';
          echo '<tr>';
            echo '<td><input type="submit" value="' . Localization::getLocale('save') . '"></td>';
          echo '</tr>';
        echo '</form>';
			echo '</table>';
    } else {
      echo '<p>Den angitte brukeren finnes ikke.</p>';
    }
  } else {
    echo '<p>Du har ikke rettigheter til dette.</p>';
  }
} else {
  echo '<p>Du er ikke logget inn.</p>';
}
?>
