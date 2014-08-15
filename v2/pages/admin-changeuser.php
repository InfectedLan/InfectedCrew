<?php
require_once 'session.php';
require_once 'handlers/userhandler.php';

$username = isset($_POST['username']) ? $_POST['username'] : 0;

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('admin') ||
		$user->hasPermission('admin.changeuser')) {
		echo '<script src="scripts/admin-changeuser.js"></script>';
		echo '<h1>Bytt bruker</h1>';
		echo '<p>Dette er en admin-funksjon som lar deg være logget inn som en annen bruker. <br>';
		echo 'Dette er en funksjon som ikke skal misbrukes, og må kun brukes i debug eller feilsøkings-sammenheng.</p>';
		
		echo '<form class="admin-changeuser" name="input" method="post">';
			echo '<table>';
				echo '<tr>';
					echo '<td>Brukernavn:</td>';
					echo '<td>';
						echo '<select name="userId">';
							$userList = UserHandler::getUsers();
						
							foreach ($userList as $user) {
								echo '<option value="' . $user->getId() . '">' . $user->getDisplayName() . '</option>';
							}
						echo '</select>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><input type="submit" value="Bytt bruker"></td>';
				echo '</tr>';
			echo '</table>';
		echo '</form>';
	} else {
		echo 'Du har ikke rettigheter til dette.';
	}
} else {
	echo 'Du er ikke logget inn.';
}
?>