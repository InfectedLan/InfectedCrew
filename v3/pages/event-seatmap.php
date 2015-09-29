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
		return 'Setekart for årets arrangement';
	}

	public function getContent() {
		$content = null;
		$id = isset($_GET['id']) ? $_GET['id'] : EventHandler::getCurrentEvent()->getSeatmap()->getId();

		$content .= '<div class="box">';
			$content .= '<div class="box-body">';

				if (Session::isAuthenticated()) {
					$user = Session::getCurrentUser();

					if ($user->hasPermission('*') ||
						$user->hasPermission('event.seatmap')) {
						$content .= '<link rel="stylesheet" href="../api/styles/seatmap.css">';

						$content .= '<div id="seatmapCanvas"></div>';

						$content .= '<script src="../api/scripts/seatmapRenderer.js"></script>';
						$content .= '<script>';
							$content .= 'var seatmapId = ' . $id . ';';
							$content .= '$(document).ready(function() {';
								$content .= 'downloadAndRenderSeatmap("#seatmapCanvas");';
							$content .= '});';
						$content .= '</script>';
					} else {
						$content .= '<p>Du har ikke rettigheter til dette!</p>';
					}
				} else {
					$content .= '<p>Du er ikke logget inn!</p>';
				}

			$content .= '</div><!-- /.box-body -->';
		$content .= '</div><!-- /.box -->';

		return $content;
	}
}
?>
