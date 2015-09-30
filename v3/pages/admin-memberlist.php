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
 require_once 'handlers/eventhandler.php';
 require_once 'interfaces/page.php';

class AdminMemberListPage extends AdminPage implements IPage {
 	public function getTitle() {
 		return 'Medlemsliste';
 	}

 	public function getContent() {
 		$content = null;

 		if (Session::isAuthenticated()) {
 			$user = Session::getCurrentUser();

 			if ($user->hasPermission('admin.memberlist')) {
				$content .= '<script src="scripts/admin-memberlist.js"></script>';

				$content .= '<div class="row">';
					$content .= '<div class="col-md-4">';
						$content .= '<div class="box">';
							$content .= '<div class="box-body">';
								$content .= '<p>Velg år du vil hente ut medlemsliste for, maksimal alder på medlemmene du vil ha med og et format du vil ha listen i.</p>';
								$content .= '<form class="memberlist" method="post">';
									$content .= '<div class="form-group">';
										$content .= '<label>År</label>';
										$content .= '<select class="form-control" name="year">';
											$eventList = EventHandler::getEvents();

											for ($year = date('Y', reset($eventList)->getStartTime()); $year <= date('Y'); $year++) {
												if ($year == date('Y')) {
													$content .= '<option value="' . $year . '" selected>' . $year . '</option>';
												} else {
													$content .= '<option value="' . $year . '">' . $year . '</option>';
												}
											}

										$content .= '</select>';
									$content .= '</div>';
									$content .= '<div class="form-group">';
										$content .= '<label>Aldersgrense</label>';
										$content .= '<select class="form-control" name="ageLimit">';

											for ($age = 1; $age <= 100; $age++) {
												if ($age == 20) {
													$content .= '<option value="' . $age . '" selected>' . $age . ' År</option>';
												} else {
													$content .= '<option value="' . $age . '">' . $age . ' År</option>';
												}
											}

										$content .= '</select>';
									$content .= '</div>';
									$content .= '<div class="form-group">';
										$content .= '<label>Format</label>';
										$content .= '<select class="form-control" name="format">';
											$content .= '<option value="html" selected>Tekst (.html)</option>';
											$content .= '<option value="csv">Regneark (.csv)</option>';
										$content .= '</select>';
									$content .= '</div><!-- /.form group -->';
									$content .= '<button type="submit" class="btn btn-primary">Hent</button>';
								$content .= '</form>';
							$content .= '</div><!-- /.box-body -->';
						$content .= '</div><!-- /.box -->';
					$content .= '</div><!--/.col (left) -->';
				$content .= '</div><!-- /.row -->';
			} else {
				$content .= '<div class="box">';
					$content .= '<div class="box-body">';
						$content .= '<p>Du har ikke rettigheter til dette.</p>';
					$content .= '</div><!-- /.box-body -->';
				$content .= '</div><!-- /.box -->';
			}
		} else {
			$content .= '<div class="box">';
				$content .= '<div class="box-body">';
					$content .= '<p>Du er ikke logget inn.</p>';
				$content .= '</div><!-- /.box-body -->';
			$content .= '</div><!-- /.box -->';
		}

		return $content;
	}
}
?>
