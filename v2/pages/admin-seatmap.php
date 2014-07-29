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
	echo 'editor';
}
?>