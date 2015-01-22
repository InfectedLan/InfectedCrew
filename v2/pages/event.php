<?php
require_once 'session.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('event')) {
		echo '<h3>Event</h3>';
		echo '<p>Du finner alle funksjonene øverst i menyen til høyre for Infected logoen.</p>';
	} else {
		echo '<p>Du har ikke tilgang til dette.</p>';
	}
}
?>