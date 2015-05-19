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

require_once 'event.php';
require_once 'session.php';
require_once 'settings.php';
require_once 'handlers/eventhandler.php';
require_once 'interfaces/page.php';

class EventSeatmapPage extends EventPage implements IPage {
	public function getTitle() {
		return 'Setekart';
	}

	public function getContent() {
		$seatmapId = isset($_GET['id']) ? $_GET['id'] : EventHandler::getCurrentEvent()->getSeatmap()->getId();

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if ($user->hasPermission('*') ||
				$user->hasPermission('event.seatmap')) {

				echo '<link rel="stylesheet" href="../api/styles/seatmap.css">';
			
				echo '<h1>Seatmap for årets arrangement</h1>';

				echo '<div id="seatmapCanvas"></div>';
				echo '<script src="../api/scripts/seatmapRenderer.js"></script>';

				echo '<script>';
					echo 'var seatmapId = ' . $seatmapId . ';';
					echo '$(document).ready(function() {';
						echo 'downloadAndRenderSeatmap("#seatmapCanvas");';
					echo '});';
				echo '</script>';
			} else {
				echo '<p>Du har ikke rettigheter til dette!</p>';
			}
		} else {
			echo '<p>Du er ikke logget inn!</p>';
		}
	}
}
?>