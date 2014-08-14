<?php
require_once 'session.php';
require_once 'handlers/userhandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('admin') ||
		$user->hasPermission('admin-permissions')) {
		echo '<script src="scripts/admin-permissions.js"></script>';
		echo '<h1>Rettigheter</h1>';
		
		echo '<h3>Gi en bruker tilgang til en funksjon</h3>';
		echo '<form class="admin-permissions-add" method="post">';
			echo '<table>';
				echo '<tr>';
					echo '<td>Brukernavn:</td>';
					echo '<td><input type="text" name="username"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Tilgang:</td>';
					echo '<td>';
						echo '<select name="value">';
							$permissionList = array('admin', 
													'functions-find-user');
						
							foreach ($permissionList as $permission) {
								echo '<option value="' . $permission . '">' . $permission . '</option>';
							}
						echo '</select>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><input type="submit" value="Legg til"></td>';
				echo '</tr>';
			echo '</table>';
		echo '</form>';

		$userList = UserHandler::getPermissionUsers();
		
		if (!empty($userList)) {
			echo '<table>';
				echo '<tr>';
					echo '<th>Bruker</th>';
					echo '<th>Tilgang</th>';
				echo '</tr>';
				
				foreach ($userList as $userValue) {
					echo '<tr>';
						echo '<td>' . $userValue->getUsername() . '</td>';
						
						foreach ($userValue->getPermissions() as $permission) {
							echo '<td>' . $permission . '</td>';
						}
					echo '</tr>';
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