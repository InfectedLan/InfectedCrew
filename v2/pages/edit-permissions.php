<?php
require_once 'session.php';
require_once 'handlers/userhandler.php';
require_once 'handlers/permissionshandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('admin.permissions')) {
	
		$permissionUser = isset($_GET['id']) ? UserHandler::getUser($_GET['id']) : $user;

		if ($permissionUser != null) {
			echo '<script src="scripts/edit-permissions.js"></script>';
			echo '<h3>Du endrer nå "' . $permissionUser->getFullName() . '" sine rettigheter</h3>';
			
			echo '<form class="edit-permissions" method="post">';
				echo '<input type="hidden" name="id" value="' . $permissionUser->getId() . '">';
				echo '<table>';
					foreach (PermissionsHandler::getPermissions() as $permission) {
						echo '<tr>';
							echo '<td>';
								if (in_array($permission->getValue(), $permissionUser->getPermissions())) {
									echo '<input type="checkbox" name="checkbox_' . $permission->getId() . '" value="' . $permission->getValue() . '" checked>' . $permission->getValue();
								} else {
									echo '<input type="checkbox" name="checkbox_' . $permission->getId() . '" value="' . $permission->getValue() . '">' . $permission->getValue();
								}
							echo '</td>';
							echo '<td>' . wordwrap($permission->getDescription(), 100, '<br>') . '</td>';
						echo '</tr>';
					}
					
					echo '<tr>';
						echo '<td><input type="submit" value="Lagre"></td>';
					echo '</tr>';
				echo '</table>';
			echo '</form>';
		} else {
			echo '<p>Brukeren finnes ikke.</p>';
		}
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>