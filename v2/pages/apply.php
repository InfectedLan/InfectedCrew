<?php
require_once 'utils.php';

if (Utils::isAuthenticated()) {
	$user = Utils::getUser();
	
	echo '<h1>Crew-søknad</h1>';
	
	if (!$user->isGroupMember()) {
		if ($user->hasAvatar()) {
			echo '<p>Velkommen! Som crew vil du oppleve ting du aldri ville opplevd som deltager, få erfaring du kan bruke på CV-en din, og møte mange nye og spennende folk. Dersom dette er første gangen du søker som crew på infected, annbefaler vi at du leser igjennom crewbeskrivelsene. Disse finer du <a href="index.php?page=crewene">her</a>. Klar til å søke? Fyll ut skjemaet under:</p>';
			
			echo '<form action="do/index.php?sokCrew=../index.php?page=sok" method="post">';
				echo '<b>Crew:</b>';
				echo '<select name="group">';
					$groupList = $this->database->getGroups();
					
					foreach ($groupList as $group) {
						echo '<option value="' . $group->getId() . '">' . $group->getTitle() . '</option>';
					}
				echo '</select>';

				echo '<b>Hvorfor vil du søke dette crewet?</b>';
				echo '<textarea rows="10" cols="100" name="content" placeholder="Skriv en kort opsummering av hvorfor du vil søke, på under 512 tegn, her."></textarea><br />';
				echo '<i>Maximum 512 tegn</i><br /><br />';
				echo '<input type="submit" value="Søk!" />';
			echo '</form>';
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