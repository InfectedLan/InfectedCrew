<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/handlers/avatarhandler.php';

if (Utils::isAuthenticated()) {
	$user = Utils::getUser();
	
	if ($user->hasPermission('chief.avatars') ||
		$user->hasPermission('admin')) {
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
					echo '<img src="' . $avatarUser->getPendingAvatar() . '" width="400"/>';
					echo '<a href="scripts/process_avatar.php?action=3&id=' . $value->getId() . '">Godkjenn</a>';
					echo '<a href="scripts/process_avatar.php?action=4&id=' . $value->getId() . '">Avslå</a>';
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