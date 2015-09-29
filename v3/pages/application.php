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
require_once 'handlers/applicationhandler.php';
require_once 'interfaces/page.php';
require_once 'traits/page.php';

class ApplicationPage implements IPage {
	use TPage;

	public function getTitle() {
		return 'Søknad';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if ($user->hasPermission('chief.application') ||
				$user->isGroupLeader() ||
				$user->isGroupCoLeader()) {

				if (isset($_GET['applicationId'])) {
					$application = ApplicationHandler::getApplication($_GET['applicationId']);

					$content .= '<div class="row">';
						$content .= '<div class="col-md-6">';

							if ($application != null) {
								$applicationUser = $application->getUser();

								$content .= '<div class="box">';
									$content .= '<div class="box-header">';
								  		$content .= '<h3 class="box-title">Søknad fra ' . $applicationUser->getDisplayName() . '</h3>';
									$content .= '</div><!-- /.box-header -->';
									$content .= '<div class="box-body">';

										/*
										$applicationList = ApplicationHandler::getUserApplications($applicationUser);

										if (!empty($applicationList)) {
											$content .= '<p>Denne brukeren har også levert søknad til følgende crew:</p>';
											$content .= '<ul>';
												foreach ($applicationList as $applicationValue) {
													if (!$application->equals($applicationValue)) {
														$group = $applicationValue->getGroup();

														$content .= '<li><a href="index.php?page=application&id=' . $applicationValue->getId() . '">' . $group->getTitle() . '</a></li>';
													}
												}
											$content .= '</ul>';
										}
										*/

							  			$content .= '<table class="table table-bordered">';
											$content .= '<tr>';
												$content .= '<td><b>Status</b></td>';
												$content .= '<td>' . $application->getStateAsString() . '</td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td><b>Søkers navn</b></td>';
												$content .= '<td><a href="index.php?page=my-profile&id=' . $applicationUser->getId() . '">' . $applicationUser->getFullname(). '</a></td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td><b>Dato søkt</b></td>';
												$content .= '<td>' . date('d.m.Y H:i', $application->getOpenedTime()) . '</td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td><b>Crew</b></td>';
												$content .= '<td>' . $application->getGroup()->getTitle() . '</td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td><b>E-post</b></td>';
												$content .= '<td><a href="mailto:' . $applicationUser->getEmail() . '">' . $applicationUser->getEmail() . '</a></td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td><b>Telefon</b></td>';
												$content .= '<td>' . $applicationUser->getPhone() . '</td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td><b>Alder</b></td>';
												$content .= '<td>' . $applicationUser->getAge() . '</td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td><b>Søknad</b></td>';
												$content .= '<td>' . $application->getContent() . '</td>';
											$content .= '</tr>';
										$content .= '</table>';

										switch ($application->getState()) {
											case 1:
												$content .= '<form class="chief-applications-reject" method="post">';
													$content .= '<input type="hidden" name="applicationId" value="' . $application->getId() . '">';
													$content .= '<div class="form-group">';
													  	$content .= '<label>Begrunnelse for avslag</label>';
													  	$content .= '<textarea class="form-control" rows="3" name="content" placeholder="Skriv hvorfor du vil avslå her..."></textarea>';
													$content .= '</div><!-- /.form group -->';
													$content .= '<div class="btn-group" role="group" aria-label="...">';
														$content .= '<button type="button" class="btn btn-primary" onClick="acceptApplication(' . $application->getId() . ')">Godta</button>';
														$content .= '<button type="submit" class="btn btn-primary">Avslå</button>';

														if (!$application->isQueued()) {
															$content .= '<button type="button" class="btn btn-primary" onClick="queueApplication(' . $application->getId() . ')">Sett i kø</button>';
														} else {
															$content .= '<button type="button" class="btn btn-primary" onClick="unqueueApplication(' . $application->getId() . ')">Fjern fra kø</button>';
														}

													$content .= '</div>';
												$content .= '</form>';

												break;

											case 3:
												$content .= 'Begrunnelse for avslåelse: <i>' . $application->getComment() . '</i>';
												break;
										}

									$content .= '</div><!-- /.box-body -->';
								$content .= '</div><!-- /.box -->';
							}

						$content .= '</div><!--/.col (left) -->';
					$content .= '</div><!-- /.row -->';
				} else {
					$content .= '<div class="box">';
						$content .= '<div class="box-body">';
							$content .= '<p>Søknaden som ble spesifisert finnes ikke.</p>';
						$content .= '</div><!-- /.box-body -->';
					$content .= '</div><!-- /.box -->';
				}

				$content .= '<script src="scripts/application.js"></script>';
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
