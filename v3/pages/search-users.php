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
require_once 'interfaces/page.php';
require_once 'traits/page.php';

class SearchUsersPage implements IPage {
	use Page;

	public function getTitle() {
		return 'Søk etter brukere';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if ($user->hasPermission('*') ||
				$user->hasPermission('search.users')) {
				$content .= '<script src="scripts/search-users.js"></script>';
				$content .= '<h3>Søk etter bruker</h3>';
				
				$content .= '<input class="search" type="text" placeholder="Søk etter bruker..." autocomplete="off" autofocus>';
				$content .= '<ul class="search-results"></ul>';
			} else {
				$content .= '<p>Du har ikke rettigheter til dette!</p>';
			}
		} else {
			$content .= '<p>Du er ikke logget inn!</p>';
		}

		return $content;
	}
}
?>