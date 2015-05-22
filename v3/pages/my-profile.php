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
require_once 'handlers/grouphandler.php';
require_once 'handlers/avatarhandler.php';
require_once 'interfaces/page.php';
require_once 'traits/page.php';

class MyProfilePage implements IPage {
	use Page;

	public function getTitle() {
		$id = isset($_GET['id']) ? $_GET['id'] : Session::getCurrentUser()->getId();

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			$profileUser = UserHandler::getUser($id);
			
			if ($profileUser != null) {
				if ($user->equals($profileUser)) {
					return 'Min profil';
				} else if ($user->hasPermission('*') ||
					$user->hasPermission('search.users')) {
					return $profileUser->getDisplayName() . '\'s profile';
				}
			}
		}

		return 'Profil';
	}

	public function getContent() {
		$content = null;
		$id = isset($_GET['id']) ? $_GET['id'] : Session::getCurrentUser()->getId();

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			$profileUser = UserHandler::getUser($id);
			
			if ($profileUser != null) {
				if ($user->hasPermission('*') ||
					$user->hasPermission('search.users') ||
					$user->equals($profileUser)) {
					
					$content .= '<div class="row">';
						$content .= '<div class="col-md-6">';
						  	$content .= '<div class="box">';
								$content .= '<div class="box-header">';
							  		$content .= '<h3 class="box-title">Informasjon</h3>';
								$content .= '</div><!-- /.box-header -->';
								$content .= '<div class="box-body">';
									$content .= '<table class="table table-bordered">';

										if ($user->hasPermission('*')) {
											$content .= '<tr>';
												$content .= '<td><b>Id</b></td>';
												$content .= '<td>' . $profileUser->getId() . '</td>';
											$content .= '</tr>';
										}

										$content .= '<tr>';
											$content .= '<td><b>Navn</b></td>';
											$content .= '<td>' . $profileUser->getFullName() . '</td>';
										$content .= '</tr>';
										$content .= '<tr>';
											$content .= '<td><b>Brukernavn</b></td>';
											$content .= '<td>' . $profileUser->getUsername() . '</td>';
										$content .= '</tr>';
										$content .= '<tr>';
											$content .= '<td><b>E-post</b></td>';
											$content .= '<td><a href="mailto:' . $profileUser->getEmail() . '">' . $profileUser->getEmail() . '</a></td>';
										$content .= '</tr>';
										$content .= '<tr>';
											$content .= '<td><b>Fødselsdato</b></td>';
											$content .= '<td>' . date('d.m.Y', $profileUser->getBirthdate()) . '</td>';
										$content .= '</tr>';
										$content .= '<tr>';
											$content .= '<td><b>Kjønn</b></td>';
											$content .= '<td>' . $profileUser->getGenderAsString() . '</td>';
										$content .= '</tr>';
										$content .= '<tr>';
											$content .= '<td><b>Alder</b></td>';
											$content .= '<td>' . $profileUser->getAge() . ' år</td>';
										$content .= '</tr>';
										$content .= '<tr>';
											$content .= '<td><b>Telefon</b></td>';
											$content .= '<td>' . $profileUser->getPhoneAsString() . '</td>';
										$content .= '</tr>';
										$content .= '<tr>';
											$content .= '<td><b>Adresse</b></td>';

											$address = $profileUser->getAddress();
											$postalCode = $profileUser->getPostalCode();

											$content .= '<td>';

												$content .= (!empty($address) ? $address : '<i>Ikke oppgitt</i>');

												if ($postalCode != 0) {
													$content .= '<br>';
													$content .= $postalCode . ' ' . $profileUser->getCity();
												}

											$content .= '</td>';
										$content .= '</tr>';
										$content .= '<tr>';
											$content .= '<td><b>Kallenavn</b></td>';
											$content .= '<td>' . $profileUser->getNickname() . '</td>';
										$content .= '</tr>';
										
										if ($profileUser->hasEmergencyContact()) {
											$content .= '<tr>';
												$content .= '<td><b>Foresatte\'s telefon</b></td>';
												$content .= '<td>' . $profileUser->getEmergencyContact()->getPhoneAsString() . '</td>';
											$content .= '</tr>';
										}

										if ($user->hasPermission('*') ||
											$user->equals($profileUser)) {
											$content .= '<tr>';
												$content .= '<td><b>Dato registrert</b></td>';
												$content .= '<td>' . date('d.m.Y', $profileUser->getRegisteredDate()) . '</td>';
											$content .= '</tr>';
										}

										if ($user->hasPermission('*')) {
											$content .= '<tr>';
												$content .= '<td><b>Aktivert</b></td>';
												$content .= '<td>' . ($profileUser->isActivated() ? 'Ja' : 'Nei') . '</td>';
											$content .= '</tr>';
										}
										
										if ($profileUser->isGroupMember()) {
											$content .= '<tr>';
												$content .= '<td><b>Crew</b></td>';
												$content .= '<td>';

													if ($profileUser->isGroupMember()) {
														$content .= $profileUser->getGroup()->getTitle();
													} else {
														$content .= '<i>Ingen</i>';
													}

												$content .= '</td>';
											$content .= '</tr>';
											
											if ($profileUser->isTeamMember()) {
												$content .= '<tr>';
													$content .= '<td><b>Lag</b></td>';
													$content .= '<td>' . $profileUser->getTeam()->getTitle() . '</td>';
												$content .= '</tr>';
											}	
										}

										if ($profileUser->hasTicket()) {
											$ticketList = $profileUser->getTickets();
											$ticketCount = count($ticketList);
											sort($ticketList);

											$content .= '<tr>';
												$content .= '<td><b>' . (count($ticketList) > 1 ? 'Billetter' : 'Billett') . '</b></td>';
												$content .= '<td>';

													foreach ($ticketList as $ticket) {
														$content .= '<a href="index.php?page=ticket&id=' . $ticket->getId() . '">#' . $ticket->getId() . '</a>';

														// Only print comma if this is not the last ticket in the array.
														$content .= $ticket !== end($ticketList) ? ', ' : ' (' . $ticketCount . ')';
													}

												$content .= '</td>';
											$content .= '</tr>';
										}

										if ($profileUser->hasTicket() &&
											$profileUser->hasSeat()) {
											$ticket = $profileUser->getTicket();
											
											$content .= '<tr>';
												$content .= '<td><b>Plass</b></td>';
												$content .= '<td>' . $ticket->getSeat()->getString() . '</td>';
											$content .= '</tr>';
										}

										if ($user->hasPermission('*') ||
											$user->hasPermission('admin.permissions')) {

											if (!$profileUser->isGroupMember()) {
												$content .= '<tr>';
													$content .= '<td></td>';
													$content .= '<td>';
														$content .= '<form class="my-profile-group-add-user" method="post">';
															$content .= '<input type="hidden" name="userId" value="' . $profileUser->getId() . '">';
															$content .= '<select class="chosen-select" name="groupId">';
																foreach (GroupHandler::getGroups() as $group) {
																	$content .= '<option value="' . $group->getId() . '">' . $group->getTitle() . '</option>';
																}
															$content .= '</select> ';
															$content .= '<input type="submit" value="Legg til i crew">';
														$content .= '</form>';
													$content .= '</td>';
												$content .= '</tr>';
											}
										}

									$content .= '</table>';
								$content .= '</div><!-- /.box-body -->';
						  	$content .= '</div><!-- /.box -->';
						$content .= '</div><!--/.col (left) -->';
						
						$content .= '<div class="col-md-6">';
						  	$content .= '<div class="box">';
						  		$content .= '<div class="box-header">';
							  		$content .= '<h3 class="box-title">Profilbilde</h3>';
								$content .= '</div><!-- /.box-header -->';
								$content .= '<div class="box-body">';
							  		
									$avatarFile = null;
									
									if ($profileUser->hasValidAvatar()) {
										$avatarFile = $profileUser->getAvatar()->getHd();
									} else {
										$avatarFile = $profileUser->getDefaultAvatar();
									}
									
				  					$content .= '<img src="../api/' . $avatarFile . '" class="img-circle" alt="' . $user->getDisplayName() . '\'s profilbilde">';

								$content .= '</div><!-- /.box-body -->';
						  	$content .= '</div><!-- /.box -->';
						$content .= '</div><!--/.col (right) -->';
					$content .= '</div><!-- /.row -->';
					$content .= '<div class="row">';
						$content .= '<div class="col-md-6">';
							$content .= '<div class="box">';
								$content .= '<div class="box-body">';

									if ($user->hasPermission('*') ||
										$user->hasPermission('admin.permissions') ||
										$user->equals($profileUser)) {

										$content .= '<ul class="nav nav-pills">';
											if ($user->hasPermission('*') ||
												$user->equals($profileUser)) {
												$content .= '<li role="presentation"><a href="index.php?page=edit-profile&id=' . $profileUser->getId() . '">Endre bruker</a></li>';
											}
											
											if ($user->equals($profileUser)) {
												$content .= '<li role="presentation"><a href="index.php?page=edit-avatar">Endre avatar</a></li>';
											}
												
											if ($user->hasPermission('*') ||
												$user->hasPermission('admin.permissions')) {
												$content .= '<li role="presentation"><a href="index.php?page=admin-permissions&id=' . $profileUser->getId() . '">Endre rettigheter</a></li>';
												$content .= '<li role="presentation"><a href="index.php?page=user-history">Vis historie</a></li>';
											}
										$content .= '</ul>';
									}

								$content .= '</div><!-- /.box-body -->';
						  	$content .= '</div><!-- /.box -->';
						$content .= '</div><!--/.col (left) -->';
					$content .= '</div><!-- /.row -->';

					/*
					if (($user->hasPermission('*') ||
						$user->hasPermission('search.users') ||
						$user->hasPermission('chief.tickets')) && // TODO: Verify this permission. 
						$profileUser->hasTicket()) {
						$ticket = $profileUser->getTicket();

						$content .= '<link rel="stylesheet" href="../api/styles/seatmap.css">';
						

						$content .= '<div class="box">';
							$content .= '<div class="box-header">';
						  		$content .= '<h3 class="box-title">Omplasser bruker</h3>';
							$content .= '</div><!-- /.box-header -->';
							$content .= '<div class="box-body">';
								$content .= '<div id="seatmapCanvas"></div>';
							$content .= '</div><!-- /.box-body -->';
						$content .= '</div><!-- /.box -->';
						
						$content .= '<script src="../api/scripts/seatmapRenderer.js"></script>';
						$content .= '<script>';
							$content .= 'var seatmapId = ' . $ticket->getEvent()->getSeatmap()->getId() . ';';
							$content .= 'var ticketId = ' . $ticket->getId() . ';';
							$content .= '$(document).ready(function() {';
								$content .= 'downloadAndRenderSeatmap("#seatmapCanvas", seatHandlerFunction, callback);';
							$content .= '});';
						$content .= '</script>';
					}
					*/

					$content .= '<script src="scripts/my-profile.js"></script>';
				} else {
					$content .= '<div class="box">';
						$content .= '<div class="box-body">';
							$content .= '<p>Du har ikke rettigehter til dette.</p>';
						$content .= '</div><!-- /.box-body -->';
					$content .= '</div><!-- /.box -->';
				}
			} else {
				$content .= '<div class="box">';
					$content .= '<div class="box-body">';
						$content .= '<p>Brukeren du ser etter finnes ikke.</p>';
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