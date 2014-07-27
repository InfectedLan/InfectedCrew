<?php
require_once 'session.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('functions') || 
		$user->isGroupLeader() || 
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