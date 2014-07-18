<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/Utils.php';

if (Utils::isAuthenticated()) {
	$user = Utils::getUser();
	
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