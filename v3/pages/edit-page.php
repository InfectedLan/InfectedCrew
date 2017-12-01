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
require_once 'handlers/pagehandler.php';
require_once 'page.php';

class EditPage extends Page {
	public function getTitle(): string {
		return 'Endre side';
	}

	public function getContent(): string {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if ($user->hasPermission('admin.website')) {
				if (isset($_GET['id']) &&
					is_numeric($_GET['id'])) {
					$page = PageHandler::getPage($_GET['id']);

					if ($page != null) {
						$content .= '<script src="scripts/edit-page.js"></script>';
						$content .= '<h3>Du endrer nå siden "' . $page->getTitle() . '"</h3>';

						$content .= '<form class="edit-page" method="post">';
							$content .= '<input type="hidden" name="id" value="' . $page->getId() . '">';
							$content .= '<table>';
								$content .= '<tr>';
									$content .= '<td>Tittel:</td>';
									$content .= '<td><input type="text" name="title" value="' . $page->getTitle() . '"> (Dette blir også navnet på linken til siden).</td>';
								$content .= '</tr>';
							$content .= '</table>';
							$content .= '<textarea name="content" rows="10" cols="80">' . $page->getContent() . '</textarea>';
							$content .= '<input type="submit" value="Endre">';
						$content .= '</form>';
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
