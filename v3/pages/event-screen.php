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
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if ($user->hasPermission('event.screen')) {
				$content .= '<div class="row">';
					$content .= '<div class="col-md-6">';

						$slideList = SlideHandler::getSlides();

						if (!empty($slideList)) {
							foreach ($slideList as $slide) {
								$content .= '<div class="box">';
									$content .= '<div class="box-header">';
								  		$content .= '<h3 class="box-title">' . $slide->getTitle() . '</h3>';
									$content .= '</div><!-- /.box-header -->';
									$content .= '<div class="box-body">';

										$content .= '<form class="slide-edit" method="post">';
											$content .= '<input type="hidden" name="id" value="' . $slide->getId() . '">';
											$content .= '<div class="form-group">';
									  			$content .= '<label>Navn</label>';
									  			$content .= '<input type="text" class="form-control" name="title" placeholder="Skriv inn et navn her..." value="' . $slide->getTitle() . '" required>';
											$content .= '</div>';
											$content .= '<div class="form-group">';
											  	$content .= '<label>Beskrivelse</label>';
											  	$content .= '<textarea class="form-control" rows="3" name="description" placeholder="Skriv inn en beskrivese her..." required>';

											  		$content .= $slide->getContent();

											  	$content .= '</textarea>';
											$content .= '</div>';
											$content .= '<div class="form-group">';
												$content .= '<label>Tid og dato:</label>';
												$content .= '<div class="input-group">';
											  		$content .= '<div class="input-group-addon">';
														$content .= '<i class="fa fa-clock-o"></i>';
											  		$content .= '</div>';
											  		$content .= '<input type="text" class="form-control pull-right" name="datetime" id="datetime" value="' . date('Y-m-d H:i:s', $slide->getStartTime()) . '" required>';
												$content .= '</div><!-- /.input group -->';
										  	$content .= '</div><!-- /.form group -->';
										  	$content .= '<div class="form-group">';
						                    	$content .= '<label><input type="checkbox" class="minimal" checked> Publisert?</label>';
						                  	$content .= '</div>';
						                  	$content .= '<div class="btn-group" role="group" aria-label="...">';
												$content .= '<button type="submit" class="btn btn-primary">Endre</button>';
												$content .= '<button type="button" class="btn btn-primary" onClick="removeSlide(' . $slide->getId() . ')">Fjern</button>';
											$content .= '</div>';
							  			$content .= '</form>';
									$content .= '</div><!-- /.box-body -->';
								$content .= '</div><!-- /.box -->';
							}
						} else {
							$content .= '<div class="box">';
								$content .= '<div class="box-body">';
									$content .= '<p>Det har ikke blitt opprettet noen slides enda.</p>';
								$content .= '</div><!-- /.box-body -->';
							$content .= '</div><!-- /.box -->';
						}

					$content .= '</div><!--/.col (left) -->';
					$content .= '<div class="col-md-6">';
					  	$content .= '<div class="box">';
							$content .= '<div class="box-header">';
						  		$content .= '<h3 class="box-title">Legg til ny slide</h3>';
							$content .= '</div><!-- /.box-header -->';
							$content .= '<div class="box-body">';
						  		$content .= '<form class="slide-add" method="post">';
									$content .= '<div class="form-group">';
							  			$content .= '<label>Navn</label>';
							  			$content .= '<input type="text" class="form-control" name="title" placeholder="Skriv inn et navn her..." required>';
									$content .= '</div>';
									$content .= '<div class="form-group">';
									  	$content .= '<label>Tekst</label>';
									  	$content .= '<textarea class="form-control" rows="3" name="content" placeholder="Skriv inn en beskrivese her..." required></textarea>';
									$content .= '</div><!-- /.form group -->';
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

				$content .= '<script src="scripts/event-screen.js"></script>';

				$content .= '<script>';
			    	$content .= '$(function () {';
			        	// iCheck for checkbox and radio inputs
				        $content .= '$(\'input[type="checkbox"].minimal, input[type="radio"].minimal\').iCheck({';
				        	$content .= 'checkboxClass: \'icheckbox_minimal-blue\'';
				        $content .= '});';
			      	$content .= '});';
			    $content .= '</script>';
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
