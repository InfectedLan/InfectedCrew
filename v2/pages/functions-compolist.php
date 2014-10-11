<?php
require_once 'session.php';
require_once 'handlers/eventhandler.php';
require_once 'handlers/compohandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('functions.compoadmin')) {
		$event = 0;
		if(isset($_GET['id'])) {
			$event = EventHandler::getEvent($_GET['id']);
		} else {
			$event = EventHandler::getCurrentEvent();
		}

		//print_r($event);

		$compos = CompoHandler::getComposForEvent($event);

		echo '<h1>Compoer for ' . $event->getTheme() . '</h1>';

		foreach($compos as $compo) {
			echo '<a href="index.php?page=functions-compo&id=' . $compo->getId() . '">' . $compo->getName() . '</a><br />';
		}

	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>