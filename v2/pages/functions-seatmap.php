<?php
require_once 'session.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('functions.tickets')) {

		echo '<link rel="stylesheet" href="../api/style/seatmap.css">';
	
		echo '<h1>Seatmap for årets arrangement</h1>';

		echo '<div id="seatmapCanvas"></div>';
		echo '<script src="../api/scripts/seatmapRenderer.js"></script>';

		echo '<script>';
			echo 'var seatmapId = ' . SeatmapHandler::getSeatmap(EventHandler::getCurrentEvent()->getSeatmap())->getId() . ';';
			echo 'var ticketId = ' . $ticket->getId() . ';';
			echo '$(document).ready(function() {';
				echo 'downloadAndRenderSeatmap("#seatmapCanvas", function() {}, function() {});';
			echo '});';
		echo '</script>';
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>