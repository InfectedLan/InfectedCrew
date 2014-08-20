<?php
require_once 'session.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('functions.search-users') ||
		$user->isGroupLeader()) {
		echo '<script type="text/javascript" src="scripts/functions-search-users.js"></script>';
		echo '<h1>Søk etter bruker</h1>';
		echo '<input class="search" type="text" placeholder="Skriv for å søke..." autocomplete="off" autofocus>';
		echo '<ul class="search-results"></ul>';
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>