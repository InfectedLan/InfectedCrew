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
require_once 'handlers/eventhandler.php';

$id = isset($_GET['id']) ? $_GET['id'] : Session::getCurrentUser()->getId();

if (Session::isAuthenticated()) {
  $user = Session::getCurrentUser();
	$relocateUser = UserHandler::getUser($id);

	if ($relocateUser != null) {
    if (($user->hasPermission('*') ||
      $user->hasPermission('search.users') ||
      $user->hasPermission('chief.tickets')) && // TODO: Verify this permission.
      $relocateUser->hasTicket()) {
      $ticket = $relocateUser->getTicket();
      echo '<link rel="stylesheet" href="../api/styles/seatmap.css">';
      echo '<script src="../api/scripts/seatmapRenderer.js"></script>';

      echo '<h3>Endrer plasseringen til ' . $relocateUser->getDisplayName() . '</h3>';
      echo '<div id="seatmapCanvas"></div>';
      echo '<script>';
        echo 'var seatmapId = ' . $ticket->getEvent()->getSeatmap()->getId() . ';'; // TODO: Fix this, somehow event here is null...
        echo 'var ticketId = ' . $ticket->getId() . ';';
        echo '$(document).ready(function() {';
          echo 'downloadAndRenderSeatmap("#seatmapCanvas", seatHandlerFunction, callback);';
        echo '});';
      echo '</script>';
    }
  }
}
?>
