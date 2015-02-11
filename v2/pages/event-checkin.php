<?php
require_once 'session.php';
require_once 'handlers/eventhandler.php';
require_once 'settings.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('event.checkin')) {
		echo '<script src="scripts/event-checkin.js"></script>';
		echo '<h3>Sjekk inn billett</h3>';

		$event = EventHandler::getCurrentEvent();
		$season = date('m', $event->getStartTime()) == 2 ? 'VINTER' : 'HØST';
		$eventName = !empty($event->getTheme()) ? $event->getTheme() : $season . date('Y', $event->getStartTime());
		
		echo Settings::name . '_' . $eventName . '_' . '<input id="ticketId" type="text" autofocus>';
		echo '<br>';
		echo '<input type="button" value="Sjekk inn" onClick="loadData()"/>';
		echo '<br>';
		echo '<div id="ticketDetails"></div>';
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>