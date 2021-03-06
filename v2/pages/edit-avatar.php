<?php
/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2015 Infected <http://infected.no/>.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3.0 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'session.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	// TODO: Sjekk om det er noen som har et uncropped bilde.
	echo '<script>';
		echo 'function deleteAvatar() {';
			echo '$.getJSON(\'../api/json/avatar/removeAvatar.php\', function(data) {';
				echo 'if (data.result) {';
					echo 'location.reload()';
				echo '} else { ';
					echo 'error(data.message);';
				echo '}';
			echo '});';
		echo '}';
	echo '</script>';

	if ($user->hasAvatar()) {
		$avatar = $user->getAvatar();

		switch ($avatar->getState()) {
			case 0:
				echo '<link rel="stylesheet" href="../api/libraries/jcrop/css/jquery.Jcrop.css">';
				echo '<script src="../api/libraries/jcrop/js/jquery.Jcrop.js"></script>';
				echo '<script>';
					echo '$(document).ready(function() {';
						echo 'var options = {';
							echo 'success: function(responseText, statusText, xhr, $form) {';
								echo 'var data = responseText;';
								echo 'if (data.result) {';
									echo 'location.reload();';
								echo '} else {';
									echo 'error(data.message);';
								echo '}';
							echo '}';
						echo '};';
						echo '$("#cropform").ajaxForm(options);';
					echo '});';
				echo '</script>';


				echo '<script>';
					echo '$(document).ready(function() {';
						echo '$(\'#cropCanvas\').Jcrop({';
							//Calculate size factor. The crop pane is 800 wide.
							$temp = explode('.', $avatar->getTemp());
							$extension = strtolower(end($temp));
							$image = 0;

							if ($extension == 'png') {
								$image = imagecreatefrompng(Settings::api_path . $avatar->getTemp());
							} else if ($extension == 'jpeg' ||
								$extension == 'jpg') {
								$image = imagecreatefromjpeg(Settings::api_path . $avatar->getTemp());
							} else {
								// TODO: Handle if the format is not supported here...
							}

							$scaleFactor = 800 / imagesx($image);
                            echo 'setSelect:   [ 0, 0, 800, ' . ( $scaleFactor * imagesy($image))  . ' ],';
							echo 'aspectRatio: 400/300,';
							echo 'minSize: [' . (Settings::avatar_minimum_width * $scaleFactor) . ', ' . (Settings::avatar_minimum_height * $scaleFactor) . '],';
							echo 'onSelect: updateCoords';
						echo '});';
					echo '});';

					echo 'var base_image = new Image();';
					echo 'base_image.src=\'../api/' . $avatar->getTemp() . '\';';
					echo 'base_image.onload = function() {';
					echo 'var canvas = document.getElementById(\'cropCanvas\').getContext(\'2d\'); ';
					echo 'canvas.drawImage(base_image, 0, 0, 800, ' . (imagesy($image) * $scaleFactor) . ');';
					echo '};';

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

			echo '<table class="wizard-table">';
            echo '<tr>';
            echo '<td class="wizard-next">';
            echo '<b>Steg 1:</b>';
            echo '<p>Last opp et bilde</p>';
            echo '</td>';
            echo '<td class="wizard-current">';
            echo '<b>Steg 2:</b>';
            echo '<p>Beskjær bildet og lagre</p>';
            echo '</td>';
            echo '<td class="wizard-nostyle">';
            echo '<b>Steg 3:</b>';
            echo '<p>Ferdig!</p>';
            echo '</td>';
            echo '</tr>';
            echo '</table>';
	    echo '<canvas width="800" height="' . (imagesy($image) * $scaleFactor) . '" id="cropCanvas"></canvas>';
	    //echo '<img src="' . $avatar->getTemp() . '" id="cropbox"  width="800">';
			echo '<form action="../api/json/avatar/cropAvatar.php" method="get" id="cropform" onsubmit="return checkCoords();">';
				echo '<input type="hidden" id="x" name="x">';
				echo '<input type="hidden" id="y" name="y">';
				echo '<input type="hidden" id="w" name="w">';
				echo '<input type="hidden" id="h" name="h">';
				echo '<input type="submit" value="Lagre">';
			echo '</form><br>';
			echo '<i>Er du ikke fornøyd? <input type="button" value="Slett bilde" onClick="deleteAvatar()">';
				break;

			case 1:
                echo '<table class="wizard-table">';
                echo '<tr>';
                echo '<td class="wizard-other">';
                echo '<b>Steg 1:</b>';
                echo '<p>Last opp et bilde</p>';
                echo '</td>';
                echo '<td class="wizard-next">';
                echo '<b>Steg 2:</b>';
                echo '<p>Beskjær bildet og lagre</p>';
                echo '</td>';
                echo '<td class="wizard-fill">';
                echo '<b>Steg 3:</b>';
                echo '<p>Ferdig!</p>';
                echo '</td>';
                echo '</tr>';
                echo '</table>';
				echo '<h1>Ditt bilde venter på godkjenning</h1>';
				echo '<img src="../api/' . $avatar->getHd() . '" width="800">';
				echo '<br>Ikke fornøyd? <input type="button" value="Slett bilde" onClick="deleteAvatar()">';
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
            	echo '$("#file").change(function() {';
                	echo '$("#uploadForm").submit();';
                    echo '$("#uploadForm").fadeOut();';
                echo '});';
				echo 'var options = {';
					echo 'success: function(responseText, statusText, xhr, $form) {';
						echo 'var data = responseText;';
						echo 'if (data.result) {';
							echo 'location.reload();';
						echo '} else {';
							echo 'error(data.message);';
                            echo '$("#uploadForm").fadeIn();';
						echo '}';
					echo '}';
				echo '};';
				echo '$("#uploadForm").ajaxForm(options);';
			echo '});';
		echo '</script>';
        echo '<table class="wizard-table">';
        echo '<tr>';
        echo '<td class="wizard-current">';
        echo '<b>Steg 1:</b>';
        echo '<p>Last opp et bilde</p>';
        echo '</td>';
        echo '<td class="wizard-other">';
        echo '<b>Steg 2:</b>';
        echo '<p>Beskjær bildet og lagre</p>';
        echo '</td>';
        echo '<td class="wizard-nostyle">';
        echo '<b>Steg 3:</b>';
        echo '<p>Ferdig!</p>';
        echo '</td>';
        echo '</tr>';
        echo '</table>';
		echo '<b>Last opp profilbilde: </b>';
		echo '<form action="../api/json/avatar/uploadAvatar.php" method="post" id="uploadForm" enctype="multipart/form-data">';
			echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';
			echo '<label for="file">Filnavn:</label>';
			echo '<input type="file" name="file" id="file">';
		echo '</form>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>
