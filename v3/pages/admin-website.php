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

require_once 'admin.php';
require_once 'session.php';
require_once 'handlers/pagehandler.php';
require_once 'interfaces/page.php';

class AdminWebsitePage extends AdminPage implements IPage {
	public function getTitle() {
		return 'Nettside';
	}

	public function getContent() {
		$content = null;
		$site = 'https://infected.no/v7/';

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if ($user->hasPermission('admin.website')) {
				$pageList = PageHandler::getPages();
				$content .= '<script src="scripts/admin-website.js"></script>';
				$content .= '<h3>Sider:</h3>';

				$content .= '<table>';
					// Loop through the pages.
					foreach ($pageList as $page) {
						// Add the current page to the page view.
						$content .= '<tr>';
							$content .= '<td>' . $page->getTitle() . '</td>';
							$content .= '<td><a href="' . $site . 'pages/' . $page->getName() . '.html">Vis</a></td>';
							$content .= '<td><input type="button" value="Endre" onClick="editPage(' . $page->getId() . ')"></td>';

							if ($user->hasPermission('*')) {
								$content .= '<td><input type="button" value="Slett" onClick="removePage(' . $page->getId() . ')"></td>';
							}
						$content .= '</tr>';
					}
				$content .= '</table>';

				$content .= '<h3>Legg til ny side:</h3>';
				$content .= '<p>Fyll ut feltene under for å legge til en ny side.</p>';
				$content .= '<p>For å få innholdet i bokser, kan du bruke HTML-kode.<br>';
				$content .= 'Du putter hvilken type boks du vil inn i feltet "class", du finner alle tyoer bokser i tabellen under: <br>';
				$content .= '&lt;article class="Putt type boks inn her!"&gtInnhold her&lt;/article&gt</pre></p><br>';

				$content .= '<table>';
					$content .= '<tr>';
						$content .= '<th>Type boks:</th>';
						$content .= '<th>Kode:</th>';
					$content .= '</tr>';
					$content .= '<tr>';
						$content .= '<td>Tekst</td>';
						$content .= '<td>contentBox</td>';
					$content .= '</tr>';
					$content .= '<tr>';
						$content .= '<td>Venstre-stillt tekst</td>';
						$content .= '<td>contentLeftBox</td>';
					$content .= '</tr>';
					$content .= '<tr>';
						$content .= '<td>Høyre-stillt tekst</td>';
						$content .= '<td>contentRightBox</td>';
					$content .= '</tr>';
					$content .= '<tr>';
						$content .= '<td>Venstre-stillt bilde</td>';
						$content .= '<td>contentLeftImageBox</td>';
					$content .= '</tr>';
					$content .= '<tr>';
						$content .= '<td>Høyre-stillt bilde</td>';
						$content .= '<td>contentRightImageBox</td>';
					$content .= '</tr>';
				$content .= '</table>';

				$content .= '<form class="admin-website-add" method="post">';
					$content .= '<table>';
						$content .= '<tr>';
							$content .= '<td>Tittel:</td>';
							$content .= '<td><input type="text" name="title"></td>';
						$content .= '</tr>';
						$content .= '<tr>';
							$content .= '<td>Tekst:</td>';
							$content .= '<td><textarea name="content" rows="10" cols="80"></textarea></td>';
						$content .= '</tr>';
						$content .= '<tr>';
							$content .= '<td></td>';
							$content .= '<td><input type="submit" value="Publiser"></td>';
						$content .= '</tr>';
					$content .= '</table>';
				$content .= '</form>';
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
