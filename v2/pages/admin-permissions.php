<?php
require_once 'session.php';
require_once 'handlers/userhandler.php';
require_once 'handlers/permissionshandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('admin.permissions')) {
		echo '<script src="scripts/admin-permissions.js"></script>';
		echo '<h3>Rettigheter</h3>';
		echo '<p>Under ser du en liste med alle brukere som har spesielle rettigheter.</p>';
		
		$userList = UserHandler::getPermissionUsers();
		
		if (!empty($userList)) {
			echo '<table>';
				echo '<tr>';
					echo '<th>Navn</th>';
					echo '<th>Antall tilganger</th>';
				echo '</tr>';
				
				foreach ($userList as $userValue) {
					echo '<tr>';
						echo '<td>' . $userValue->getDisplayName() . '</td>';;
						echo '<td>' . count($userValue->getPermissions()) . '</td>';
						echo '<td><input type="button" value="Endre" onClick="editUserPermissions(' . $userValue->getId() . ')"></td>';
					echo '</tr>';
				}
			echo '</table>';
		} else {
			echo '<p>Det finnes ingen brukere med rettigheter.</p>';
		}
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>