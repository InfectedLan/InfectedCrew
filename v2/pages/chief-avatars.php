<?php
require_once 'session.php';
require_once 'handlers/avatarhandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('chief.avatars') ||
		$user->isGroupLeader()) {
		echo '<h3>Godkjenn profilbilder</h3>';
		
		$pendingAvatarList = AvatarHandler::getPendingAvatars();
		
		if (!empty($pendingAvatarList)) {
			$index = 0;
		
			foreach ($pendingAvatarList as $value) {
				$avatarUser = $value->getUser();
			
				echo '<div class="';
					if ($index % 2 == 0) {
						echo 'avatarLeft';
					} else {
						echo 'avatarRight';
					}
				echo '">';
					echo '<p>' . $avatarUser->getFirstname() . ' ' . $avatarUser->getLastname() . '</p>';
					echo '<img src="' . $avatarUser->getPendingAvatar()->getFile() . '" width="400">';
					echo '<table>';
						echo '<tr>';
							echo '<td><a href="scripts/process_avatar.php?action=3&id=' . $value->getId() . '">Godkjenn</a></td>';
							echo '<td><a href="scripts/process_avatar.php?action=4&id=' . $value->getId() . '">Avslå</a></td>';
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