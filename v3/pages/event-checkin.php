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
		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if ($user->hasPermission('*') ||
				$user->hasPermission('event.checkin')) {
				echo '<div class="row">';
					echo '<div class="col-md-4">';
						echo '<div class="box">';
							echo '<div class="box-header with-border">';
								echo '<h3 class="box-title">Sjekk inn en billett</h3>';
							echo '</div>';
							echo '<div class="box-body">';

								$event = EventHandler::getCurrentEvent();
								$season = date('m', $event->getStartTime()) == 2 ? 'Vinter' : 'Høst';
								$eventName = !empty($event->getTheme()) ? $event->getTheme() : $season . '_' . date('Y', $event->getStartTime());
								
								echo '<form class="navbar-form navbar-left">';
									echo '<div class="form-group">';
										echo '<label>' . Settings::name . '_' . $eventName . '_' . '</label>';
										echo '<div class="input-group">';
											echo '<input type="text" class="form-control" placeholder="Skriv inn billet id her...">';
											echo '<span class="input-group-btn">';
										  		echo '<button type="submit" class="btn btn-info btn-flat" onClick="loadData()">Sjekk inn</button>';
											echo '</span>';
									  	echo '</div>';
									echo '</div>';
								echo '</form>';
								echo '<div id="ticketDetails"></div>';
							echo '</div><!-- /.box-body -->';
						echo '</div><!-- /.box -->';
					echo '</div><!--/.col (left) -->';
				echo '</div><!-- /.row -->';

				echo '<script src="scripts/event-checkin.js"></script>';
			} else {
				echo '<div class="box">';
					echo '<div class="box-body">';
						echo '<p>Du har ikke rettigheter til dette!</p>';
					echo '</div><!-- /.box-body -->';
				echo '</div><!-- /.box -->';
			}
		} else {
			echo '<div class="box">';
				echo '<div class="box-body">';
					echo '<p>Du er ikke logget inn!</p>';
				echo '</div><!-- /.box-body -->';
			echo '</div><!-- /.box -->';
		}
	}
}
?>