<?php
require_once 'session.php';
require_once 'handlers/userhandler.php';
require_once 'handlers/permissionshandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('admin.permissions')) {
		echo '<script src="scripts/admin-permissions.js"></script>';
		
		if (isset($_GET['id'])) {
			$permissionUser = UserHandler::getUser($_GET['id']);

			if ($permissionUser != null) {
				echo '<h3>Du endrer nå "' . $permissionUser->getFullName() . '" sine rettigheter</h3>';
				
				echo '<form class="admin-permissions-edit" method="post">';
					echo '<input type="hidden" name="id" value="' . $permissionUser->getId() . '">';
					echo '<table>';
						foreach (PermissionsHandler::getPermissions() as $permission) {
							if ($user->hasPermission('*') ||
								$user->hasPermission($permission->getValue())) {
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
						if ($userValue != null) {
							echo '<tr>';
								echo '<td>' . $userValue->getDisplayName() . '</td>';;
								echo '<td>' . count($userValue->getPermissions()) . '</td>';
								echo '<td><input type="button" value="Endre" onClick="editUserPermissions(' . $userValue->getId() . ')"></td>';
							echo '</tr>';
						}
					}
				echo '</table>';
			} else {
				echo '<p>Det finnes ingen brukere med rettigheter.</p>';
			}
		}
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>