<?php
require_once 'session.php';

if (!Session::isAuthenticated()) {
	echo '<script src="scripts/reset-password.php"></script>';

	if (!isset($_GET['code'])) {
		echo '<h2>Glemt passord?</h2>';
		echo '<form class="request-reset-password" name="input" action="" method="post">';
			echo '<p>Skriv inn ditt brukernavnet eller din e-postadresse for å nullstille passordet ditt: <input type="text" name="username"></p>';
			echo '<input type="submit" value="Nullstill passord">';
		echo '</form>';
	} else {
		echo '<h2>Nullstill passord</h2>';
		echo '<p>Skriv inn et nytt passord.</p>';
		
		echo '<form class="reset-password" name="input" action="" method="post">';
			echo '<table>';
				echo '<tr>';
					echo '<td>Nytt passord:</td>';
					echo '<td><input type="password" name="password"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Bekreft passord:</td>';
					echo '<td><input type="password" name="confirmpassword"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><input type="submit" value="Endre"></td>';
				echo '</tr>';
			echo '</table>';
		echo '</form>';
	}
} else {
	echo 'Siden du er logget inn, ser det ut til at du husker passordet ditt.';
}
?>