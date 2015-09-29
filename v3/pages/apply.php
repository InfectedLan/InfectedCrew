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
require_once 'settings.php';
require_once 'handlers/grouphandler.php';
require_once 'interfaces/page.php';
require_once 'traits/page.php';

class ApplyPage implements IPage {
	use TPage;

	public function getTitle() {
		return 'Søk';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			$content .= '<h1>Søk deg inn i crew</h1>';

			if (!$user->isGroupMember()) {
				if ($user->hasCroppedAvatar()) {
					$groupList = GroupHandler::getGroups();

					$content .= '<script src="scripts/apply.js"></script>';

					$content .= '<p>Velkommen! Som crew vil du oppleve ting du aldri ville som deltaker, få erfaringer du kan bruke sette på din CV-en, <br>';
					$content .= 'og møte mange nye og spennende mennesker. Dersom det er første gang du skal søke til crew på ' . Settings::name . ', <br>';
					$content .= 'anbefaler vi at du leser igjennom beskrivelsene av våre ' . count($groupList) . ' forksjellige crew. Disse finner du <a href="index.php?page=crewene">her</a>.</p>';
					$content .= '<p>Klar til å søke? Fyll ut skjemaet under:</p>';
					$content .= '<table>';
						$content .= '<form class="application" method="post">';
							$content .= '<tr>';
								$content .= '<td>Crew:</td>';
								$content .= '<td>';
									$content .= '<select name="groupId">';

										foreach ($groupList as $group) {
											$content .= '<option value="' . $group->getId() . '">' . $group->getTitle() . '</option>';
										}
										
									$content .= '</select>';
								$content .= '</td>';
							$content .= '</tr>';
							$content .= '<tr>';
								$content .= '<td>Tekst:</td>';
								$content .= '<td>';
									$content .= '<textarea name="content" rows="10" cols="80" placeholder="Skriv en kort oppsummering av hvorfor du vil søke her."></textarea>';
								$content .= '</td>';
							$content .= '</tr>';
							$content .= '<tr>';
								$content .= '<td><input type="submit" value="Send søknad"></td>';
							$content .= '</tr>';
						$content .= '</form>';
					$content .= '</table>';
				} else {
					$content .= '<p>Du er nødt til å laste opp et profilbilde for å søke. Dette gjør du <a href="index.php?page=edit-avatar">her.</a></p>';
				}
			} else {
				$group = $user->getGroup();

				$content .= '<p>Du er allerede med i <a href="index.php?page=crew&id=' . $group->getId() . '">' . $group->getTitle() . '</a>!<br>';
			}
		} else {
			$content .= '<p>Du må være logget inn for å søke!<br>';
		}

		return $content;
	}
}
?>
