<?php
require_once 'scripts/utils.php';

$utils = new Utils();

if ($utils->isAuthenticated()) {
	$user = $utils->getUser();
	
	if ($user->hasPermission('admin') ||
		$user->hasPermission('site-admin') ||
		$user->hasPermission('crew-admin')) {
		echo '<h3>Admin</h3>';
		echo '<p>Du finner alle funksjonene øverst i menyen til høyre for Infected logoen.';
	}
}
?>