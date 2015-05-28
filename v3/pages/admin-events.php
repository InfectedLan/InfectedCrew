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
require_once 'handlers/locationhandler.php';
require_once 'interfaces/page.php';

class AdminEventsPage extends AdminPage implements IPage {
	public function getTitle() {
		return 'Arrangementer';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if ($user->hasPermission('*') ||
				$user->hasPermission('admin.events')) {

				$content .= '<div class="row">';
					$content .= '<div class="col-md-6">';
							
						$eventList = EventHandler::getEvents();

						// Sort this array so that we show newest events first.
						rsort($eventList);

						if (!empty($eventList)) {
							foreach ($eventList as $event) {
							  	$content .= '<div class="box">';
									$content .= '<div class="box-header">';
								  		$content .= '<h3 class="box-title">' . $event->getTitle() . '</h3>';
									$content .= '</div><!-- /.box-header -->';
									$content .= '<div class="box-body">';
							  		
										$content .= '<form class="admin-events-edit" method="post">';
											$content .= '<input type="hidden" name="id" value="' . $event->getId() . '">';
											$content .= '<div class="form-group">';
									  			$content .= '<label>Sted</label>';
									  			$content .= '<select class="form-control" name="location" required>';

									  				foreach (LocationHandler::getLocations() as $location) {
									  					if ($location->equals($event->getLocation())) {
									  						$content .= '<option value="' . $location->getId() . '" selected>' . $location->getTitle() . '</option>';
									  					} else {
															$content .= '<option value="' . $location->getId() . '">' . $location->getTitle() . '</option>';
														}
													}
													
												$content .= '</select>';
											$content .= '</div>';
									  		$content .= '<div class="form-group">';
									  			$content .= '<label>Anstall deltakere</label>';
												$content .= '<input type="number" class="form-control" name="participants" value="' . $event->getParticipants() . '" required>';
											$content .= '</div>';
											$content .= '<div class="form-group">';
												$content .= '<label>Billetsalgsdato og tid</label>';
												$content .= '<div class="input-group">';
											  		$content .= '<div class="input-group-addon">';
														$content .= '<i class="fa fa-clock-o"></i>';
											  		$content .= '</div>';
											  		$content .= '<input type="text" class="form-control pull-right" name="bookingTime" id="datetime" value="' . date('Y-m-d H:i:s', $event->getBookingTime()) . '" required>';
												$content .= '</div><!-- /.input group -->';
										  	$content .= '</div><!-- /.form group -->';
											$content .= '<div class="form-group">';
												$content .= '<label>Startdato og tid</label>';
												$content .= '<div class="input-group">';
											  		$content .= '<div class="input-group-addon">';
														$content .= '<i class="fa fa-clock-o"></i>';
											  		$content .= '</div>';
											  		$content .= '<input type="text" class="form-control pull-right" name="startTime" id="datetime" value="' . date('Y-m-d H:i:s', $event->getStartTime()) . '" required>';
												$content .= '</div><!-- /.input group -->';
										  	$content .= '</div><!-- /.form group -->';
										  	$content .= '<div class="form-group">';
												$content .= '<label>Startdato og tid</label>';
												$content .= '<div class="input-group">';
											  		$content .= '<div class="input-group-addon">';
														$content .= '<i class="fa fa-clock-o"></i>';
											  		$content .= '</div>';
											  		$content .= '<input type="text" class="form-control pull-right" name="endTime" id="datetime" value="' . date('Y-m-d H:i:s', $event->getEndTime()) . '" required>';
												$content .= '</div><!-- /.input group -->';
										  	$content .= '</div><!-- /.form group -->';
										  	$content .= '<div class="btn-group" role="group" aria-label="...">';
										  		$content .= '<button type="submit" class="btn btn-primary">Endre</button>';

												if ($user->hasPermission('*')) {
													$currentEvent = EventHandler::getCurrentEvent();

													// Prevent users from removing events that have already started, we don't want to delete old tickets etc.
													if ($event->getBookingTime() >= $currentEvent->getBookingTime()) {
														$content .= '<button type="button" class="btn btn-primary" onClick="removeEvent(' . $event->getId() . ')">Fjern</button>';
													}

													// Allow user to transfer members from previus event if this event is the current one.
													if ($event->equals($currentEvent)) {
														$previousEvent = EventHandler::getPreviousEvent();

														$content .= '<button type="button" class="btn btn-primary" onClick="copyMembers(' . $previousEvent->getId() . ')">Kopier medlemmer fra "' . $previousEvent->getTitle() . '"</button>';
													}
												}

												$content .= '<button type="button" class="btn btn-primary" onClick="viewSeatmap(' . $event->getSeatmap()->getId() . ')">Vis setekart</button>';

											$content .= '</div>';
							  			$content .= '</form>';
									$content .= '</div><!-- /.box-body -->';
								$content .= '</div><!-- /.box -->';
							}
						} else {
							$content .= '<div class="box">';
								$content .= '<div class="box-body">';
									$content .= '<p>Det har ikke blitt opprettet noen arrangementer enda.</p>';
								$content .= '</div><!-- /.box-body -->';
							$content .= '</div><!-- /.box -->';
						}

					$content .= '</div><!--/.col (left) -->';
					$content .= '<div class="col-md-6">';
					  	$content .= '<div class="box">';
							$content .= '<div class="box-header">';
						  		$content .= '<h3 class="box-title">Legg til et nytt arrangement</h3>';
							$content .= '</div><!-- /.box-header -->';
							$content .= '<div class="box-body">';
								$content .= '<p>Fyll ut feltene under for å legge til en ny side.</p>';

								$content .= '<form class="admin-events-add" method="post">';
									$content .= '<div class="form-group">';
							  			$content .= '<label>Sted</label>';
							  			$content .= '<select class="form-control" name="location" required>';

							  				foreach (LocationHandler::getLocations() as $location) {
												$content .= '<option value="' . $location->getId() . '">' . $location->getTitle() . '</option>';
											}

										$content .= '</select>';
									$content .= '</div>';
									$content .= '<div class="form-group">';
							  			$content .= '<label>Anstall deltakere</label>';
										$content .= '<input type="number" class="form-control" name="participants" value="' . $event->getParticipants() . '" required>';
									$content .= '</div>';
									$content .= '<div class="form-group">';
										$content .= '<label>Billetsalgsdato og tid</label>';
										$content .= '<div class="input-group">';
									  		$content .= '<div class="input-group-addon">';
												$content .= '<i class="fa fa-clock-o"></i>';
									  		$content .= '</div>';
									  		$content .= '<input type="text" class="form-control pull-right" name="bookingTime" id="datetime" value="' . date('Y-m-d H:i:s', $event->getBookingTime()) . '" required>';
										$content .= '</div><!-- /.input group -->';
								  	$content .= '</div><!-- /.form group -->';
									$content .= '<div class="form-group">';
										$content .= '<label>Startdato og tid</label>';
										$content .= '<div class="input-group">';
									  		$content .= '<div class="input-group-addon">';
												$content .= '<i class="fa fa-clock-o"></i>';
									  		$content .= '</div>';
									  		$content .= '<input type="text" class="form-control pull-right" name="startTime" id="datetime" value="' . date('Y-m-d H:i:s', $event->getStartTime()) . '" required>';
										$content .= '</div><!-- /.input group -->';
								  	$content .= '</div><!-- /.form group -->';
								  	$content .= '<div class="form-group">';
										$content .= '<label>Startdato og tid</label>';
										$content .= '<div class="input-group">';
									  		$content .= '<div class="input-group-addon">';
												$content .= '<i class="fa fa-clock-o"></i>';
									  		$content .= '</div>';
									  		$content .= '<input type="text" class="form-control pull-right" name="endTime" id="datetime" value="' . date('Y-m-d H:i:s', $event->getEndTime()) . '" required>';
										$content .= '</div><!-- /.input group -->';
								  	$content .= '</div><!-- /.form group -->';
								  	$content .= '<button type="submit" class="btn btn-primary">Legg til</button>';
								$content .= '</form>';
							$content .= '</div><!-- /.box-body -->';
					  	$content .= '</div><!-- /.box -->';
					$content .= '</div><!--/.col (right) -->';
				$content .= '</div><!-- /.row -->';

				$content .= '<script src="scripts/admin-events.js"></script>';

				//<!-- jQuery 2.1.4 -->
				$content .= '<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>';
				//<!-- date-range-picker -->
				$content .= '<script src="plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>';
				//<!-- Page script -->
				$content .= '<script type="text/javascript">';
		  			$content .= '$(function() {';
						//Date range picker with time picker
						$content .= '$(\'#datetime\').daterangepicker({';
							$content .= 'timePicker: true,';
							$content .= 'timePickerSeconds: true,';
							$content .= 'format: \'YYYY-MM-DD HH:mm:ss\'';
						$content .= '});';
		  			$content .= '});';
				$content .= '</script>';
				$content .= '<script src="scripts/event-agenda.js"></script>';
			} else {
				$content .= '<div class="box">';
					$content .= '<div class="box-body">';
						$content .= '<p>Du har ikke rettigheter til dette!</p>';
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