<?php
require_once 'session.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	$avatar = $user->getAvatar();
	
	// Sjekk om det er noen som har et uncropped bilde.
	$state = $avatar->getState();
	
	if (!$user->hasAvatar()) {
		echo '<h1>Endre avatar</h1>';
		echo '<h3>Nåværende avatar:</h3>';
		
		echo '<img src="' . $avatar->getFile() . '" width="50%" height="50%">';
		
		/* if (User::getAvatar($_SESSION["username"]) == "default.png") {
			echo '<i>Du er nødt til å laste opp en avatar for å søke!</i><br />';
		} */
		
		echo '<b>Nytt profilbilde: </b>';
		echo '<form action="do/doAvatar.php" method="post" enctype="multipart/form-data"><input type="hidden" name="MAX_FILE_SIZE" value="7000000" /><label for="file">Filnavn:</label><input type="file" name="file" id="file"><br><input type="submit" name="submit" value="Last opp!"></form><br />';
	} else if ($state == 2) {
		echo '<script src="api/jcrop/js/jquery.min.js"></script>';
		echo '<script src="api/jcrop/js/jquery.Jcrop.js"></script>';
		echo '<link rel="stylesheet" href="api/jcrop/css/jquery.Jcrop.css" type="text/css">';
		echo '<script type="text/javascript">';
			echo '$(function(){';
				echo '$(\'#cropbox\').Jcrop({';
					echo 'aspectRatio: 400/300,';
					echo 'onSelect: updateCoords';
				echo '});';
			echo '});';

			echo 'function updateCoords(c)';
			echo '{';
				echo '$(\'#x\').val(c.x);';
				echo '$(\'#y\').val(c.y);';
				echo '$(\'#w\').val(c.w);';
				echo '$(\'#h\').val(c.h);';
			echo '};';

			echo 'function checkCoords()';
			echo '{';
				echo 'if (parseInt($(\'#w\').val())) return true;';
				echo 'alert(\'Please select a crop region then press submit.\');';
				echo 'return false;';
			echo '};';
		echo '</script>';

		echo '<h1>Beskjær bilde</h1>';
		echo '<img width="800" src="' . $avatar->getFile() . '" id="cropbox">';
		echo '<form action="do/doAvatar.php" method="post" onsubmit="return checkCoords();">';
			echo '<input type="hidden" id="x" name="x">';
			echo '<input type="hidden" id="y" name="y">';
			echo '<input type="hidden" id="w" name="w">';
			echo '<input type="hidden" id="h" name="h">';
			echo '<input type="submit" value="Beskjær">';
		echo '</form><br />';
		echo '<i>Er du ikke fornøyd? <a href="do/doAvatar.php?delete=' . $avatar->getId() . '">Slett bilde</i>';
	} else if ($state == 1) {
		echo '<h1>Ditt bilde venter på godkjenning</h1>';
		echo '<img src="' . $avatar->getFile() . '">';
		echo 'Ikke fornøyd? <a href="do/doAvatar.php?delete=' . $avatar->getId() . '" />Slett!</a>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>