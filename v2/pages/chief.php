<?php
require_once 'session.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('chief')) {
		echo '<h3>Chief</h3>';
		
		echo '<p>Du finner alle funksjonene øverst i menyen til høyre for Infected logoen.';
	} else {
		echo 'Du har ikke rettigheter til dette!';
	}
} else {
	echo 'Du er ikke logget inn!';
}
?>