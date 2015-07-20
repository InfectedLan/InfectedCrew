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
require_once 'handlers/gamehandler.php';
require_once 'handlers/gameapplicationhandler.php';

$site = 'http://infected.no/v7/';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('*') || 
		$user->hasPermission('functions.list-games')) {
		echo '<script src="scripts/functions-list-games.js"></script>';
		echo '<h3>Spill</h3>';
		
		$gameList = GameHandler::getGames();
		
		if (!empty($gameList)) {
			echo '<table>';
				echo '<tr>';
					echo '<th>Navn</th>';
					echo '<th>Pris</th>';
					echo '<th>Mode</th>';
					echo '<th>Beskrivelse</th>';
					echo '<th>Påmeldingsfrist</th>';
				echo '</tr>';
				
				foreach ($gameList as $game) {
					echo '<tr>';
						echo '<form class="functions-site-games-edit" method="post">';
							echo '<input type="hidden" name="id" value="' . $game->getId() . '">';
							echo '<td><input type="text" name="title" value="' . $game->getTitle() . '"></td>';
							echo '<td><input type="text" name="price" value="' . $game->getPrice() . '"></td>';
							echo '<td><input type="text" name="mode" value="' . $game->getMode() . '"></td>';
							echo '<td><input type="text" name="description" value="' . $game->getDescription() . '"></td>';
							echo '<td>';
								echo '<input type="date" name="startDate" value="' . date('Y-m-d', $game->getStartTime()) . '" placeholder="åååå-mm-dd">';
								echo '<input type="time" name="startTime" value="' . date('H:i:s', $game->getStartTime()) . '" placeholder="tt:mm:ss">';
							echo '</td>';
							echo '<td>';
								echo '<input type="date" name="endDate" value="' . date('Y-m-d', $game->getEndTime()) . '" placeholder="åååå-mm-dd">';
								echo '<input type="time" name="endTime" value="' . date('H:i:s', $game->getEndTime()) . '" placeholder="tt:mm:ss">';
							echo '</td>';
							
							if ($game->isPublished()) {
								echo '<td><input type="checkbox" name="published" value="1" checked></td>';
							} else {
								echo '<td><input type="checkbox" name="published" value="1"></td>';
							}
							
							echo '<td><input type="submit" value="Endre"></td>';
						echo '</form>';
						echo '<td><input type="button" value="Slett" onClick="removeGame(' . $game->getId() . ')"></td>';
					echo '</tr>';
				}
			echo '</table>';

			echo '<h4>Legg til et spill</h4>';
			
			echo '<form class="functions-site-games-add" method="post">';
				echo '<table>';
					echo '<tr>';
						echo '<td>Navn:</td>';
						echo '<td><input type="text" name="title" required></td>';
						echo '<td>(Full tittel på spillet).</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Premie:</td>';
						echo '<td><input type="number" name="price" required></td>';
						echo '<td>,-</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Modus:</td>';
						echo '<td><input type="text" name="mode" required></td>';
						echo '<td>(Hvilket oppsett har vi? Eks. 1on1).</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Beskrivelse:</td>';
						echo '<td><input type="text" name="description" required></td>';
						echo '<td>(Ekstrainformasjon som vises bak premie på hovedsiden).</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Påmeldingsstart:</td>';
						echo '<td><input type="date" name="startDate" placeholder="' . date('Y-m-d') . '" required></td>';
						echo '<td><input type="time" name="startTime" placeholder="' . date('H:i:s') . '" required></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Påmeldingsfrist:</td>';
						echo '<td><input type="date" name="endDate" placeholder="' . date('Y-m-d') . '" required></td>';
						echo '<td><input type="time" name="endTime" placeholder="' . date('H:i:s') . '" required></td>';
					echo '</tr>';
				echo '</table>';
				echo '<textarea class="editor" name="content" rows="10" cols="80"></textarea>';
				echo '<input type="submit" value="Legg til">';
			echo '</form>';
		}

		echo '<h4>Compo påmeldinger</h4>';
		
		if (!empty($gameList)) {
			foreach ($gameList as $game) {
				$gameApplicationList = GameApplicationHandler::getGameApplications($game);
				
				echo '<h3><a href="' . $site . 'pages/game/id/' . $game->getId() . '.html">' . $game->getTitle() . '</a></h3>';
				echo '<table>';			
					if (!empty($gameApplicationList)) {
						echo '<tr>';
							echo '<th>Clan:</th>';
							echo '<th>Tag:</th>';
							echo '<th>Navn:</th>';
							echo '<th>Nick:</th>';
							echo '<th>Telefon:</th>';
							echo '<th>E-post:</th>';
						echo '</tr>';
						
						foreach ($gameApplicationList as $gameApplication) {
							echo '<tr>';
								echo '<td>' . $gameApplication->getName() . '</td>';
								echo '<td>' . $gameApplication->getTag() . '</td>';
								echo '<td>' . $gameApplication->getContactname() . '</td>';
								echo '<td>' . $gameApplication->getContactnick() . '</td>';
								echo '<td>' . $gameApplication->getPhone() . '</td>';
								echo '<td>' . $gameApplication->getEmail() . '</td>';
								echo '<td><input type="button" value="Fjern" onClick="removeGameApplication(' . $gameApplication->getId() . ')"></td>';
							echo '</tr>';
						}
					} else {
						echo '<tr>';
							echo '<td>Ingen har meldt seg på compo i <i>' . $game->getTitle() . '</i> enda.</td>';
						echo '</tr>';
					}
				echo '</table>';
			}
		} else {
			echo 'Ingen spill er registrert enda.';
		}
	} else {
		echo 'Du har ikke rettigheter til dette!';
	}
} else {
	echo 'Du er ikke logget inn!';
}
?>