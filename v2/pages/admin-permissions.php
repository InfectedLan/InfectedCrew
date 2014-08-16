<?php
require_once 'session.php';
require_once 'handlers/userhandler.php';
require_once 'handlers/permissionshandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('admin.permissions')) {
		echo '<script src="scripts/admin-permissions.js"></script>';
		echo '<h1>Tilganger</h1>';
		
		echo '<h3>Gi en tilgang til en bruker</h3>';
		echo '<form class="admin-permissions-add" method="post">';
			echo '<table>';
				echo '<tr>';
					echo '<td>Bruker:</td>';
					echo '<td>';
						echo '<select name="userId">';
							foreach (UserHandler::getUsers() as $userValue) {
								echo '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
							}
						echo '</select>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Tilgang:</td>';
					echo '<td>';
						echo '<select class="admin-permissions-add-value" name="value">';
							foreach (PermissionsHandler::getPermissions() as $permission) {
								echo '<option value="' . $permission->getValue() . '">' . $permission->getValue() . '</option>';
							}
						echo '</select>';
					echo '</td>';
					echo '<td><p class="admin-permissions-add-description"></p></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><input type="submit" value="Legg til"></td>';
				echo '</tr>';
			echo '</table>';
		echo '</form>';
		
		echo '<h3>Allerede tildelete tilganger</h3>';
		
		$userList = UserHandler::getPermissionUsers();
		
		if (!empty($userList)) {
			echo '<table>';
				echo '<tr>';
					echo '<th>Bruker</th>';
					echo '<th>Tilgang</th>';
				echo '</tr>';
				
				foreach ($userList as $userValue) {
					$first = true;
				
					foreach ($userValue->getPermissions() as $permission) {
						echo '<tr>';
							echo '<td>';
								if ($first) {
									$first = false;
									echo $userValue->getDisplayName();
								}
							echo '</td>';
							echo '<td>' . $permission . '</td>';
							echo '<td><input type="button" value="Fjern" onClick="removeUserPermission(' . $userValue->getId() . ', \'' . $permission . '\')"></td>';
						echo '</tr>';
					}
				}
			echo '</table>';
		} else {
			echo '<p>Det finnes ingen brukere med tilganger enda.</p>';
		}
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>