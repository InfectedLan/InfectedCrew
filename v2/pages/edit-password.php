<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils.php';

if (Utils::isAuthenticated()) {
	echo '<h3>Endre passord</h3>';
	
	echo '<form action="do/index.php?editpass=crew/v2/index.php" method="post">';
		echo '<table>';
			echo '<tr>';
				echo '<td>Gammelt passord:</td>';
				echo '<td><input type="password" name="oldPassword"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Nytt passord:</td>';
				echo '<td><input type="password" name="newPassword"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Gjenta nytt passord:</td>';
				echo '<td><input type="password" name="confirmNewPassword"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td><input type="submit" value="Lagre"></td>';
			echo '</tr>';
		echo '</table>';
	echo '</form>';
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>