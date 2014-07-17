<?php
echo '<form action="scripts/process_user.php?action=6&returnPage=index.php" method="post">';
	echo '<h3>Glemt passord</h3>';
	echo '<table>';
		echo '<tr>';
			echo '<td>E-post:</td>';
			echo '<td><input type="email" name="email"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><input type="submit" value="Send e-post!"></td>';
		echo '</tr>';
	echo '</table>';
echo '</form>';
?>