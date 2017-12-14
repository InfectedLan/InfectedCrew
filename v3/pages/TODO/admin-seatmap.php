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

require_once 'admin.php';
require_once 'session.php';
require_once 'handlers/seatmaphandler.php';
require_once 'interfaces/page.php';

class AdminSeatmapPage extends AdminPage implements IPage {
	public function getTitle() {
		if (isset($_GET['id'])) {
			$seatmap = SeatmapHandler::getSeatmap($_GET["id"]);

			if ($seatmap != null) {
				return 'Endrer på seatmappet "' . $seatmap->getHumanName() . '"';
			}
		} else {
			return 'Setekart editor';
		}

		return 'Setekart';
	}

	public function getContent() {
		$content = null;

		if (isset($_GET['id'])) {
			$seatmap = SeatmapHandler::getSeatmap($_GET["id"]);

			$content .= '<div class="box">';
				$content .= '<div class="box-body">';

					if ($seatmap != null) {
						$content .= '<div id="seatmapEditorPanel">';
							//Fille uploader widget
							$content .= '<form id="uploadBgForm" action="../api/json/seatmapUploadBg.php" method="post" enctype="multipart/form-data">';
								$content .= '<input type="file" id="uploadBgImage" name="bgImageFile" />';
					 			$content .= '<input type="submit" value="Last opp nytt bakgrunnsbilde" />';
					 			$content .= '<input type="hidden" name="seatmapId" value="' . $seatmap->getId() . '" />';
							$content .= '</form>';
							$content .= '<br />';
							$content .= '<br />';
							//Buttons
							$content .= '<input type="button" id="btnNewRow" value="Legg til rad på [0,0]" onclick="addRow()" /> | ';
							$content .= '<input type="button" id="btnSetCoords" value="Skriv inn kordinater selv" onclick="promptPosition()" /> | ';
							//$content .= '<input type="button" id="btnUploadImage" value="Last opp ny bakgrunn" onclick="uploadBackground()" /> | ';
							//Context sensitive buttons
							$content .= '<span id="seatmapEditorContextButtons">';

							$content .= '</span>';
							//Navigation buttons
							$content .= '<input type="button" value="Tilbake" onclick="redirectToSplash()" />';
							//Mouse pos indicator
							$content .= '<div id="mousePos">';
								$content .= '<i>Mus-posisjon: [0,0]. Klikk for å velge.</i>';
							$content .= '</div>';
						$content .= '</div>';
						$content .= '<div id="seatmapEditorCanvas">';
							$content .= '<i>Laster inn data...</i>';
						$content .= '</div>';


						$content .= '<script>var seatmapId = ' . $seatmap->getId() . '; </script>';
						$content .= '<script>';
							$content .= '$(document).ready(function() {';
								$content .= 'renderSeatmap();';
								$content .= '$("#uploadBgForm").ajaxForm(function() {';
									$content .= 'location.reload();';
								$content .= '});';
							$content .= '});';
						$content .= '</script>';
					} else {
						$content .= '<p>Setekartet finnes ikke.</p>';
					}

				$content .= '</div><!-- /.box-body -->';
			$content .= '</div><!-- /.box -->';
		} else {
			$content .= '<div class="box">';
				$content .= '<div class="box-body">';
					$content .= '<div id="seatmapIntro">';
						$content .= '<p>For å starte, må du velge et seatmap du vil redigere, eller lage et nytt.</p>';
						$content .= '<select id="seatmapSelect">';

							foreach (SeatmapHandler::getSeatmaps() as $seatmap) {
								$content .= '<option value="' . $seatmap->getId() . '">' . $seatmap->getHumanName() . '</option>';
							}

						$content .= '</select>';
						$content .= '<input type="button" value="Edit" onclick="editSeatmap()" />';
						$content .= '<input type="button" value="Lag kopi" onclick="copySeatmap()" />';
						$content .= '&nbsp;...eller...&nbsp;';
						$content .= '<input type="button" value="Lag nytt seatmap" onclick="newSeatmapName()" />';
					$content .= '</div>';
					$content .= '<div id="newSeatmapDiv" style="display: none;">';
						$content .= 'Hva skal seatmappet hete?&nbsp;';
						$content .= '<input type="text" id="newSeatmapName" />';
						$content .= '<input type="button" value="Lag nytt seatmap!" onclick="newSeatmap()" />';
						$content .= '<br /><br />';
						$content .= '<input type="button" value="Tilbake" onclick="backToMenuFromNewSeatmap()" />';
					$content .= '</div>';
				$content .= '</div><!-- /.box-body -->';
			$content .= '</div><!-- /.box -->';
		}

		$content .= '<script src="scripts/seatmapEditor.js"></script>';

		return $content;
	}
}
?>
