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
require_once 'page.php';

class EditRestrictedPage extends Page {
	public function getTitle(): string {
		return 'Endre side';
	}

	public function getContent(): string {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if ($user->hasPermission('chief.my-crew')) {
				if (isset($_GET['id']) &&
					is_numeric($_GET['id'])) {
					$page = RestrictedPageHandler::getPage($_GET['id']);

					if ($page != null) {
						if ($user->hasPermission('*') ||
							$user->hasPermission('chief.my-crew') &&
							$user->getGroup()->equals($page->getGroup())) {
							$content .= '<script src="scripts/edit-restricted-page.js"></script>';
							$content .= '<h3>Du endrer nå siden "' . $page->getTitle() . '"</h3>';

							$content .= '<form class="edit-restricted-page-edit" method="post">';
								$content .= '<input type="hidden" name="id" value="' . $page->getId() . '">';
								$content .= '<table>';
									$content .= '<tr>';
										$content .= '<td>Navn:</td>';
										$content .= '<td><input type="text" name="title" value="' . $page->getTitle() . '"> (Dette blir navnet på siden).</td>';
									$content .= '</tr>';

									if ($user->getGroup()->equals($page->getGroup())) {
										$group = $user->getGroup();

										$content .= '<tr>';
											$content .= '<td>Tilgang:</td>';
											$content .= '<td>';
												$content .= '<select class="chosen-select select" name="teamId">';
													$content .= '<option value="0">Alle</option>';

													foreach ($group->getTeams() as $team) {
														if ($team->equals($page->getTeam())) {
															$content .= '<option value="' . $team->getId() . '" selected>' . $team->getTitle() . '</option>';
														} else {
															$content .= '<option value="' . $team->getId() . '">' . $team->getTitle() . '</option>';
														}
													}
												$content .= '</select>';
											$content .= '</td>';
										$content .= '</tr>';
									}

									$content .= '<tr>';
										$content .= '<td>Innhold:</td>';
										$content .= '<td>';
											$content .= '<textarea class="editor" name="content" rows="10" cols="80">' . $page->getContent() . '</textarea>';
										$content .= '</td>';
									$content .= '</tr>';
								$content .= '</table>';
								$content .= '<input type="submit" value="Endre">';
							$content .= '</form>';
						} else {
							$content .= 'Du har ikke rettighet er til dette.';
						}
					} else {
						$content .= '<p>Siden finnes ikke.</p>';
					}
				}
			}
		}

		return $content;
	}
}
?>
