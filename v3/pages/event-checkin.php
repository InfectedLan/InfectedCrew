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
require_once 'settings.php';
require_once 'handlers/eventhandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('event.checkin')) {
		echo '<script src="scripts/event-checkin.js"></script>';

		echo '<div class="box">';
			echo '<div class="box-header with-border">';
				echo '<h3 class="box-title">Sjekk inn billett</h3>';
			echo '</div>';
			echo '<div class="box-body">';

				$event = EventHandler::getCurrentEvent();
				$season = date('m', $event->getStartTime()) == 2 ? 'Vinter' : 'Høst';
				$eventName = !empty($event->getTheme()) ? $event->getTheme() : $season . '_' . date('Y', $event->getStartTime());
				
				echo '<form class="navbar-form navbar-left">';
					echo '<div class="form-group">';
				    	echo '<label>' . Settings::name . '_' . $eventName . '_' . '</label>';
				    	echo '<input type="text" class="form-control" placeholder="Skriv inn billet id her...">';
				  	echo '</div>';
				  	echo '<button type="submit" class="btn btn-primary" onClick="loadData()">Sjekk inn</button>';
				echo '</form>';
				echo '<div id="ticketDetails"></div>';
			echo '</div><!-- /.box-body -->';
		echo '</div><!-- /.box -->';
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>