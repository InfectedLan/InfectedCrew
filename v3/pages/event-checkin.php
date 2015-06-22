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

class EventCheckInPage extends EventPage implements IPage {
	public function getTitle() {
		return 'Innsjekk';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if ($user->hasPermission('*') ||
				$user->hasPermission('event.checkin')) {
				$content .= '<div class="row">';
					$content .= '<div class="col-md-4">';
						$content .= '<div class="box">';
							$content .= '<div class="box-header with-border">';
								$content .= '<h3 class="box-title">Sjekk inn en billett</h3>';
							$content .= '</div>';
							$content .= '<div class="box-body">';

								$event = EventHandler::getCurrentEvent();
								$season = date('m', $event->getStartTime()) == 2 ? 'Vinter' : 'Høst';
								$eventName = !empty($event->getTheme()) ? $event->getTheme() : $season . '_' . date('Y', $event->getStartTime());
								
								$content .= '<form class="navbar-form navbar-left">';
									$content .= '<div class="form-group">';
										$content .= '<label>' . Settings::name . '_' . $eventName . '_' . '</label>';
										$content .= '<div class="input-group">';
											$content .= '<input type="text" class="form-control" placeholder="Skriv inn billet id her...">';
											$content .= '<span class="input-group-btn">';
										  		$content .= '<button type="submit" class="btn btn-primary btn-flat" onClick="loadData()">Sjekk inn</button>';
											$content .= '</span>';
									  	$content .= '</div>';
									$content .= '</div>';
								$content .= '</form>';
								$content .= '<div id="ticketDetails"></div>';
							$content .= '</div><!-- /.box-body -->';
						$content .= '</div><!-- /.box -->';
					$content .= '</div><!--/.col (left) -->';
				$content .= '</div><!-- /.row -->';

				$content .= '<script src="scripts/event-checkin.js"></script>';
			} else {
				$content .= '<div class="box">';
					$content .= '<div class="box-body">';
						$content .= '<p>Du har ikke rettigheter til dette!</p>';
					$content .= '</div><!-- /.box-body -->';
				$content .= '</div><!-- /.box -->';
			}
		} else {
			$content .= '<div class="box">';
				$content .= '<div class="box-body">';
					$content .= '<p>Du er ikke logget inn!</p>';
				$content .= '</div><!-- /.box-body -->';
			$content .= '</div><!-- /.box -->';
		}

		return $content;
	}
}
?>