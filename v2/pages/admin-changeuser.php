<?php
require_once 'session.php';

$username = isset($_POST['username']) ? $_POST['username'] : 0;

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('admin')) {
		echo '<h1>Bytt bruker</h1>';
		echo '<p>Dette er en admin-funksjon som lar deg være logget inn som en annen bruker. <br>';
		echo 'Dette er en funksjon som ikke skal misbrukes, og må kun brukes i debug eller feilsøkings-sammenheng.</p>';
		
		echo '<form name="input" action="index.php?page=admin-changeuser" method="post">';
			echo '<table>';
				echo '<tr>';
					echo '<td>Brukernavn:</td>';
					echo '<td><input type="text" name="username"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><input type="submit" value="Bytt bruker"></td>';
				echo '</tr>';
			echo '</table>';
		echo '</form>';
	
		if (isset($_POST['username'])) {
			$changeUser = UserHandler::getCurrentUserByName($username);
			
			if ($changeUser != null) {
				$_SESSION['user'] = $changeUser;
			} else {
				echo '<p>Brukeren du prøvde å bytte til eksisterer ikke.</p>';
			}
		}
	} else {
		echo 'Du har ikke rettigheter til dette.';
	}
} else {
	echo 'Du er ikke logget inn.';
}
?>