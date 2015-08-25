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
require_once 'handlers/userhandler.php';


if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('chief.email')) {
		echo '<script src="scripts/chief-email.js"></script>';
		
		echo '<h3>E-poster:</h3>';
		echo '<p>Her er en liste over Infected arrangementer som har vært eller skal være. Neste arrangement blir automatisk vist på hovedsiden.</p>';

		echo '<table>';
			echo '<form class="chief-email-send" method="post">';
				echo '<tr>';
					echo '<td>Mottakere:</td>';
					echo '<td>';
						echo '<select multiple class="chosen-select select" name="userIdList" data-placeholder="Velg mottakere...">';
							if ($user->hasPermission('*')) {
								echo '<option value="all">Alle</option>';
								echo '<option value="allMembers">Alle i crew</option>';
								echo '<option value="allNonMembers">Alle som ikke er i crew</option>';
								echo '<option value="allWithTicket">Alle med en billett</option>';
								echo '<option value="allWithTickets">Alle med flere billetter</option>';
								echo '<option value="allWithTicketLast3">Alle med billett siste 3 arrangementer</option>';
							}

							if ($user->isGroupMember()) {
								echo '<option value="group">Alle i ' . $user->getGroup()->getTitle() . '</option>';
							}

							foreach (UserHandler::getUsers() as $userValue) {
								echo '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
							}
						echo '</select>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Emne:</td>';
					echo '<td><input type="text" name="subject" required></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Melding:</td>';
					echo '<td><textarea name="message" class="editor" rows="10" cols="80"></textarea></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><input type="submit" value="Send"></td>';
				echo '</tr>';
			echo '</form>';
		echo '</table>';
	} else {
		echo 'Du har ikke rettigheter til dette!';
	}
} else {
	echo 'Du er ikke logget inn!';
}
?>
