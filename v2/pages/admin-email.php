<?php
require_once 'session.php';
require_once 'handlers/userhandler.php';


if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('admin.email')) {
		
		echo '<script src="scripts/admin-email.js"></script>';
		echo '<h3>E-poster:</h3>';
		echo '<p>Her er en liste over Infected arrangementer som har vært eller skal være. Neste arrangement blir automatisk vist på hovedsiden.</p>';

		echo '<table>';
			echo '<form class="admin-email-send" method="post">';
				echo '<tr>';
					echo '<td>Mottakere:</td>';
					echo '<td>';
						echo '<select multiple class="chosen-select select" name="userIdList" data-placeholder="Velg mottakere...">';
							echo '<option value="0">Alle</option>';
							echo '<option value="-1">Alle med billett</option>';
							
							foreach (UserHandler::getUsers() as $userValue) {
								echo '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
							}
						echo '</select>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Emne:</td>';
					echo '<td><input type="text" name="subject" required></td>';
				echo '</tr>';	
				echo '<tr>';
					echo '<td>Melding:</td>';
					echo '<td><textarea name="message" class="editor" rows="10" cols="80"></textarea></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><input type="submit" value="Send"></td>';
				echo '</tr>';
			echo '</form>';
		echo '</table>';
	} else {
		echo 'Du har ikke rettigheter til dette!';
	}
} else {
	echo 'Du er ikke logget inn!';
}
?>