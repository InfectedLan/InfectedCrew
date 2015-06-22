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

class DeveloperPage implements IPage {
	use TPage;

	public function getTitle() {
		return 'Utvikler';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if ($user->hasPermission('*') || 
				$user->hasPermission('developer')) {
				$content .= '<div class="row">';
					$content .= '<div class="col-md-4">';
						$content .= '<div class="box">';
							$content .= '<div class="box-body">';
								$content .= '<p>Du finner alle utviklerfunksjonene øverst i menyen til høyre for Infected logoen.';
							$content .= '</div><!-- /.box-body -->';
						$content .= '</div><!-- /.box -->';
					$content .= '</div><!--/.col (left) -->';
				$content .= '</div><!-- /.row -->';
			} else {
				$content .= '<div class="box">';
					$content .= '<div class="box-body">';
						$content .= 'Du har ikke rettigheter til dette!';
					$content .= '</div><!-- /.box-body -->';
				$content .= '</div><!-- /.box -->';
			}
		} else {
			$content .= '<div class="box">';
				$content .= '<div class="box-body">';
					$content .= '<p>Du er ikke logget inn!</p>';
				$content .= '</div><!-- /.box-body -->';
			$content .= '</div><!-- /.box -->';
		}

		return $content;
	}
}
?>