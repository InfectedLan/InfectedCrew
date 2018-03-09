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
require_once 'handlers/eventhandler.php';
require_once 'handlers/seatmaphandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('admin.seatmap')) {
		echo '<script src="scripts/seatmapEditor.js"></script>';

		if (isset($_GET['id']) &&
			is_numeric($_GET['id'])) {
			$seatmap = SeatmapHandler::getSeatmap($_GET['id']);

			if ($seatmap != null) {
				echo '<script>var seatmapId = ' . $seatmap->getId() . '; </script>';
				echo '<script>';
					echo '$(document).ready(function() {';
						echo 'renderSeatmap();';
						echo '$("#uploadBgForm").ajaxForm(function() {';
							echo 'location.reload();';
						echo '});';
					echo '});';
				echo '</script>';

				echo '<div id="seatmapEditorPanel">';
					echo '<h1>Endrer på seatmappet "' . $seatmap->getHumanName() . '"</h1>';
					//Fille uploader widget
					echo '<form id="uploadBgForm" action="../api/json/seatmap/uploadSeatmapBackground.php" method="post" enctype="multipart/form-data">';
						echo '<input type="file" id="uploadBgImage" name="bgImageFile" />';
		     			echo '<input type="submit" value="Last opp nytt bakgrunnsbilde" />';
		     			echo '<input type="hidden" name="seatmapId" value="' . $seatmap->getId() . '" />';
					echo '</form>';
					echo '<br />';
					echo '<br />';
					//Buttons
					echo '<input type="button" id="btnNewRow" value="Legg til rad på [0,0]" onclick="addRow()" /> | ';
					echo '<input type="button" id="btnSetCoords" value="Skriv inn kordinater selv" onclick="promptPosition()" /> | ';
					echo '<input type="button" id="btnInitCopy" value="Kopier fra et annet seatmap" onClick="initCopy()" />';
					echo '<div id="copySeatmapDiv" style="display: none;">';
					echo '<select id="copySeatmapSourceSelect">';
					$event = EventHandler::getCurrentEvent();

					foreach (SeatmapHandler::getSeatmaps() as $seatmapElement) {
					    if ($seatmapElement->equals($event->getSeatmap())) {
						echo '<option value="' . $seatmapElement->getId() . '" selected>' . $seatmapElement->getHumanName() . '</option>';
					    } else {
						echo '<option value="' . $seatmapElement->getId() . '">' . $seatmapElement->getHumanName() . '</option>';
					    }
					}
					echo '</select>';
					echo '<input type="button" id="btnCopy" value="Kopier!" onClick="copySeatmap()" />';
					echo '</div> | ';
					//echo '<input type="button" id="btnUploadImage" value="Last opp ny bakgrunn" onclick="uploadBackground()" /> | ';
					//Context sensitive buttons
					echo '<span id="seatmapEditorContextButtons">';

					echo '</span>';
					//Navigation buttons
					echo '<input type="button" value="Tilbake" onclick="redirectToSplash()" />';
					//Mouse pos indicator
					echo '<div id="mousePos">';
						echo '<i>Mus-posisjon: [0,0]. Klikk for å velge.</i>';
					echo '</div>';
				echo '</div>';
				echo '<div id="seatmapEditorCanvas">';
					echo '<i>Laster inn data...</i>';
				echo '</div>';
			} else {
				echo '<h1>Seatmappet eksisterer ikke!</h1>';
				echo '<input type="button" onclick="redirectToSplash()" value="Tilbake" />';
			}
		} else {
			echo '<center>';
				echo '<h1>Seatmap-editor</h1>';
				echo '<div id="seatmapIntro">';
					echo 'For å starte, må du velge et seatmap du vil redigere, eller lage et nytt.<br>';

					echo '<select id="seatmapSelect">';
						$event = EventHandler::getCurrentEvent();

						$currentMap = $event->getSeatmap();

						foreach (SeatmapHandler::getSeatmaps() as $seatmap) {
							if ($seatmap->equals($currentMap)) {
								echo '<option value="' . $seatmap->getId() . '" selected>' . $seatmap->getHumanName() . '</option>';
							} else {
								echo '<option value="' . $seatmap->getId() . '">' . $seatmap->getHumanName() . '</option>';
							}
						}
					echo '</select>';
					echo '<input type="button" value="Edit" onclick="editSeatmap()" />';
					echo '<input type="button" value="Lag kopi" onclick="cloneSeatmap()" />';
					echo '&nbsp;...eller...&nbsp;';
					echo '<input type="button" value="Lag nytt seatmap" onclick="newSeatmapName()" />';
				echo '</div>';
				echo '<div id="newSeatmapDiv" style="display: none;">';
					echo 'Hva skal seatmappet hete?&nbsp;';
					echo '<input type="text" id="newSeatmapName" />';
					echo '<input type="button" value="Lag nytt seatmap!" onclick="newSeatmap()" />';
					echo '<br /><br />';
					echo '<input type="button" value="Tilbake" onclick="backToMenuFromNewSeatmap()" />';
				echo '</div>';
			echo '</center>';
		}
	} else {
		echo 'Du har ikke rettigheter til dette!';
	}
} else {
	echo 'Du er ikke logget inn!';
}
?>
