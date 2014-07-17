<?php
require_once 'scripts/utils.php';

$utils = new Utils();

if ($utils->isAuthenticated()) {
	$user = $utils->getUser();
	
	if ($user->hasPermission('functions') || 
		$user->isGroupChief() || 
		$user->hasPermission('admin') || 
		$user->hasPermission('crew-admin')) {
		echo '<h1>Functions</h1>';
		
		echo '<p>Du finner alle funksjonene øverst i menyen til høyre for Infected logoen.';
	} else {
		echo 'Du har ikke rettigheter til dette!';
	}
} else {
	echo 'Du er ikke logget inn!';
}
?>