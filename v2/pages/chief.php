<?php
require_once 'utils.php';

if (Utils::isAuthenticated()) {
	$user = Utils::getUser();
	
	if ($user->hasPermission('chief') ||
		$user->isGroupChief() ||
		$user->hasPermission('admin') ||
		$user->hasPermission('crew-admin')) {
		echo '<h1>Chief</h1>';
		
		echo '<p>Du finner alle funksjonene øverst i menyen til høyre for Infected logoen.';
	} else {
		echo 'Du har ikke rettigheter til dette!';
	}
} else {
	echo 'Du er ikke logget inn!';
}
?>