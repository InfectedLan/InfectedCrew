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

			if ($user->hasPermission('user.ticket')) {
				if (isset($_GET['id'])) {
					$ticket = TicketHandler::getTicket($_GET['id']);

					if ($ticket != null) {
						return 'Billett #' . $ticket->getId();
					}
				}
			}
		}

		return 'Billett';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if ($user->hasPermission('user.ticket')) {
				if (isset($_GET['id'])) {
					$ticket = TicketHandler::getTicket($_GET['id']);

					if ($ticket != null) {
						$content .= '<div class="row">';
							$content .= '<div class="col-md-4">';
								$content .= '<div class="box">';
									$content .= '<div class="box-body">';
										$content .= '<table class="table">';
											$content .= '<tr>';
												$content .= '<td>Billettnummer:</td>';
												$content .= '<td>' . $ticket->toString() . '</td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td>Type:</td>';
												$content .= '<td>' . $ticket->getType()->getTitle() . '</td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$buyerUser = $ticket->getBuyer();

												$content .= '<td>Kjøpt av:</td>';
												$content .= '<td><a href="index.php?page=user-profile&id=' . $buyerUser->getId()  . '">' . $buyerUser->getFullname() . '</a></td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$ticketUser = $ticket->getUser();

												$content .= '<td>Brukes av:</td>';
												$content .= '<td><a href="index.php?page=user-profile&id=' . $ticketUser->getId()  . '">' . $ticketUser->getFullname() . '</a></td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$seaterUser = $ticket->getSeater();

												$content .= '<td>Plasseres av:</td>';
												$content .= '<td><a href="index.php?page=user-profile&id=' . $seaterUser->getId()  . '">' . $seaterUser->getFullname() . '</a></td>';
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
									$content .= '</div><!-- /.box-body -->';
							  $content .= '</div><!-- /.box -->';
							$content .= '</div><!--/.col (left) -->';
						$content .= '</div><!-- /.row -->';
					} else {
						$content .= '<div class="box">';
							$content .= '<div class="box-body">';
								$content .= '<p>Denne billetten finnes ikke.</p>';
							$content .= '</div><!-- /.box-body -->';
						$content .= '</div><!-- /.box -->';
					}
				} else {
					$content .= '<div class="box">';
						$content .= '<div class="box-body">';
							$content .= '<p>Ingen billett spesifisert.</p>';
						$content .= '</div><!-- /.box-body -->';
					$content .= '</div><!-- /.box -->';
				}
			} else {
				$content .= '<div class="box">';
					$content .= '<div class="box-body">';
						$content .= '<p>Du har ikke rettigheter til dette.</p>';
					$content .= '</div><!-- /.box-body -->';
				$content .= '</div><!-- /.box -->';
			}
		} else {
			$content .= '<div class="box">';
				$content .= '<div class="box-body">';
					$content .= '<p>Du er ikke logget inn.</p>';
				$content .= '</div><!-- /.box-body -->';
			$content .= '</div><!-- /.box -->';
		}

		return $content;
	}
}
?>
