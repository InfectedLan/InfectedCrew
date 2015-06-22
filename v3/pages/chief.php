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

require_once 'interfaces/page.php';
require_once 'traits/page.php';

class ChiefPage implements IPage {
	use TPage;

	public function getTitle() {
		return 'Chief';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if ($user->hasPermission('*') ||
				$user->hasPermission('chief')) {
				$content .= '<p>Du finner alle funksjonene øverst i menyen til høyre for Infected logoen.';
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