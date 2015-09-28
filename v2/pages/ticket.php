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
require_once 'handlers/tickethandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('user.ticket')) {
		if (isset($_GET['id'])) {
			$ticket = TicketHandler::getTicket($_GET['id']);

			if ($ticket != null) {
				echo '<h3>' . $ticket->getString() . '</h3>';

				echo '<table>';
					echo '<tr>';
						echo '<td>Billettnummer:</td>';
						echo '<td>' . $ticket->getId() . '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Type:</td>';
						echo '<td>' . $ticket->getType()->getTitle() . '</td>';
					echo '</tr>';
					echo '<tr>';
						$buyerUser = $ticket->getBuyer();

						echo '<td>Kjøpt av:</td>';
						echo '<td><a href="index.php?page=user-profile&id=' . $buyerUser->getId()  . '">' . $buyerUser->getFullname() . '</a></td>';
					echo '</tr>';
					echo '<tr>';
						$ticketUser = $ticket->getUser();

						echo '<td>Brukes av:</td>';
						echo '<td><a href="index.php?page=user-profile&id=' . $ticketUser->getId()  . '">' . $ticketUser->getFullname() . '</a></td>';
					echo '</tr>';
					echo '<tr>';
						$seaterUser = $ticket->getSeater();

						echo '<td>Plasseres av:</td>';
						echo '<td><a href="index.php?page=user-profile&id=' . $seaterUser->getId()  . '">' . $seaterUser->getFullname() . '</a></td>';
					echo '</tr>';

					if ($ticket->isSeated()) {
						echo '<tr>';
							echo '<td>Plass:</td>';
							echo '<td>' . $ticket->getSeat()->getString() . '</td>';
						echo '</tr>';
					}

					echo '<tr>';
						echo '<td>Kan returneres?</td>';
						echo '<td>' . ($ticket->isRefundable() ? 'Ja' : 'Nei') . '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Sjekket inn?</td>';
						echo '<td>' . ($ticket->isCheckedIn() ? 'Ja' : 'Nei') . '</td>';
					echo '</tr>';
				echo '</table>';
			} else {
				echo '<p>Billetten finnes ikke.</p>';
			}
		} else {
			echo '<p>Ingen billett spesifisert.</p>';
		}
	} else {
		echo 'Du har ikke rettigheter til dette.';
	}
} else {
	echo 'Du er ikke logget inn.';
}
?>
