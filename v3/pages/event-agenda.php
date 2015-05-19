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
		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if ($user->isGroupMember()) {
				$group = $user->getGroup();
				
				if ($user->hasPermission('*') || 
					$user->hasPermission('event.agenda')) {
					echo '<script src="scripts/event-agenda.js"></script>';

					echo '<div class="row">';
						echo '<div class="col-md-6">';
			              	echo '<div class="box box-warning">';
			                	echo '<div class="box-header">';
			                  		echo '<h3 class="box-title">Agenda liste</h3>';
			                	echo '</div><!-- /.box-header -->';
			                	echo '<div class="box-body">';
			                  		
			                		$agendaList = AgendaHandler::getAgendas();
					
									if (!empty($agendaList)) {
										echo '<table>';
											echo '<tr>';
												echo '<th>Navn:</th>';
												echo '<th>Informasjon:</th>';
												echo '<th>Tid:</th>';
											echo '</tr>';
											
											// TODO: Fix forms for this thing.

											foreach ($agendaList as $agenda) {
												echo '<tr>';
													echo '<form class="agenda-edit" method="post">';
														echo '<input type="hidden" name="id" value="' . $agenda->getId() . '">';
														echo '<td><input type="text" name="title" value="' . $agenda->getTitle() . '"></td>';
														echo '<td><textarea name="description">' . $agenda->getDescription() . '</textarea></td>';
														echo '<td>';
															echo '<input type="time" name="startTime" value="' . date('H:i', $agenda->getStartTime()) . '">';
															echo '<br>';
															echo '<input type="date" name="startDate" value="' . date('Y-m-d', $agenda->getStartTime()) . '">';
														echo '</td>';
														
														if ($agenda->isPublished()) {
															echo '<td><input type="checkbox" name="published" value="1" checked></td>';
														} else {
															echo '<td><input type="checkbox" name="published" value="1"></td>';
														}
														
														echo '<td><input type="submit" value="Endre"></td>';
													echo '</form>';
													echo '<td><input type="button" value="Fjern" onClick="removeAgenda(' . $agenda->getId() . ')"></td>';
												echo '</tr>';
											}
										echo '</table>';
									} else {
										echo '<p>Det er ikke opprettet noen agenda\'er enda.';
									}

			                	echo '</div><!-- /.box-body -->';
			              	echo '</div><!-- /.box -->';
			            echo '</div><!--/.col (right) -->';
						echo '<div class="col-md-6">';
			              	//<!-- general form elements disabled -->
			              	echo '<div class="box box-warning">';
			                	echo '<div class="box-header">';
			                  		echo '<h3 class="box-title">Legg til ny agenda</h3>';
			                	echo '</div><!-- /.box-header -->';
			                	echo '<div class="box-body">';
			                  		echo '<form class="agenda-add" method="post">';
			                    		echo '<div class="form-group">';
			                      			echo '<label>Navn</label>';
			                      			echo '<input type="text" class="form-control" name="title" placeholder="Skriv inn et navn her..." required>';
			                    		echo '</div>';
					                    echo '<div class="form-group">';
					                      	echo '<label>Beskrivelse</label>';
					                      	echo '<textarea class="form-control" rows="3" name="description" placeholder="Skriv inn en beskrivese her..." required></textarea>';
					                    echo '</div>';
					                    echo '<div class="form-group">';
					                    	echo '<label>Tid og dato:</label>';
					                    	echo '<div class="input-group">';
					                      		echo '<div class="input-group-addon">';
					                        		echo '<i class="fa fa-clock-o"></i>';
					                      		echo '</div>';
					                      		echo '<input type="text" class="form-control pull-right" id="datetime" required>';
					                    	echo '</div><!-- /.input group -->';
					                  	echo '</div><!-- /.form group -->';
					                  	echo '<button type="submit" class="btn btn-primary">Legg til</button>';
			                  		echo '</form>';
			                	echo '</div><!-- /.box-body -->';
			              	echo '</div><!-- /.box -->';
			            echo '</div><!--/.col (right) -->';
			        echo '</div>   <!-- /.row -->';

			        //<!-- jQuery 2.1.4 -->
				    echo '<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>';
					//<!-- date-range-picker -->
		    		echo '<script src="plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>';
		    		//<!-- Page script -->
		    		echo '<script type="text/javascript">';
		      			echo '$(function() {';
		        			//Date range picker with time picker
		        			echo '$(\'#datetime\').daterangepicker({';
		        				echo 'timePicker: true,';
		        				echo 'timePickerSeconds: true,';
		        				echo 'format: \'YYYY-MM-DD HH:mm:ss\'';
		        			echo '});';
		      			echo '});';
		    		echo '</script>';
				} else {
					echo '<p>Du har ikke rettigheter til dette!</p>';
				}
			} else {
				echo '<p>Du er ikke medlem av en gruppe.</p>';
			}
		} else {
			echo '<p>Du er ikke logget inn!</p>';
		}
	}
}
?>