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
require_once 'handlers/userhandler.php';
require_once 'handlers/userhistoryhandler.php';
require_once 'interfaces/page.php';
require_once 'traits/page.php';

class UserHistoryPage implements IPage {
	use Page;

	public function getTitle() {
		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			$historyUser = isset($_GET['id']) ? UserHandler::getUser($_GET['id']) : Session::getCurrentUser();

			if ($user->equals($historyUser)) {
				return 'Min bruker historikk';
			} else if ($user->hasPermission('*')) {
				return $historyUser->getDisplayName() . '\'s historikk';
			}
		}

		return 'Bruker historikk';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			$historyUser = isset($_GET['id']) ? UserHandler::getUser($_GET['id']) : Session::getCurrentUser();

			if ($user->hasPermission('*') ||
				$user->equals($historyUser)) {
				$eventList = UserHistoryHandler::getEventsByUser($historyUser);

				if (!empty($eventList)) {
					$content .= '<div class="row">';
						$content .= '<div class="col-md-6">';
						  	$content .= '<div class="box">';
								$content .= '<div class="box-body">';
									$content .= '<p>Denne brukeren har deltatt på følgende arrangementer:</p>';
									$content .= '<table class="table table-bordered">';
										$content .= '<tr>';
											$content .= '<th>Arrangement</th>';
											$content .= '<th>Rolle</th>';
										$content .= '</tr>';

										foreach ($eventList as $event) {
											$content .= '<tr>';
												$content .= '<td>' . $event->getTitle() . '</td>';
												$content .= '<td>' . $historyUser->getRoleByEvent($event) . '</td>';
											$content .= '</tr>';
										}

									$content .= '</table>';
								$content .= '</div><!-- /.box-body -->';
						  	$content .= '</div><!-- /.box -->';
						$content .= '</div><!--/.col (left) -->';
					$content .= '</div><!-- /.row -->';
				} else {
					$content .= '<div class="box">';
						$content .= '<div class="box-body">';
							$content .= '<p>Denne brukeren har ikke noe historie enda.</p>';
						$content .= '</div><!-- /.box-body -->';
					$content .= '</div><!-- /.box -->';
				}
			} else {
				$content .= '<div class="box">';
					$content .= '<div class="box-body">';
						$content .= 'Du har ikke rettigheter til dette.';
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