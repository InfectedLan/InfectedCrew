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
require_once 'interfaces/page.php';
require_once 'traits/page.php';

class TicketPage implements IPage {
	use TPage;

	public function getTitle() {
		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if ($user->hasPermission('*') ||
				$user->hasPermission('event.tickets')) {
				
				if (isset($_GET['id'])) {
					$ticket = TicketHandler::getTicket($_GET['id']);
					
					if ($ticket != null) {
						return 'Billet #' . $ticket->getId();
					}
				}
			}
		}

		return 'Billet';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if ($user->hasPermission('*') ||
				$user->hasPermission('event.tickets')) {
				
				if (isset($_GET['id'])) {
					$ticket = TicketHandler::getTicket($_GET['id']);
					
					if ($ticket != null) {
						$content .= '<h3>' . $ticket->getString() . '</h3>';
						
						$content .= '<table>';
							$content .= '<tr>';
								$content .= '<td>Billettnummer:</td>';
								$content .= '<td>' . $ticket->getId() . '</td>';
							$content .= '</tr>';
							$content .= '<tr>';
								$content .= '<td>Type:</td>';
								$content .= '<td>' . $ticket->getType()->getTitle() . '</td>';
							$content .= '</tr>';
							$content .= '<tr>';
								$buyerUser = $ticket->getBuyer();

								$content .= '<td>Kjøpt av:</td>';
								$content .= '<td><a href="index.php?page=my-profile&id=' . $buyerUser->getId()  . '">' . $buyerUser->getFullname() . '</a></td>';
							$content .= '</tr>';
							$content .= '<tr>';
								$ticketUser = $ticket->getUser();

								$content .= '<td>Brukes av:</td>';
								$content .= '<td><a href="index.php?page=my-profile&id=' . $ticketUser->getId()  . '">' . $ticketUser->getFullname() . '</a></td>';
							$content .= '</tr>';
							$content .= '<tr>';
								$seaterUser = $ticket->getSeater();

								$content .= '<td>Plasseres av:</td>';
								$content .= '<td><a href="index.php?page=my-profile&id=' . $seaterUser->getId()  . '">' . $seaterUser->getFullname() . '</a></td>';
							$content .= '</tr>';

							if ($ticket->isSeated()) {
								$content .= '<tr>';
									$content .= '<td>Plass:</td>';
									$content .= '<td>' . $ticket->getSeat()->getString() . '</td>';
								$content .= '</tr>';
							}

							$content .= '<tr>';
								$content .= '<td>Kan returneres?</td>';
								$content .= '<td>' . ($ticket->isRefundable() ? 'Ja' : 'Nei') . '</td>';
							$content .= '</tr>';
							$content .= '<tr>';
								$content .= '<td>Sjekket inn?</td>';
								$content .= '<td>' . ($ticket->isCheckedIn() ? 'Ja' : 'Nei') . '</td>';
							$content .= '</tr>';
						$content .= '</table>';
					} else {
						$content .= '<p>Billetten finnes ikke.</p>';
					}
				} else {
					$content .= '<p>Ingen billett spesifisert.</p>';
				}
			} else {
				$content .= 'Du har ikke rettigheter til dette.';
			}
		} else {
			$content .= 'Du er ikke logget inn.';
		}

		return $content;
	}
}
?>