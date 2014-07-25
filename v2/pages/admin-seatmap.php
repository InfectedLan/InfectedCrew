<?php
	require_once 'utils.php';
	require_once 'handlers/seatmaphandler.php';

	function showSplash()
	{
		echo '<center>';
			echo '<h1>Seatmap-editor</h1>';
			echo 'For å starte, må du velge et seatmap du vil redigere, eller lage et nytt.<br />';
			$seatmaps = SeatmapHandler::getSeatmaps();

			echo '<select id="seatmapSelect">';
			foreach($seatmaps as $seatmap)
			{
				echo '<option value="' . $seatmap->getId() . '">' . $seatmap->getHumanName() . '</option>';
			}
			echo '</select>';
			echo '<input type="button" value="Edit" onclick="editSeatmap()" />';
			echo '<input type="button" value="Lag kopi" onclick="copySeatmap()" />';
			echo '&nbsp;...eller...&nbsp;';
			echo '<input type="button" value="Lag nytt seatmap" onclick="newSeatmap()" />';
		echo '</center>';
	}

	function showEditor()
	{
		echo 'editor';
	}

	echo '<script src="scripts/seatmapEditor.js"></script>';

	if(!isset($_GET["id"]))
	{
		showSplash();
	}
	else
	{
		showEditor();
	}
?>