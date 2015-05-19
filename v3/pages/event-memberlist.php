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

require_once 'event.php';
require_once 'session.php';
require_once 'handlers/eventhandler.php';
require_once 'interfaces/page.php';

class EventMemberListPage extends EventPage implements IPage {
	public function getTitle() {
		return 'Medlemsliste';
	}

	public function getContent() {
		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if ($user->isGroupMember()) {
				$group = $user->getGroup();
				
				if ($user->hasPermission('*') || 
					$user->hasPermission('event.memberlist')) {
					echo '<script src="scripts/event-memberlist.js"></script>';
					
					echo '<div class="row">';
						echo '<div class="col-md-6">';
						  	echo '<div class="box">';
								echo '<div class="box-header">';
							  		echo '<h3 class="box-title">Hent ut medlemsliste</h3>';
								echo '</div><!-- /.box-header -->';
								echo '<div class="box-body">';
									echo '<p>Velg år du vil hente ut medlemsliste for, maksimal alder på medlemmene du vil ha med og et format du vil ha listen i.</p>';

							  		echo '<form class="memberlist" method="post">';
										echo '<div class="form-group">';
								  			echo '<label>År</label>';
								  			echo '<select class="form-control" name="year">';
												
												$eventList = EventHandler::getEvents();
												
												for ($year = date('Y', reset($eventList)->getStartTime()); $year <= date('Y'); $year++) {
													if ($year == date('Y')) {
														echo '<option value="' . $year . '" selected>' . $year . '</option>';
													} else {
														echo '<option value="' . $year . '">' . $year . '</option>';
													}
												}

											echo '</select>';
										echo '</div>';
										echo '<div class="form-group">';
										  	echo '<label>Aldersgrense</label>';
										  	echo '<select class="form-control" name="ageLimit">';
												
												for ($age = 1; $age <= 100; $age++) {
													if ($age == 20) {
														echo '<option value="' . $age . '" selected>' . $age . ' År</option>';
													} else {
														echo '<option value="' . $age . '">' . $age . ' År</option>';
													}
												}

											echo '</select>';
										echo '</div>';
										echo '<div class="form-group">';
											echo '<label>Format</label>';
											echo '<select class="form-control" name="format">';
												echo '<option value="html" selected>Tekst (.html)</option>';
												echo '<option value="csv">Regneark (.csv)</option>';
											echo '</select>';
									  	echo '</div><!-- /.form group -->';
									  	echo '<button type="submit" class="btn btn-primary">Hent</button>';
							  		echo '</form>';
								echo '</div><!-- /.box-body -->';
						  	echo '</div><!-- /.box -->';
						echo '</div><!--/.col (left) -->';
					echo '</div><!-- /.row -->';
				} else {
					echo '<div class="box">';
						echo '<div class="box-body">';
							echo '<p>Du har ikke rettigheter til dette!</p>';
						echo '</div><!-- /.box-body -->';
					echo '</div><!-- /.box -->';
				}
			} else {
				echo '<div class="box">';
					echo '<div class="box-body">';
						echo '<p>Du er ikke medlem av en gruppe.</p>';
					echo '</div><!-- /.box-body -->';
				echo '</div><!-- /.box -->';
			}
		} else {
			echo '<div class="box">';
				echo '<div class="box-body">';
					echo '<p>Du er ikke logget inn!</p>';
				echo '</div><!-- /.box-body -->';
			echo '</div><!-- /.box -->';
		}
	}
}
?>