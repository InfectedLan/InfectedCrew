<?php
require_once 'session.php';
require_once 'handlers/avatarhandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('chief.avatars') ||
		$user->isGroupLeader()) {
		echo '<script src="scripts/chief-avatars.js"></script>';
		echo '<h3>Godkjenn profilbilder</h3>';
		
		$pendingAvatarList = AvatarHandler::getPendingAvatars();
		
		if (!empty($pendingAvatarList)) {
			$index = 0;
		
			foreach ($pendingAvatarList as $avatar) {
				$avatarUser = $avatar->getUser();
			
				echo '<div class="';
					if ($index % 2 == 0) {
						echo 'avatarLeft';
					} else {
						echo 'avatarRight';
					}
				echo '">';
					echo '<p>' . $avatarUser->getDisplayName() . '</p>';
					echo '<img src="../api/' . $avatarUser->getAvatar()->getSd() . '" width="300" height="200">';
					echo '<table>';
						echo '<tr>';
							echo '<td><input type="button" value="Godta" onClick="acceptAvatar(' . $avatar->getId() . ')"></td>';
							echo '<td><input type="button" value="Avslå" onClick="rejectAvatar(' . $avatar->getId() . ')"></td>';
						echo '</tr>';
					echo '</table>';
				echo '</div>';
					
				$index++;
			}
		} else {
			echo '<p>Det er ingen profilbilder som trenger godkjenning akkurat nå.</p>';
		}
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>