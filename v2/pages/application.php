<?php
require_once 'session.php';
require_once 'settings.php';
require_once 'handlers/grouphandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	echo '<h1>Søk deg inn i crew</h1>';
	
	if (!$user->isGroupMember()) {
		if ($user->hasAvatar()) {
			echo '<script src="scripts/application.js"></script>';

			echo '<p>Velkommen! Som crew vil du oppleve ting du aldri ville som deltaker, få erfaringer du kan bruke sette på din CV-en, <br>
				  og møte mange nye og spennende mennesker. Dersom det er første gang du skal søke til crew på ' . Settings::name . ', <br>
				  anbefaler vi at du leser igjennom beskrivelsene av våre ' . count(GroupHandler::getGroups()) . ' forksjellige crew. Disse finner du <a href="index.php?page=crewene">her</a>.</p>
				  <p>Klar til å søke? Fyll ut skjemaet under:</p>';
			echo '<table>';
				echo '<form class="application" method="post">';
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
						echo '<td>';
							echo '<textarea id="editor1" name="content" placeholder="Skriv en kort oppsummering av hvorfor du vil søke her."></textarea>';
							echo '<script>';
								// Replace the <textarea id="editor1"> with a CKEditor
								// instance, using default configuration.
								echo 'CKEDITOR.replace(\'editor1\');';
							echo '</script>';
						echo '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td><input type="submit" value="Søk"></td>';
					echo '</tr>';
				echo '</form>';
			echo '</table>';
		} else {
			echo '<h3>Du er nødt til å laste opp et profilbilde for å søke. Dette gjør du <a href="index.php?page=edit-avatar">her.</a>';
		}
	} else {
		$group = $user->getGroup();
		
		echo '<p>Du er allerede med i <a href="index.php?page=crew&id=' . $group->getId() . '">' . $group->getTitle() . '</a>!<br>';
		echo '<a href="index.php">Tilbake</a></p>';
	}
} else {
	echo '<p>Du må være logget inn for å søke!<br>';
	echo '<a href="index.php">Tilbake</a></p>';
}
?>