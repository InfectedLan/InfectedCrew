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
		$id = isset($_GET['id']) ? $_GET['id'] : Session::getCurrentUser()->getId();

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			$profileUser = UserHandler::getUser($id);
			
			if ($profileUser != null) {
				if ($user->hasPermission('*') ||
					$user->hasPermission('search.users') ||
					$user->equals($profileUser)) {
					
					echo '<div class="row">';
						echo '<div class="col-md-6">';
						  	echo '<div class="box">';
								echo '<div class="box-header">';
							  		echo '<h3 class="box-title">Informasjon</h3>';
								echo '</div><!-- /.box-header -->';
								echo '<div class="box-body">';
									echo '<table class="table table-bordered">';

										if ($user->hasPermission('*')) {
											echo '<tr>';
												echo '<td><b>Id</b></td>';
												echo '<td>' . $profileUser->getId() . '</td>';
											echo '</tr>';
										}

										echo '<tr>';
											echo '<td><b>Navn</b></td>';
											echo '<td>' . $profileUser->getFullName() . '</td>';
										echo '</tr>';
										echo '<tr>';
											echo '<td><b>Brukernavn</b></td>';
											echo '<td>' . $profileUser->getUsername() . '</td>';
										echo '</tr>';
										echo '<tr>';
											echo '<td><b>E-post</b></td>';
											echo '<td><a href="mailto:' . $profileUser->getEmail() . '">' . $profileUser->getEmail() . '</a></td>';
										echo '</tr>';
										echo '<tr>';
											echo '<td><b>Fødselsdato</b></td>';
											echo '<td>' . date('d.m.Y', $profileUser->getBirthdate()) . '</td>';
										echo '</tr>';
										echo '<tr>';
											echo '<td><b>Kjønn</b></td>';
											echo '<td>' . $profileUser->getGenderAsString() . '</td>';
										echo '</tr>';
										echo '<tr>';
											echo '<td><b>Alder</b></td>';
											echo '<td>' . $profileUser->getAge() . ' år</td>';
										echo '</tr>';
										echo '<tr>';
											echo '<td><b>Telefon</b></td>';
											echo '<td>' . $profileUser->getPhoneAsString() . '</td>';
										echo '</tr>';
										echo '<tr>';
											echo '<td><b>Adresse</b></td>';

											$address = $profileUser->getAddress();
											$postalCode = $profileUser->getPostalCode();

											echo '<td>';

												echo (!empty($address) ? $address : '<i>Ikke oppgitt</i>');

												if ($postalCode != 0) {
													echo '<br>';
													echo $postalCode . ' ' . $profileUser->getCity();
												}

											echo '</td>';
										echo '</tr>';
										echo '<tr>';
											echo '<td><b>Kallenavn</b></td>';
											echo '<td>' . $profileUser->getNickname() . '</td>';
										echo '</tr>';
										
										if ($profileUser->hasEmergencyContact()) {
											echo '<tr>';
												echo '<td><b>Foresatte\'s telefon</b></td>';
												echo '<td>' . $profileUser->getEmergencyContact()->getPhoneAsString() . '</td>';
											echo '</tr>';
										}

										if ($user->hasPermission('*') ||
											$user->equals($profileUser)) {
											echo '<tr>';
												echo '<td><b>Dato registrert</b></td>';
												echo '<td>' . date('d.m.Y', $profileUser->getRegisteredDate()) . '</td>';
											echo '</tr>';
										}

										if ($user->hasPermission('*')) {
											echo '<tr>';
												echo '<td><b>Aktivert</b></td>';
												echo '<td>' . ($profileUser->isActivated() ? 'Ja' : 'Nei') . '</td>';
											echo '</tr>';
										}
										
										if ($profileUser->isGroupMember()) {
											echo '<tr>';
												echo '<td><b>Crew</b></td>';
												echo '<td>';

													if ($profileUser->isGroupMember()) {
														echo $profileUser->getGroup()->getTitle();
													} else {
														echo '<i>Ingen</i>';
													}

												echo '</td>';
											echo '</tr>';
											
											if ($profileUser->isTeamMember()) {
												echo '<tr>';
													echo '<td><b>Lag</b></td>';
													echo '<td>' . $profileUser->getTeam()->getTitle() . '</td>';
												echo '</tr>';
											}	
										}

										if ($profileUser->hasTicket()) {
											$ticketList = $profileUser->getTickets();
											$ticketCount = count($ticketList);
											sort($ticketList);

											echo '<tr>';
												echo '<td><b>' . (count($ticketList) > 1 ? 'Billetter' : 'Billett') . '</b></td>';
												echo '<td>';

													foreach ($ticketList as $ticket) {
														echo '<a href="index.php?page=ticket&id=' . $ticket->getId() . '">#' . $ticket->getId() . '</a>';

														// Only print comma if this is not the last ticket in the array.
														echo $ticket !== end($ticketList) ? ', ' : ' (' . $ticketCount . ')';
													}

												echo '</td>';
											echo '</tr>';
										}

										if ($profileUser->hasTicket() &&
											$profileUser->hasSeat()) {
											$ticket = $profileUser->getTicket();
											
											echo '<tr>';
												echo '<td><b>Plass</b></td>';
												echo '<td>' . $ticket->getSeat()->getString() . '</td>';
											echo '</tr>';
										}
										
										if ($user->hasPermission('*') ||
											$user->equals($profileUser)) {
											echo '<tr>';
												echo '<td></td>';
												echo '<td><a href="index.php?page=edit-profile&id=' . $profileUser->getId() . '">Endre bruker</a></td>';
											echo '</tr>';
										}
										
										if ($user->equals($profileUser)) {
											echo '<tr>';
												echo '<td></td>';
												echo '<td><a href="index.php?page=edit-avatar">Endre avatar</a></td>';
											echo '</tr>';
										}
											
										if ($user->hasPermission('*') ||
											$user->hasPermission('admin.permissions')) {
											echo '<tr>';
												echo '<td></td>';
												echo '<td><a href="index.php?page=admin-permissions&id=' . $profileUser->getId() . '">Endre rettigheter</a></td>';
											echo '</tr>';
											echo '<tr>';
												echo '<td></td>';
												echo '<td><a href="index.php?page=user-history">Vis historie</a></td>';
											echo '</tr>';
										}

										if ($user->hasPermission('*') ||
											$user->hasPermission('admin.permissions')) {

											if (!$profileUser->isGroupMember()) {
												echo '<tr>';
													echo '<td></td>';
													echo '<td>';
														echo '<form class="my-profile-group-add-user" method="post">';
															echo '<input type="hidden" name="userId" value="' . $profileUser->getId() . '">';
															echo '<select class="chosen-select" name="groupId">';
																foreach (GroupHandler::getGroups() as $group) {
																	echo '<option value="' . $group->getId() . '">' . $group->getTitle() . '</option>';
																}
															echo '</select> ';
															echo '<input type="submit" value="Legg til i crew">';
														echo '</form>';
													echo '</td>';
												echo '</tr>';
											}
										}

									echo '</table>';
								echo '</div><!-- /.box-body -->';
						  	echo '</div><!-- /.box -->';
						echo '</div><!--/.col (left) -->';
						echo '<div class="col-md-6">';
						  	echo '<div class="box">';
						  		echo '<div class="box-header">';
							  		echo '<h3 class="box-title">Profilbilde</h3>';
								echo '</div><!-- /.box-header -->';
								echo '<div class="box-body">';
							  		
									$avatarFile = null;
									
									if ($profileUser->hasValidAvatar()) {
										$avatarFile = $profileUser->getAvatar()->getHd();
									} else {
										$avatarFile = $profileUser->getDefaultAvatar();
									}
									
				  					echo '<img src="../api/' . $avatarFile . '" class="img-circle" alt="' . $user->getDisplayName() . '\'s profilbilde">';

								echo '</div><!-- /.box-body -->';
						  	echo '</div><!-- /.box -->';
						echo '</div><!--/.col (right) -->';
					echo '</div><!-- /.row -->';

					/*
					if (($user->hasPermission('*') ||
						$user->hasPermission('search.users') ||
						$user->hasPermission('chief.tickets')) && // TODO: Verify this permission. 
						$profileUser->hasTicket()) {
						$ticket = $profileUser->getTicket();

						echo '<link rel="stylesheet" href="../api/styles/seatmap.css">';
						

						echo '<div class="box">';
							echo '<div class="box-header">';
						  		echo '<h3 class="box-title">Omplasser bruker</h3>';
							echo '</div><!-- /.box-header -->';
							echo '<div class="box-body">';
								echo '<div id="seatmapCanvas"></div>';
							echo '</div><!-- /.box-body -->';
						echo '</div><!-- /.box -->';
						
						echo '<script src="../api/scripts/seatmapRenderer.js"></script>';
						echo '<script>';
							echo 'var seatmapId = ' . $ticket->getEvent()->getSeatmap()->getId() . ';';
							echo 'var ticketId = ' . $ticket->getId() . ';';
							echo '$(document).ready(function() {';
								echo 'downloadAndRenderSeatmap("#seatmapCanvas", seatHandlerFunction, callback);';
							echo '});';
						echo '</script>';
					}
					*/

					echo '<script src="scripts/my-profile.js"></script>';
				} else {
					echo '<div class="box">';
						echo '<div class="box-body">';
							echo '<p>Du har ikke rettigehter til dette.</p>';
						echo '</div><!-- /.box-body -->';
					echo '</div><!-- /.box -->';
				}
			} else {
				echo '<div class="box">';
					echo '<div class="box-body">';
						echo '<p>Brukeren du ser etter finnes ikke.</p>';
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