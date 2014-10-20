<?php
require_once 'session.php';
require_once 'handlers/userhandler.php';

$username = isset($_POST['username']) ? $_POST['username'] : 0;

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('admin.console')) {
		echo '<script src="scripts/admin-api.js"></script>';
		echo '<b>Api-command: </b><select class="search" id="apiName" placeholder="Skriv api-navn her"></select>';
		echo '<br />';
		echo '<div id="commandArgBox"></div>';
		echo '<div id="resultBox"><i>Results kommer her</i></div>';
	} else {
		echo 'Du har ikke rettigheter til dette.';
	}
} else {
	echo 'Du er ikke logget inn.';
}
?>