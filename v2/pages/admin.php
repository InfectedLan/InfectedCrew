<?php
require_once 'session.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('admin') ||
		$user->hasPermission('site-admin') ||
		$user->hasPermission('crew-admin')) {
		echo '<h3>Admin</h3>';
		echo '<p>Du finner alle funksjonene øverst i menyen til høyre for Infected logoen.';
	} else {
		echo '<p>Du har ikke tilgang til dette.';
	}
}
?>