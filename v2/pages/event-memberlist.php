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

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('event.memberlist')) {
		echo '<script src="scripts/event-memberlist.js"></script>';
		echo '<h3>Medlemsliste</h3>';

		echo '<p>Velg år du vil hente ut medlemsliste for, maksimal alder på medlemmene du vil ha med og et format du vil ha listen i.<br></p>';

		echo '<form class="memberlist" method="post">';
			echo '<table>';
				echo '<tr>';
					echo '<td>År:</td>';
					echo '<td>';
						echo '<select name="year">';
							$eventList = EventHandler::getEvents();

							for ($year = date('Y', reset($eventList)->getStartTime()); $year <= date('Y'); $year++) {
								if ($year == date('Y')) {
									echo '<option value="' . $year . '" selected>' . $year . '</option>';
								} else {
									echo '<option value="' . $year . '">' . $year . '</option>';
								}
							}
						echo '</select>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Aldersgrense:</td>';
					echo '<td>';
						echo '<select name="ageLimit">';
							for ($age = 1; $age <= 100; $age++) {
								if ($age == 20) {
									echo '<option value="' . $age . '" selected>' . $age . '</option>';
								} else {
									echo '<option value="' . $age . '">' . $age . '</option>';
								}
							}
						echo '</select> År';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Format:</td>';
					echo '<td>';
						echo '<select name="format">';
							echo '<option value="html" selected>Tekst</option>';
							echo '<option value="csv">Regneark</option>';
						echo '</select>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><input type="submit" value="Hent"></td>';
				echo '</tr>';
			echo '</table>';
		echo '</form>';
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>
