<?php
require_once 'session.php';
require_once 'handlers/eventhandler.php';
require_once 'handlers/compohandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('functions.compoadmin')) {

		if(isset($_GET['id'])) {
			$compo = CompoHandler::getCompo($_GET['id']);

			echo '<script src="scripts/functions-compo.js"></script>';
			echo '<script>var compoId = ' . $compo->getId() . ';</script>';

			echo '<div id="teamListArea"></div>';
		} else {
			echo '<p>Mangler felt!</p>';
		}

	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>