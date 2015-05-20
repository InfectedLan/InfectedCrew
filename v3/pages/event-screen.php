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
require_once 'handlers/slidehandler.php';
require_once 'interfaces/page.php';

class EventScreenPage extends EventPage implements IPage {
	public function getTitle() {
		return 'Skjerm';
	}

	public function getContent() {
		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if ($user->isGroupMember()) {
				$group = $user->getGroup();
				
				if ($user->hasPermission('*') || 
					$user->hasPermission('event.screen')) {
					echo '<div class="row">';
						echo '<div class="col-md-6">';

							$slideList = SlideHandler::getSlides();
						
							if (!empty($slideList)) {
								foreach ($slideList as $slide) {
									echo '<div class="box">';
										echo '<div class="box-header">';
									  		echo '<h3 class="box-title">' . $slide->getTitle() . '</h3>';
										echo '</div><!-- /.box-header -->';
										echo '<div class="box-body">';
								  		
											echo '<form class="slide-edit" method="post">';
												echo '<input type="hidden" name="id" value="' . $slide->getId() . '">';
												echo '<div class="form-group">';
										  			echo '<label>Navn</label>';
										  			echo '<input type="text" class="form-control" name="title" placeholder="Skriv inn et navn her..." value="' . $slide->getTitle() . '" required>';
												echo '</div>';
												echo '<div class="form-group">';
												  	echo '<label>Beskrivelse</label>';
												  	echo '<textarea class="form-control" rows="3" name="description" placeholder="Skriv inn en beskrivese her..." required>';

												  		echo $slide->getContent();

												  	echo '</textarea>';
												echo '</div>';
												echo '<div class="form-group">';
													echo '<label>Tid og dato:</label>';
													echo '<div class="input-group">';
												  		echo '<div class="input-group-addon">';
															echo '<i class="fa fa-clock-o"></i>';
												  		echo '</div>';
												  		echo '<input type="text" class="form-control pull-right" name="datetime" id="datetime" value="' . date('Y-m-d H:i:s', $slide->getStartTime()) . '" required>';
													echo '</div><!-- /.input group -->';
											  	echo '</div><!-- /.form group -->';
											  	echo '<div class="form-group">';
							                    	echo '<label><input type="checkbox" class="minimal" checked> Publisert?</label>';
							                  	echo '</div>';
											  	echo '<button type="submit" class="pull-left btn btn-primary">Endre</button>';
								  			echo '</form>';
											echo '<button type="button" class="pull-right btn btn-primary" onClick="removeSlide(' . $slide->getId() . ')">Fjern</button>';
										echo '</div><!-- /.box-body -->';
									echo '</div><!-- /.box -->';

									echo '<script src="scripts/event-screen.js"></script>';
								}
							} else {
								echo '<div class="box box-warning">';
									echo '<div class="box-body">';
										echo '<p>Det har ikke blitt opprettet noen slides enda.</p>';
									echo '</div><!-- /.box-body -->';
								echo '</div><!-- /.box -->';
							}
						
						echo '</div><!--/.col (left) -->';
						echo '<div class="col-md-6">';
						  	echo '<div class="box">';
								echo '<div class="box-header">';
							  		echo '<h3 class="box-title">Legg til ny slide</h3>';
								echo '</div><!-- /.box-header -->';
								echo '<div class="box-body">';
							  		echo '<form class="slide-add" method="post">';
										echo '<div class="form-group">';
								  			echo '<label>Navn</label>';
								  			echo '<input type="text" class="form-control" name="title" placeholder="Skriv inn et navn her..." required>';
										echo '</div>';
										echo '<div class="form-group">';
										  	echo '<label>Tekst</label>';
										  	echo '<textarea class="form-control" rows="3" name="content" placeholder="Skriv inn en beskrivese her..." required></textarea>';
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
					echo '</div><!-- /.row -->';

					echo '<script>';
				    	echo '$(function () {';
				        	// iCheck for checkbox and radio inputs
					        echo '$(\'input[type="checkbox"].minimal, input[type="radio"].minimal\').iCheck({';
					        	echo 'checkboxClass: \'icheckbox_minimal-blue\'';
					        echo '});';
				      	echo '});';
				    echo '</script>';
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