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
require_once 'handlers/userhandler.php';

$id = isset($_GET['id']) ? $_GET['id'] : Session::getCurrentUser()->getId();

if (Session::isAuthenticated()) {
  $user = Session::getCurrentUser();

  if ($user->hasPermission('user.relocate')) {
    $editUser = UserHandler::getUser($id);

    if ($editUser != null) {
      if ($editUser->hasTicket()) {
        $ticket = $editUser->getTicket();

        echo '<link rel="stylesheet" href="../api/styles/seatmap.css">';
        echo '<script src="../api/scripts/seatmapRenderer.js"></script>';
        echo '<script src="scripts/edit-user-location.js"></script>';

        echo '<h3>Endrer plasseringen til ' . $editUser->getDisplayName() . '</h3>';
        echo '<div id="seatmapCanvas"></div>';
        echo '<script>';
          echo 'var seatmapId = ' . $ticket->getEvent()->getSeatmap()->getId() . ';';
          echo 'var ticketId = ' . $ticket->getId() . ';';
          echo '$(document).ready(function() {';
            echo 'downloadAndRenderSeatmap("#seatmapCanvas", seatHandlerFunction, callback);';
          echo '});';
        echo '</script>';
      } else {
        echo '<p>Brukeren du prøver å flytte har ingen gyldig billett for dette arrangementet.</p>';
      }
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
