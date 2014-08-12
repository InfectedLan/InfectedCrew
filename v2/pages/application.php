<?php
require_once 'session.php';
require_once 'handlers/grouphandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	echo '<h1>Crew-søknad</h1>';
	
	if (!$user->isGroupMember()) {
		if ($user->hasAvatar()) {
			echo '<script src="scripts/application.js"></script>';
			
			echo '<p>Velkommen! Som crew vil du oppleve ting du aldri ville opplevd som deltager, få erfaring du kan bruke på CV-en din, og møte mange nye og spennende folk. Dersom dette er første gangen du søker som crew på infected, annbefaler vi at du leser igjennom crewbeskrivelsene. Disse finer du <a href="index.php?page=crewene">her</a>. Klar til å søke? Fyll ut skjemaet under:</p>';
			echo '<table>';
				echo '<form class="application" action="" method="post">';
					echo '<tr>';
						echo '<td>Crew:</td>';
						echo '<td>';
							echo '<select name="groupId">';
								$groupList = GroupHandler::getGroups();
								
								foreach ($groupList as $group) {
									echo '<option value="' . $group->getId() . '">' . $group->getTitle() . '</option>';
								}
							echo '</select>';
						echo '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Tekst:</td>';
						echo '<td><textarea rows="10" cols="100" name="content" placeholder="Skriv en kort opsummering av hvorfor du vil søke, på under 512 tegn, her."></textarea></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td></td>';
						echo '<td><input type="submit" value="Søk!"></td>';
					echo '</tr>';
				echo '</form>';
			echo '</table>';
		} else {
			echo '<h3>Du er nødt til å laste opp et profilbilde for å søke. Dette gjør du <a href="index.php?page=edit-avatar">her.</a>';
		}
	} else {
		$group = $user->getGroup();
		
		echo 'Du er allerede med i <a href="index.php?page=crew&id=' . $group->getId() . '">' . $group->getTitle() . '</a> crew!<br>';
		echo '<a href="index.php">Tilbake</a>';
	}
} else {
	echo 'Du må være logget inn for å søke!<br>';
	echo '<a href="index.php">Tilbake</a>';
}
?>