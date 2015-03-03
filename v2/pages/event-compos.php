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
require_once 'handlers/eventhandler.php';
require_once 'handlers/compohandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('event.compoadmin')) {
		$event = 0;
		
		if(isset($_GET['id'])) {
			$event = EventHandler::getEvent($_GET['id']);
		} else {
			$event = EventHandler::getCurrentEvent();
		}

		$compos = CompoHandler::getComposByEvent($event);

		echo '<h1>Compoer for ' . $event->getTheme() . '</h1>';

		foreach ($compos as $compo) {
			echo '<a href="index.php?page=event-compo&id=' . $compo->getId() . '">' . $compo->getName() . '</a><br />';
		}
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>