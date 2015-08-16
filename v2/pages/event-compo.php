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
require_once 'handlers/compohandler.php';
require_once 'handlers/clanhandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('*') ||
		$user->hasPermission('functions.compoadmin')) {

		if(isset($_GET['id'])) {
			$compo = CompoHandler::getCompo($_GET['id']);

			echo '<script src="scripts/event-compo.js"></script>';
			echo '<script>var compoId = ' . $compo->getId() . ';</script>';

			if (CompoHandler::hasGeneratedMatches($compo)) {
				echo '<script>initMatchList();</script>';

				echo '<div id="teamListArea"></div>';
			} else {
				echo '<h1>' . $compo->getName() .'</h1>';
				echo '<i>Matcher har ikke blitt generert enda</i><br>';
				echo '<table>';
					echo '<tr>';
						echo '<td>';
							echo 'Starttid';
						echo '</td>';
						echo '<td>';
							echo 'Match-mellomrom';
						echo '</td>';
						echo '<td></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>';
							echo '<input type="text" id="startTime" value="' . time() . '">';
						echo '</td>';
						echo '<td>';
							echo '<input type="text" id="compoSpacing" value="3600">';
						echo '</td>';
						echo '<td>';
							echo '<input type="button" value="Generer matcher(stenger registrering)" onClick="generateMatches()">';
						echo '</td>';
					echo '</tr>';
				echo '</table>';


				// Show list of teams
				$teams = ClanHandler::getClansByCompo($compo);
				// Count stats
				$numQualified = 0;

				foreach ($teams as $clan) {
					if ($clan->isQualified($compo)) {
						$numQualified++;
					}
				}
				echo '<h3>Fullstendige lag:</h3>';
				echo '<br>';
				echo '<br>';

				if ($numQualified==0) {
					echo '<i>Ingen lag er fullstendige enda!</i>';
				}

				echo '<ul>';
					foreach ($teams as $clan) {
						if ($clan->isQualified($compo)) {
							echo '<li class="teamEntry" id="teamButtonId' . $clan->getId() . '">' . $clan->getName() . '</li>';
						}
					}
				echo '</ul>';

				if (count($teams) != $numQualified) {
					echo '<br>';
					echo '<h3>Ufullstendige lag:</h3>';
					echo '<br>';
					echo '<ul>';
						foreach($teams as $clan) {
							if(!$clan->isQualified($compo)) {
								echo '<li class="teamEntry" id="teamButtonId' . $clan->getId() . '">' . $clan->getName() . '</li>';
							}
						}
					echo '</ul>';
					echo '<br>';
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
