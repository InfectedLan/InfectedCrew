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
require_once 'interfaces/page.php';
require_once 'traits/page.php';

class EditAvatarPage implements IPage {
	use Page;

	public function getTitle() {
		return 'Endre avatar';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			// TODO: Sjekk om det er noen som har et uncropped bilde.
			$content .= '<script>';
				$content .= 'function deleteAvatar() {';
					$content .= '$.getJSON(\'../api/json/avatar/deleteAvatar.php\', function(data) {';
						$content .= 'if (data.result) {';
							$content .= 'location.reload()';
						$content .= '} else { ';
							$content .= 'error(data.message);';
						$content .= '}';
					$content .= '});';
				$content .= '}';
			$content .= '</script>';
			
			if ($user->hasAvatar()) {
				$avatar = $user->getAvatar();
				
				switch ($avatar->getState()) {
					case 0:
						$content .= '<script>';
							$content .= '$(document).ready(function() {';
								$content .= 'var options = {';
									$content .= 'success: function(responseText, statusText, xhr, $form) {';
										$content .= 'var data = jQuery.parseJSON(responseText);';
										$content .= 'if (data.result) {';
											$content .= 'location.reload();';
										$content .= '} else {';
											$content .= 'error(data.message);';
										$content .= '}';
									$content .= '}';
								$content .= '};';
								$content .= '$("#cropform").ajaxForm(options);';
							$content .= '});';
						$content .= '</script>';
						$content .= '<script src="../api/libraries/jcrop/js/jquery.Jcrop.js"></script>';
						$content .= '<link rel="stylesheet" href="../api/libraries/jcrop/css/jquery.Jcrop.css">';
						$content .= '<script>';
							$content .= '$(function() {';
								$content .= '$(\'#cropbox\').Jcrop({';
									//Calculate size factor. The crop pane is 800 wide.
									$temp = explode('.', $avatar->getTemp());
									$extension = strtolower(end($temp));
									$image = 0;
									
									if ($extension == 'png') {
										$image = imagecreatefrompng(Settings::api_path . $avatar->getTemp());
									} else if ($extension == 'jpeg' || 
											   $extension == 'jpg') {
										$image = imagecreatefromjpeg(Settings::api_path . $avatar->getTemp());
									}

									$scaleFactor = 800 / imagesx($image);
									$content .= 'aspectRatio: 400/300,';
									$content .= 'minSize: [' . (Settings::avatar_minimum_width * $scaleFactor) . ', ' . (Settings::avatar_minimum_height * $scaleFactor) . '],';
									$content .= 'onSelect: updateCoords';
								$content .= '});';
							$content .= '});';

							$content .= 'function updateCoords(c) {';
								$content .= '$(\'#x\').val(c.x);';
								$content .= '$(\'#y\').val(c.y);';
								$content .= '$(\'#w\').val(c.w);';
								$content .= '$(\'#h\').val(c.h);';
							$content .= '};';

							$content .= 'function checkCoords() {';
								$content .= 'if (parseInt($(\'#w\').val())) return true;';
								$content .= 'alert(\'Please select a crop region then press submit.\');';
								$content .= 'return false;';
							$content .= '};';
						$content .= '</script>';

					$content .= '<h1>Beskjær bilde</h1>';
					$content .= '<img src="../api/' . $avatar->getTemp() . '" id="cropbox"  width="800">';
					$content .= '<form action="../api/json/avatar/cropAvatar.php" method="get" id="cropform" onsubmit="return checkCoords();">';
						$content .= '<input type="hidden" id="x" name="x">';
						$content .= '<input type="hidden" id="y" name="y">';
						$content .= '<input type="hidden" id="w" name="w">';
						$content .= '<input type="hidden" id="h" name="h">';
						$content .= '<input type="submit" value="Lagre">';
					$content .= '</form><br>';
					$content .= '<i>Er du ikke fornøyd? <input type="button" value="Slett bilde" onClick="deleteAvatar()">';
						break;
					
					case 1:
						$content .= '<h1>Ditt bilde venter på godkjenning</h1>';
						$content .= '<img src="../api/' . $avatar->getHd() . '" width="800">';
						$content .= '<br>Ikke fornøyd? <input type="button" value="Slett bilde" onClick="deleteAvatar()">';
						break;
						
					case 2:
						$content .= '<h1>Nåværende avatar:</h1>';
						$content .= '<img src="../api/' . $avatar->getHd() . '" width="800">';
						$content .= '<br>';
						$content .= 'Ikke fornøyd? <input type="button" value="Slett bilde" onClick="deleteAvatar()">';
						break;
						
					default:
						$content .= '<b>Avataren din er ikke godkjent!</b>';
						$content .= '<br>';
						$content .= '<input type="button" value="Slett og start på nytt" onClick="deleteAvatar()">';
						break;
				}
			} else {
				$content .= '<script>';
					$content .= '$(document).ready(function() {';
						$content .= 'var options = {';
							$content .= 'success: function(responseText, statusText, xhr, $form) {';
								$content .= 'var data = jQuery.parseJSON(responseText);';
								$content .= 'if (data.result) {';
									$content .= 'location.reload();';
								$content .= '} else {';
									$content .= 'error(data.message);';
								$content .= '}';
							$content .= '}';
						$content .= '};';
						$content .= '$("#uploadForm").ajaxForm(options);';
					$content .= '});';
				$content .= '</script>';
				$content .= '<b>Last opp profilbilde: </b>';
				$content .= '<form action="../api/json/avatar/uploadAvatar.php" method="post" id="uploadForm" enctype="multipart/form-data">';
					$content .= '<input type="hidden" name="MAX_FILE_SIZE" value="7340032" />';
					$content .= '<label for="file">Filnavn:</label>';
					$content .= '<input type="file" name="file" id="file">';
					$content .= '<br>';
					$content .= '<input type="submit" name="submit" value="Last opp!">';
				$content .= '</form>';
			}
		} else {
			$content .= '<p>Du er ikke logget inn!</p>';
		}

		return $content;
	}
}
?>