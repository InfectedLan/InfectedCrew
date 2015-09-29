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

require_once 'chief.php';
require_once 'session.php';
require_once 'handlers/userhandler.php';
require_once 'interfaces/page.php';

class ChiefEmailPage extends ChiefPage implements IPage {
	public function getTitle() {
		return 'E-post';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if ($user->hasPermission('chief.email')) {
				$content .= '<script src="scripts/chief-email.js"></script>';

				$content .= '<p>Her er en liste over Infected arrangementer som har vært eller skal være. Neste arrangement blir automatisk vist på hovedsiden.</p>';

				$content .= '<table>';
					$content .= '<form class="chief-email-send" method="post">';
						$content .= '<tr>';
							$content .= '<td>Mottakere:</td>';
							$content .= '<td>';
								$content .= '<select multiple class="chosen-select select" name="userIdList" data-placeholder="Velg mottakere...">';
									if ($user->hasPermission('*')) {
										$content .= '<option value="all">Alle</option>';
										$content .= '<option value="allMembers">Alle i crew</option>';
										$content .= '<option value="allNonMembers">Alle som ikke er i crew</option>';
										$content .= '<option value="allWithTicket">Alle med en billett</option>';
										$content .= '<option value="allWithTickets">Alle med flere billetter</option>';
										$content .= '<option value="allWithTicketLast3">Alle med billett siste 3 arrangementer</option>';
									}

									if ($user->isGroupMember()) {
										$content .= '<option value="group">Alle i ' . $user->getGroup()->getTitle() . '</option>';
									}

									foreach (UserHandler::getUsers() as $userValue) {
										$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
									}
								$content .= '</select>';
							$content .= '</td>';
						$content .= '</tr>';
						$content .= '<tr>';
							$content .= '<td>Emne:</td>';
							$content .= '<td><input type="text" name="subject" required></td>';
						$content .= '</tr>';
						$content .= '<tr>';
							$content .= '<td>Melding:</td>';
							$content .= '<td><textarea name="message" class="editor" rows="10" cols="80"></textarea></td>';
						$content .= '</tr>';
						$content .= '<tr>';
							$content .= '<td><input type="submit" value="Send"></td>';
						$content .= '</tr>';
					$content .= '</form>';
				$content .= '</table>';
			} else {
				$content .= 'Du har ikke rettigheter til dette!';
			}
		} else {
			$content .= 'Du er ikke logget inn!';
		}

		return $content;
	}
}
?>
