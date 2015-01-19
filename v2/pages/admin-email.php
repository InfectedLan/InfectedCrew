<?php
require_once 'session.php';
require_once 'handlers/userhandler.php';


if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('admin.email') ||
		$user->hasPermission('admin.emails')) {
		
		echo '<script src="scripts/admin-email.js"></script>';
		echo '<h3>E-poster:</h3>';
		echo '<p>Her er en liste over Infected arrangementer som har vært eller skal være. Neste arrangement blir automatisk vist på hovedsiden.</p>';
		
		// TODO: Add some way for the user to select which users to send the email to.
		
		echo '<table>';
			echo '<form class="admin-email-send" method="post">';
				echo '<tr>';
					echo '<td>Emne:</td>';
					echo '<td>';
						echo '<select multiple class="chosen-select select" name="userIdList" data-placeholder="Velg en chief...">';
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
					echo '<td><input type="submit" value="Endre"></td>';
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