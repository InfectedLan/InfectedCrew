<?php
require_once 'session.php';
require_once 'handlers/seatmaphandler.php';

echo '<script src="scripts/seatmapEditor.js"></script>';

if (isset($_GET['id'])) {
	showEditor();
} else {
	showSplash();
}

function showSplash() {
	echo '<center>';
		echo '<h1>Seatmap-editor</h1>';
		echo '<div id="seatmapIntro">';
			echo 'For å starte, må du velge et seatmap du vil redigere, eller lage et nytt.<br>';
			
			$seatmaps = SeatmapHandler::getSeatmaps();

			echo '<select id="seatmapSelect">';
			
			foreach($seatmaps as $seatmap) {
				echo '<option value="' . $seatmap->getId() . '">' . $seatmap->getHumanName() . '</option>';
			}
			echo '</select>';
			echo '<input type="button" value="Edit" onclick="editSeatmap()" />';
			echo '<input type="button" value="Lag kopi" onclick="copySeatmap()" />';
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

function showEditor() {
	$seatmap = SeatmapHandler::getSeatmap($_GET["id"]);
	if(!isset($seatmap))
	{
		echo '<h1>Seatmappet eksisterer ikke!</h1>';
		echo '<input type="button" onclick="redirectToSplash()" value="Tilbake" />';
	}
	else
	{
		echo '<script>var seatmapId = ' . $seatmap->getId() . '; </script>';

		echo '<script>';
			echo '$(document).ready(function() {';
				echo 'renderSeatmap();';
			echo '});';
		echo '</script>';

		echo '<div id="seatmapEditorPanel">';
			echo '<h1>Endrer på seatmappet "' . $seatmap->getHumanName() . '"</h1>';
			//Buttons
			echo '<input type="button" id="btnNewRow" value="Legg til rad på [0,0]" onclick="addRow()" /> | ';
			echo '<input type="button" id="btnSetCoords" value="Skriv inn kordinater selv" onclick="promptPosition()" /> | ';
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
		echo '<div id="seatmapCanvas">';
			echo '<i>Laster inn data...</i>';
		echo '</div>';
	}
}
?>