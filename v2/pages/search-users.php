<?php
require_once 'session.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('search.users')) {
		echo '<script src="scripts/search-users.js"></script>';
		echo '<h3>Søk etter bruker</h3>';
		
		echo '<input class="search" type="text" placeholder="Skriv for å søke..." autocomplete="off" autofocus>';
		echo '<ul class="search-results"></ul>';
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>