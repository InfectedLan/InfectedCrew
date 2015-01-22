<?php
require_once 'session.php';

$seatmapId = isset($_GET['id']) ? $_GET['id'] : EventHandler::getCurrentEvent()->getSeatmap()->getId();

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('event.seatmap')) {

		echo '<link rel="stylesheet" href="../api/styles/seatmap.css">';
	
		echo '<h1>Seatmap for årets arrangement</h1>';

		echo '<div id="seatmapCanvas"></div>';
		echo '<script src="../api/scripts/seatmapRenderer.js"></script>';

		echo '<script>';
			echo 'var seatmapId = ' . $seatmapId . ';';
			echo '$(document).ready(function() {';
				echo 'downloadAndRenderSeatmap("#seatmapCanvas");';
			echo '});';
		echo '</script>';
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>