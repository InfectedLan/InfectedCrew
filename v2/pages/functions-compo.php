<?php
require_once 'session.php';
require_once 'handlers/eventhandler.php';
require_once 'handlers/compohandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('functions.compoadmin')) {

		if(isset($_GET['id'])) {
			$compo = CompoHandler::getCompo($_GET['id']);
			echo '<script src="scripts/functions-compo.js"></script>';
			if(CompoHandler::hasGeneratedMatches($compo)) {
				echo '<script>var compoId = ' . $compo->getId() . '; initMatchList();</script>';

				echo '<div id="teamListArea"></div>';
			} else {
				echo '<h1>' . $compo->getName() .'</h1>';
				echo '<i>Matcher har ikke blitt generert enda</i><br />';
				echo '<input type="button" value="Generer matcher(stenger registrering)" onClick="generateMatches()" />';

				//Show list of teams
				$teams = CompoHandler::getClans($compo);
				//Count stats
				$numQualified = 0;
				foreach($teams as $clan) {
					if($clan->isQualified($compo)) {
						$numQualified++;
					}
				}
				echo '<h3>Fullstendige lag:</h3>';
				echo '<br />';
				echo '<br />';
				if($numQualified==0) {
					echo '<i>Ingen lag er fullstendige enda!</i>';
				}
				echo '<ul>';
				//print_r($teams);
				foreach($teams as $clan) {
					if($clan->isQualified($compo)) {
						echo '<li class="teamEntry" id="teamButtonId' . $clan->getId() . '">' . $clan->getName() . '</li>';
					}
				}
				echo '</ul>';
				if(count($teams) != $numQualified) {
					echo '<br />';
					echo '<h3>Ufullstendige lag:</h3>';
					echo '<br />';
					echo '<ul>';
						foreach($teams as $clan) {
							if(!$clan->isQualified($compo)) {
								echo '<li class="teamEntry" id="teamButtonId' . $clan->getId() . '">' . $clan->getName() . '</li>';
							}
						}
					echo '</ul>';
					echo '<br />';
					echo '<i>Disse lagene mangler spillere og vil ikke kunne delta med mindre de klarer å fylle laget</i>';
				}
			}

			
		} else {
			echo '<p>Mangler felt!</p>';
		}

	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>