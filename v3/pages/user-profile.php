<?php
/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2017 Infected <http://infected.no/>.
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
require_once 'handlers/userhistoryhandler.php';
require_once 'page.php';

class UserProfilePage extends Page {
	public function getTitle(): string {
		$id = isset($_GET['id']) ?? Session::getCurrentUser()->getId();

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			$profileUser = UserHandler::getUser($id);

			if ($profileUser != null) {
				if ($user->equals($profileUser)) {
					return 'Min profil';
				} else {
					return $profileUser->getFullName() . '\'s profil';
				}
			}
		}

		return 'Profil';
	}

	public function getContent(): string {
		$content = null;
		$id = isset($_GET['id']) ? $_GET['id'] : Session::getCurrentUser()->getId();

		if (Session::isAuthenticated()) {
            echo '<script src="scripts/user-profile.js"></script>';
			$user = Session::getCurrentUser();
			$profileUser = UserHandler::getUser($id);

			if ($profileUser != null) {
				if ($user->hasPermission('user.search') ||
					$user->equals($profileUser)) {
					$avatarFile = null;

					if ($profileUser->hasValidAvatar()) {
						$avatarFile = $profileUser->getAvatar()->getHd();
					} else {
						$avatarFile = $profileUser->getDefaultAvatar();
					}


          $content .= '<div class="row">';
            $content .= '<div class="col-md-3">';

              //<!-- Profile Image -->
              $content .= '<div class="box box-primary">';
                $content .= '<div class="box-body box-profile">';
                  $content .= '<img class="profile-user-img img-responsive img-circle" src="../api/' . $avatarFile . '" alt="' . $profileUser->getFullName() . '\'s profilbilde">';
									//$content .= '<img class="profile-user-img img-responsive img-circle" src="https://crew.test.infected.no/v3/demo/dist/img/user4-128x128.jpg" alt="' . $profileUser->getFullName() . '\'s profilbilde">';
									$content .= '<h3 class="profile-username text-center">' . $profileUser->getFullName() . '</h3>';
									$content .= '<p class="text-muted text-center">' . $profileUser->getRole() . '</p>';
                $content .= '</div><!-- /.box-body -->';
              $content .= '</div><!-- /.box -->';
              if ($user->equals($profileUser) ||
                  $user->hasPermission('user.relocate') ||
                  $user->hasPermission('user.edit') ||
                  $user->hasPermission('admin.permissions')) {
              $content .= '<!-- Settings -->';
              $content .= '<div class="box box-primary">';
                $content .= '<div class="box-header with-border">';
                  $content .= '<h3 class="box-title">Innstillinger</h3>';
                $content .= '</div><!-- /.box-header -->';
                $content .= '<div class="box-body">';
                if($user->hasPermission('user.edit') ||
                   $user->equals($profileUser)) {
                    $content .= '<p><a href="index.php?page=edit-profile&id=' . $profileUser->getId() . '">Endre bruker</a></p>';
                }
                if($user->hasPermission('user.relocate') ||
                   $user->equals($profileUser)) {
                    $content .= '<p><a href="index.php?page=edit-user-location">Endre plassering</a></p>';
                }
                if($user->hasPermission('admin.permissions')) {
                    $content .= '<p><a href="index.php?page=admin-permissions&id=' . $profileUser->getId() . '">Endre rettigheter</a></p>';
                }
                if($user->equals($profileUser)) {
                    $content .= '<p><a href="index.php?page=edit-avatar">Endre avatar</a></p>';
                }
                $content .= '</div><!-- /.box-body -->';
              $content .= '</div><!-- /.box -->';
              }
              if ($user->hasPermission('user.note')) {
              $content .= '<!-- Notes -->';
              $content .= '<div class="box box-primary">';
                $content .= '<div class="box-header with-border">';
                  $content .= '<h3 class="box-title">Notater</h3>';
                $content .= '</div><!-- /.box-header -->';
                $content .= '<div class="box-body">';

										$content .= '<form class="edit-user-note" method="post">';
											$content .= '<input type="hidden" name="id" value="' . $profileUser->getId() . '">';
											$content .= '<div class="form-group">';
												$content .= '<div class="col-sm-12">';
													$content .= '<textarea name="content" class="form-control" placeholder="Skriv inn et notat her...">' . ($profileUser->hasNote() ? $profileUser->getNote() : null) . '</textarea>';
												$content .= '</div>';
											$content .= '</div>';
											$content .= '<div class="form-group">';
                                            $content .= '<div class="col-sm-12">';
											$content .= '<input type="submit" value="' . ($profileUser->hasNote() ? 'Lagre notat' : 'Legg til notat') . '">';
                                            $content .= '</div>';
                                            $content .= '</div>';
										$content .= '</form>';

                $content .= '</div><!-- /.box-body -->';
              $content .= '</div><!-- /.box -->';
              }
            $content .= '</div><!-- /.col -->';
            $content .= '<div class="col-md-9">';
              $content .= '<div class="nav-tabs-custom">';
                $content .= '<ul class="nav nav-tabs">';
									$content .= '<li class="active"><a href="#information" data-toggle="tab">Informasjon</a></li>';
									$content .= '<li><a href="#history" data-toggle="tab">Historikk</a></li>';
                $content .= '</ul>';
                $content .= '<div class="tab-content">';
									$content .= '<div class="tab-pane active" id="information">';
										$content .= '<table class="table table-bordered">';

											if ($user->hasPermission('*')) {
												$content .= '<tr>';
													$content .= '<td>Id:</td>';
													$content .= '<td>' . $profileUser->getId() . '</td>';
												$content .= '</tr>';
											}

											$content .= '<tr>';
												$content .= '<td>Navn:</td>';
												$content .= '<td>' . $profileUser->getFullName() . '</td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td>Brukernavn:</td>';
												$content .= '<td>' . $profileUser->getUsername() . '</td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td>E-post:</td>';
												$content .= '<td><a href="mailto:' . $profileUser->getEmail() . '">' . $profileUser->getEmail() . '</a></td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td>Fødselsdato</td>';
												$content .= '<td>' . date('d.m.Y', $profileUser->getBirthdate()) . '</td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td>Kjønn:</td>';
												$content .= '<td>' . $profileUser->getGenderAsString() . '</td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td>Alder:</td>';
												$content .= '<td>' . $profileUser->getAge() . ' år</td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td>Telefon:</td>';
												$content .= '<td>' . $profileUser->getPhoneAsString() . '</td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td>Adresse:</td>';
													$address = $profileUser->getAddress();

													if (!empty($address)) {
														$content .= '<td>' . $address . '</td>';
													} else {
														$content .= '<td><i>Ikke oppgitt</i></td>';
													}
											$content .= '</tr>';

											$postalCode = $profileUser->getPostalCode();

											if ($postalCode != 0) {
												$content .= '<tr>';
													$content .= '<td></td>';
													$content .= '<td>' . $postalCode . ' ' . $profileUser->getCity() . '</td>';
												$content .= '</tr>';
											}

											$content .= '<tr>';
												$content .= '<td>Kallenavn:</td>';
												$content .= '<td>' . $profileUser->getNickname() . '</td>';
											$content .= '</tr>';

											if ($profileUser->hasEmergencyContact()) {
												$content .= '<tr>';
													$content .= '<td>Foresatte\'s telefon:</td>';
													$content .= '<td>' . $profileUser->getEmergencyContact()->getPhoneAsString() . '</td>';
												$content .= '</tr>';
											}

											if ($user->hasPermission('*') ||
												$user->equals($profileUser)) {
												$content .= '<tr>';
													$content .= '<td>Dato registrert:</td>';
													$content .= '<td>' . date('d.m.Y', $profileUser->getRegisteredDate()) . '</td>';
												$content .= '</tr>';
											}

											if ($user->hasPermission('user.activate')) {
												$content .= '<tr>';
													$content .= '<td>Aktivert:</td>';
													$content .= '<td>';
														$content .= ($profileUser->isActivated() ? 'Ja' : 'Nei');

														if (!$profileUser->isActivated()) {
															$content .= '<input type="button" value="Aktiver" onClick="activateUser(' . $profileUser->getId() . ')">';
														}
													$content .= '</td>';
												$content .= '</tr>';
											}

											$historyEventCount = count($profileUser->getParticipatedEvents());

											$content .= '<tr>';
												$content .= '<td>Deltatt tidligere:</td>';
												$content .= '<td>' . $historyEventCount . ' ' . ($historyEventCount > 1 ? 'ganger' : 'gang') . '</td>';
											$content .= '</tr>';

											if ($profileUser->isGroupMember()) {
												$group = $profileUser->getGroup();

												$content .= '<tr>';
													$content .= '<td>' . ($profileUser->isTeamMember() ? 'Crew/Lag:' : 'Crew') . '</td>';
													$content .= '<td>' . ($profileUser->isTeamMember() ? $group->getTitle() . ':' . $profileUser->getTeam()->getTitle() : $group->getTitle()) . '</td>';
												$content .= '</tr>';
											}

											if ($profileUser->hasTicket()) {
												$ticketList = $profileUser->getTickets();
												$ticketCount = count($ticketList);
												sort($ticketList);

												$content .= '<tr>';
													$content .= '<td>' . (count($ticketList) > 1 ? 'Billetter' : 'Billett') . ':</td>';
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
													$content .= '<td>Plass:</td>';
													$content .= '<td>' . $ticket->getSeat()->getString() . '</td>';
												$content .= '</tr>';
											}

											if ($user->hasPermission('user.profile')) {
												$content .= '<tr>';
													$content .= '<td>Svømming:</td>';
													$content .= '<td>';
														$content .= $profileUser->isSwimming() ? 'Ja' : 'Nei';
														$content .= '<input type="button" value="Endre" onClick="setUserSwimming(' . $profileUser->getId() . ', ' . ($profileUser->isSwimming() ? '0' : '1') . ')">';
													$content .= '</td>';
												$content .= '</tr>';
											}

										$content .= '</table>';
                  $content .= '</div><!-- /.tab-pane -->';

									$content .= '<div class="tab-pane" id="history">';

										if ($user->hasPermission('user.history') ||
											$user->equals($profileUser)) {
											$eventList = $profileUser->getParticipatedEvents($profileUser);

											if (!empty($eventList)) {
												$content .= '<p>Denne brukeren har deltatt på følgende arrangementer:</p>';
												$content .= '<table class="table table-bordered">';
													$content .= '<tr>';
														$content .= '<th>Arrangement:</th>';
														$content .= '<th>Rolle:</th>';
														$content .= '<th>Medlemskap:</th>';
														$content .= '<th>Billetter:</th>';
													$content .= '</tr>';

													foreach ($eventList as $event) {
														$content .= '<tr>';
															$content .= '<td>' . $event->getTitle() . '</td>';
															$content .= '<td>' . $profileUser->getRoleByEvent($event) . '</td>';

															if ($profileUser->isGroupMemberByEvent($event)) {
																$group = $profileUser->getGroupByEvent($event);

																$content .= '<td><a href="index.php?page=all-crew&id=' . $group->getId() . '">' . $group->getTitle() . '</a></td>';
																$content .= '<td>Ingen</td>';
															} else if ($profileUser->hasTicketByEvent($event)) {
																$content .= '<td>Ingen</td>';
																$content .= '<td>';
																	$ticketList = $profileUser->getTicketsByEvent($event);

																	foreach ($ticketList as $ticket) {
																		$content .= '<a href="index.php?page=ticket&id=' . $ticket->getId() . '">#' . $ticket->getId() . '</a>';

																		// Only print comma if this is not the last ticket in the array.
																		$content .= $ticket !== end($ticketList) ? ', ' : null;
																	}
																$content .= '</td>';
															}
														$content .= '</tr>';
													}
												$content .= '</table>';
											} else {
												$content .= '<p>Denne brukeren har ikke noe historie enda.</p>';
											}
										}

                  $content .= '</div><!-- /.tab-pane -->';
                $content .= '</div><!-- /.tab-content -->';
              $content .= '</div><!-- /.nav-tabs-custom -->';
            $content .= '</div><!-- /.col -->';
          $content .= '</div><!-- /.row -->';
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
