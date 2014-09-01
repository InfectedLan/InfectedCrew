<?php
require_once 'session.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	$avatar = $user->getAvatar();
	
	// TODO: Sjekk om det er noen som har et uncropped bilde.
	echo '<script>';
		echo 'function deleteAvatar() {';
			echo '$.getJSON(\'../api/json/deleteavatar.php\', function(data) {';
				echo 'if(data.result) {';
					echo 'location.reload()';
				echo '} else { ';
					echo 'error(data.message);';
				echo '}';
			echo '});';
		echo '}';
	echo '</script>';
	if (isset($avatar)) {
		switch ($avatar->getState()) {
			case 0:
				echo '<script>';
					echo '$(document).ready(function() {';
						echo '$("#cropform").ajaxForm(function() {';
							echo 'location.reload();';
						echo '});';
					echo '});';
				echo '</script>';
				echo '<script src="../api/scripts/jcrop/js/jquery.Jcrop.js"></script>';
				echo '<link rel="stylesheet" href="../api/scripts/jcrop/css/jquery.Jcrop.css">';
				echo '<script type="text/javascript">';
					echo '$(function() {';
						echo '$(\'#cropbox\').Jcrop({';
							echo 'aspectRatio: 400/300,';
							echo 'onSelect: updateCoords';
						echo '});';
					echo '});';

					echo 'function updateCoords(c) {';
						echo '$(\'#x\').val(c.x);';
						echo '$(\'#y\').val(c.y);';
						echo '$(\'#w\').val(c.w);';
						echo '$(\'#h\').val(c.h);';
					echo '};';

					echo 'function checkCoords() {';
						echo 'if (parseInt($(\'#w\').val())) return true;';
						echo 'alert(\'Please select a crop region then press submit.\');';
						echo 'return false;';
					echo '};';
				echo '</script>';

			echo '<h1>Beskjær bilde</h1>';
			echo '<img src="../api/' . $avatar->getTemp() . '" id="cropbox"  width="800">';
			echo '<form action="../api/json/cropavatar.php" method="get" id="cropform" onsubmit="return checkCoords();">';
				echo '<input type="hidden" id="x" name="x">';
				echo '<input type="hidden" id="y" name="y">';
				echo '<input type="hidden" id="w" name="w">';
				echo '<input type="hidden" id="h" name="h">';
				echo '<input type="submit" value="Beskjær">';
			echo '</form><br>';
			echo '<i>Er du ikke fornøyd? <input type="button" value="Slett bilde" onClick="deleteAvatar()">';
				break;
			
			case 1:
				echo '<h1>Ditt bilde venter på godkjenning</h1>';
				echo '<img src="../api/' . $avatar->getHd() . '" width="800">';
				echo '<br />Ikke fornøyd? <input type="button" value="Slett bilde" onClick="deleteAvatar()">';
				break;
				
			case 2:
				echo '<h1>Nåværende avatar:</h1>';
				echo '<img src="../api/' . $avatar->getHd() . '" width="800">';
				echo '<br>';
				echo 'Ikke fornøyd? <input type="button" value="Slett bilde" onClick="deleteAvatar()">';
				break;
				
			default:
				echo '<b>Avataren din er ikke godkjent!</b>';
				echo '<br>';
				echo '<input type="button" value="Slett og start på nytt" onClick="deleteAvatar()">';
				break;
		}
	} else {
		echo '<script>';
			echo '$(document).ready(function() {';
				echo '$("#uploadForm").ajaxForm(function() {';
					echo 'location.reload();';
				echo '});';
			echo '});';
		echo '</script>';
		echo '<b>Last opp profilbilde: </b>';
		echo '<form action="../api/json/uploadAvatar.php" method="post" id="uploadForm" enctype="multipart/form-data">';
			echo '<input type="hidden" name="MAX_FILE_SIZE" value="7000000" />';
			echo '<label for="file">Filnavn:</label>';
			echo '<input type="file" name="file" id="file">';
			echo '<br>';
			echo '<input type="submit" name="submit" value="Last opp!">';
		echo '</form>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>