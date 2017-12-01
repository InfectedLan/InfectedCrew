<?php
/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2017 Infected <http://infected.no/>.
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
require_once 'handlers/restrictedpagehandler.php';
require_once 'chief.php';

class ChiefMyCrewPage extends ChiefPage {
	public function hasParent(): bool {
		return true;
	}

	public function getTitle(): string {
		return 'Mitt crew';
	}

	public function getContent(): string {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if ($user->isGroupMember()) {
				$group = $user->getGroup();

				if ($user->hasPermission('chief.my-crew')) {
					$content .= '<script src="scripts/chief-my-crew.js"></script>';
					$content .= '<h3>Mine sider</h3>';

					$pageList = RestrictedPageHandler::getAllPagesForGroup($group);

					if (!empty($pageList)) {
						$content .= '<table>';
							$content .= '<tr>';
								$content .= '<th>Navn</th>';
								$content .= '<th>Tilgang</th>';
							$content .= '</tr>';

							// Loop through the pages.
							foreach ($pageList as $page) {
								$team = $page->getTeam();

								$content .= '<tr>';
									$content .= '<td><a href="index.php?page=' . $page->getName() . '">' . $page->getTitle() . '</a></td>';
									$content .= '<td>' . ($team != null ? $team->getTitle() : 'Alle') . '</td>';
									$content .= '<td><input type="button" value="Endre" onClick="editPage(' . $page->getId() . ')"></td>';
									$content .= '<td><input type="button" value="Slett" onClick="removePage(' . $page->getId() . ')"></td>';
								$content .= '</tr>';
							}
						$content .= '</table>';
					} else {
						$content .= '<p>Det er ikke opprettet noen sider enda, du kan legge til en ny side under.</p>';
					}

					$content .= '<h3>Legg til ny side:</h3>';
					$content .= '<p>Fyll ut feltene under for å legge til en ny side.</p>';
					$content .= '<form class="chief-my-crew-add" method="post">';
						$content .= '<input type="hidden" name="groupId" value="' . $group->getId() . '">';
						$content .= '<table>';
							$content .= '<tr>';
								$content .= '<td>Navn:</td>';
								$content .= '<td><input type="text" name="title"> (Dette blir navnet på siden).</td>';
							$content .= '</tr>';
							$content .= '<tr>';
								$content .= '<td>Tilgang:</td>';
								$content .= '<td>';
									$content .= '<select class="chosen-select select" name="teamId">';
										$content .= '<option value="0">Alle</option>';

										foreach ($group->getTeams() as $team) {
											$content .= '<option value="' . $team->getId() . '">' . $team->getTitle() . '</option>';
										}
									$content .= '</select>';
								$content .= '</td>';
							$content .= '</tr>';
							$content .= '<tr>';
								$content .= '<td>Innhold:</td>';
								$content .= '<td>';
									$content .= '<textarea class="editor" name="content" rows="10" cols="80"></textarea>';
								$content .= '</td>';
							$content .= '</tr>';
						$content .= '</table>';
						$content .= '<input type="submit" value="Legg til">';
					$content .= '</form>';
				} else {
					$content .= '<p>Du har ikke rettigheter til dette!</p>';
				}
			} else {
				$content .= '<p>Du er ikke medlem av en gruppe.</p>';
			}
		} else {
			$content .= '<p>Du er ikke logget inn!</p>';
		}

		return $content;
	}
}
?>
