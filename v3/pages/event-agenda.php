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
require_once 'handlers/agendahandler.php';
require_once 'interfaces/page.php';

class EventAgendaPage extends EventPage implements IPage {
	public function getTitle() {
		return 'Agenda';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if ($user->isGroupMember()) {
				$group = $user->getGroup();
				
				if ($user->hasPermission('*') || 
					$user->hasPermission('event.agenda')) {
					$content .= '<div class="row">';
						$content .= '<div class="col-md-6">';
							
							$agendaList = AgendaHandler::getAgendas();
							
							if (!empty($agendaList)) {
								foreach ($agendaList as $agenda) {
								  	$content .= '<div class="box">';
										$content .= '<div class="box-header">';
									  		$content .= '<h3 class="box-title">' . $agenda->getTitle() . '</h3>';
										$content .= '</div><!-- /.box-header -->';
										$content .= '<div class="box-body">';
								  		
											$content .= '<form class="agenda-edit" method="post">';
												$content .= '<input type="hidden" name="id" value="' . $agenda->getId() . '">';
												$content .= '<div class="form-group">';
										  			$content .= '<label>Navn</label>';
										  			$content .= '<input type="text" class="form-control" name="title" placeholder="Skriv inn et navn her..." value="' . $agenda->getTitle() . '" required>';
												$content .= '</div>';
												$content .= '<div class="form-group">';
												  	$content .= '<label>Beskrivelse</label>';
												  	$content .= '<textarea class="form-control" rows="3" name="description" placeholder="Skriv inn en beskrivese her..." required>';

												  		$content .= $agenda->getDescription();

												  	$content .= '</textarea>';
												$content .= '</div>';
												$content .= '<div class="form-group">';
													$content .= '<label>Tid og dato:</label>';
													$content .= '<div class="input-group">';
												  		$content .= '<div class="input-group-addon">';
															$content .= '<i class="fa fa-clock-o"></i>';
												  		$content .= '</div>';
												  		$content .= '<input type="text" class="form-control pull-right" name="datetime" id="datetime" value="' . date('Y-m-d H:i:s', $agenda->getStartTime()) . '" required>';
													$content .= '</div><!-- /.input group -->';
											  	$content .= '</div><!-- /.form group -->';
											  	$content .= '<button type="submit" class="pull-left btn btn-primary">Endre</button>';
								  			$content .= '</form>';
											$content .= '<button type="button" class="pull-right btn btn-primary" onClick="removeAgenda(' . $agenda->getId() . ')">Fjern</button>';
										$content .= '</div><!-- /.box-body -->';
									$content .= '</div><!-- /.box -->';
								}
							} else {
								$content .= '<div class="box">';
									$content .= '<div class="box-body">';
										$content .= '<p>Det har ikke blitt opprettet noen agenda\'er enda.</p>';
									$content .= '</div><!-- /.box-body -->';
								$content .= '</div><!-- /.box -->';
							}

						$content .= '</div><!--/.col (left) -->';
						$content .= '<div class="col-md-6">';
						  	$content .= '<div class="box">';
								$content .= '<div class="box-header">';
							  		$content .= '<h3 class="box-title">Legg til ny agenda</h3>';
								$content .= '</div><!-- /.box-header -->';
								$content .= '<div class="box-body">';
							  		$content .= '<form class="agenda-add" method="post">';
										$content .= '<div class="form-group">';
								  			$content .= '<label>Navn</label>';
								  			$content .= '<input type="text" class="form-control" name="title" placeholder="Skriv inn et navn her..." required>';
										$content .= '</div>';
										$content .= '<div class="form-group">';
										  	$content .= '<label>Beskrivelse</label>';
										  	$content .= '<textarea class="form-control" rows="3" name="description" placeholder="Skriv inn en beskrivese her..." required></textarea>';
										$content .= '</div>';
										$content .= '<div class="form-group">';
											$content .= '<label>Tid og dato:</label>';
											$content .= '<div class="input-group">';
										  		$content .= '<div class="input-group-addon">';
													$content .= '<i class="fa fa-clock-o"></i>';
										  		$content .= '</div>';
										  		$content .= '<input type="text" class="form-control pull-right" id="datetime" required>';
											$content .= '</div><!-- /.input group -->';
									  	$content .= '</div><!-- /.form group -->';
									  	$content .= '<button type="submit" class="btn btn-primary">Legg til</button>';
							  		$content .= '</form>';
								$content .= '</div><!-- /.box-body -->';
						  	$content .= '</div><!-- /.box -->';
						$content .= '</div><!--/.col (right) -->';
					$content .= '</div><!-- /.row -->';

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
						$content .= '<p>Du er ikke medlem av en gruppe.</p>';
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