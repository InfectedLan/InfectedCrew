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
    private $profileUser;

    public function __construct() {
        $this->profileUser = isset($_GET['userId']) ? UserHandler::getUser($_GET['userId']) : Session::getCurrentUser();
    }

    public function canAccess(User $user): bool {
        return true;
    }

	public function getTitle(): ?string {
		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if ($this->profileUser != null) {
                return $user->equals($this->profileUser) ? 'Min profil' : $this->profileUser->getFullName() . '\'s profil';
			}
		}

		return 'Profil';
	}

    public function getContent(User $user = null): string {
		$content = null;

		if (Session::isAuthenticated()) {
            echo '<script src="scripts/user-profile.js"></script>';
			$user = Session::getCurrentUser();

			if ($this->profileUser != null) {
				if ($user->hasPermission('user.search') ||
					$user->equals($this->profileUser)) {
					$avatarFile = null;

					if ($this->profileUser->hasValidAvatar()) {
						$avatarFile = $this->profileUser->getAvatar()->getHd();
					} else {
						$avatarFile = $this->profileUser->getDefaultAvatar();
					}


          $content .= '<div class="row">';
            $content .= '<div class="col-md-3">';

              //<!-- Profile Image -->
              $content .= '<div class="box box-primary">';
                $content .= '<div class="box-body box-profile">';
                  $content .= '<img class="profile-user-img img-responsive img-circle" src="../api/' . $avatarFile . '" alt="' . $this->profileUser->getFullName() . '\'s profilbilde">';
									//$content .= '<img class="profile-user-img img-responsive img-circle" src="https://crew.test.infected.no/v3/demo/dist/img/user4-128x128.jpg" alt="' . $this->profileUser->getFullName() . '\'s profilbilde">';
									$content .= '<h3 class="profile-username text-center">' . $this->profileUser->getFullName() . '</h3>';
									$content .= '<p class="text-muted text-center">' . $this->profileUser->getRole() . '</p>';
                $content .= '</div><!-- /.box-body -->';
              $content .= '</div><!-- /.box -->';
              if ($user->equals($this->profileUser) ||
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
                   $user->equals($this->profileUser)) {
                    $content .= '<p><a href="index.php?page=edit-profile&id=' . $this->profileUser->getId() . '">Endre bruker</a></p>';
                }
                if($user->hasPermission('user.relocate') ||
                   $user->equals($this->profileUser)) {
                    $content .= '<p><a href="index.php?page=edit-user-location">Endre plassering</a></p>';
                }
                if($user->hasPermission('admin.permissions')) {
                    $content .= '<p><a href="index.php?page=admin-permissions&id=' . $this->profileUser->getId() . '">Endre rettigheter</a></p>';
                }
                if($user->equals($this->profileUser)) {
                    $content .= '<p><a href="index.php?page=edit-avatar">Endre avatar</a></p>';
                }
                $content .= '</div><!-- /.box-body -->';
              $content .= '</div><!-- /.box -->';
              }

              if ($user->hasPermission('user.note')) {
              $content .= '<div class="box box-primary">';
                $content .= '<div class="box-header with-border">';
                  $content .= '<h3 class="box-title">Notater</h3>';
                $content .= '</div><!-- /.box-header -->';
                $content .= '<div class="box-body">';

										$content .= '<form class="edit-user-note" method="post">';
											$content .= '<input type="hidden" name="id" value="' . $this->profileUser->getId() . '">';
											$content .= '<div class="form-group">';
												$content .= '<div class="col-sm-12">';
													$content .= '<textarea name="content" class="form-control" placeholder="Skriv inn et notat her...">' . ($this->profileUser->hasNote() ? $this->profileUser->getNote() : null) . '</textarea>';
												$content .= '</div>';
											$content .= '</div>';
											$content .= '<div class="form-group">';
                                            $content .= '<div class="col-sm-12">';
											$content .= '<input type="submit" value="' . ($this->profileUser->hasNote() ? 'Lagre notat' : 'Legg til notat') . '">';
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
													$content .= '<td>' . $this->profileUser->getId() . '</td>';
												$content .= '</tr>';
											}

											$content .= '<tr>';
												$content .= '<td>Navn:</td>';
												$content .= '<td>' . $this->profileUser->getFullName() . '</td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td>Brukernavn:</td>';
												$content .= '<td>' . $this->profileUser->getUsername() . '</td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td>E-post:</td>';
												$content .= '<td><a href="mailto:' . $this->profileUser->getEmail() . '">' . $this->profileUser->getEmail() . '</a></td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td>Fødselsdato</td>';
												$content .= '<td>' . date('d.m.Y', $this->profileUser->getBirthdate()) . '</td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td>Kjønn:</td>';
												$content .= '<td>' . $this->profileUser->getGenderAsString() . '</td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td>Alder:</td>';
												$content .= '<td>' . $this->profileUser->getAge() . ' år</td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td>Telefon:</td>';
												$content .= '<td>' . $this->profileUser->getPhoneAsString() . '</td>';
											$content .= '</tr>';
											$content .= '<tr>';
												$content .= '<td>Adresse:</td>';
													$address = $this->profileUser->getAddress();

													if (!empty($address)) {
														$content .= '<td>' . $address . '</td>';
													} else {
														$content .= '<td><i>Ikke oppgitt</i></td>';
													}
											$content .= '</tr>';

											$postalCode = $this->profileUser->getPostalCode();

											if ($postalCode != 0) {
												$content .= '<tr>';
													$content .= '<td></td>';
													$content .= '<td>' . $postalCode . ' ' . $this->profileUser->getCity() . '</td>';
												$content .= '</tr>';
											}

											$content .= '<tr>';
												$content .= '<td>Kallenavn:</td>';
												$content .= '<td>' . $this->profileUser->getNickname() . '</td>';
											$content .= '</tr>';

											if ($this->profileUser->hasEmergencyContact()) {
												$content .= '<tr>';
													$content .= '<td>Foresatte\'s telefon:</td>';
													$content .= '<td>' . $this->profileUser->getEmergencyContact()->getPhoneAsString() . '</td>';
												$content .= '</tr>';
											}

											if ($user->hasPermission('*') ||
												$user->equals($this->profileUser)) {
												$content .= '<tr>';
													$content .= '<td>Dato registrert:</td>';
													$content .= '<td>' . date('d.m.Y', $this->profileUser->getRegisterDate()) . '</td>';
												$content .= '</tr>';
											}

											if ($user->hasPermission('user.activate')) {
												$content .= '<tr>';
													$content .= '<td>Aktivert:</td>';
													$content .= '<td>';
														$content .= ($this->profileUser->isActivated() ? 'Ja' : 'Nei');

														if (!$this->profileUser->isActivated()) {
															$content .= '<input type="button" value="Aktiver" onClick="activateUser(' . $this->profileUser->getId() . ')">';
														}
													$content .= '</td>';
												$content .= '</tr>';
											}

											$historyEventCount = count($this->profileUser->getParticipatedEvents());

											$content .= '<tr>';
												$content .= '<td>Deltatt tidligere:</td>';
												$content .= '<td>' . $historyEventCount . ' ' . ($historyEventCount > 1 ? 'ganger' : 'gang') . '</td>';
											$content .= '</tr>';

											if ($this->profileUser->isGroupMember()) {
												$group = $this->profileUser->getGroup();

												$content .= '<tr>';
													$content .= '<td>' . ($this->profileUser->isTeamMember() ? 'Crew/Lag:' : 'Crew') . '</td>';
													$content .= '<td>' . ($this->profileUser->isTeamMember() ? $group->getTitle() . ':' . $this->profileUser->getTeam()->getTitle() : $group->getTitle()) . '</td>';
												$content .= '</tr>';
											}

											if ($this->profileUser->hasTicket()) {
												$ticketList = $this->profileUser->getTickets();
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

											if ($this->profileUser->hasTicket() &&
												$this->profileUser->hasSeat()) {
												$ticket = $this->profileUser->getTicket();

												$content .= '<tr>';
													$content .= '<td>Plass:</td>';
													$content .= '<td>' . $ticket->getSeat()->getString() . '</td>';
												$content .= '</tr>';
											}

										$content .= '</table>';
                                    $content .= '</div><!-- /.tab-pane -->';

									$content .= '<div class="tab-pane" id="history">';

										if ($user->hasPermission('user.history') ||
											$user->equals($this->profileUser)) {
											$eventList = $this->profileUser->getParticipatedEvents($this->profileUser);

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
															$content .= '<td>' . $this->profileUser->getRole($event) . '</td>';

															if ($this->profileUser->isGroupMember($event)) {
																$group = $this->profileUser->getGroup($event);

																$content .= '<td><a href="index.php?page=all-crew&id=' . $group->getId() . '">' . $group->getTitle() . '</a></td>';
																$content .= '<td>Ingen</td>';
															} else if ($this->profileUser->hasTicket($event)) {
																$content .= '<td>Ingen</td>';
																$content .= '<td>';
																	$ticketList = $this->profileUser->getTickets($event);

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