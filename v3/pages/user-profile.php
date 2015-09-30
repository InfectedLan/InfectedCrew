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
require_once 'handlers/userhistoryhandler.php';
require_once 'interfaces/page.php';
require_once 'traits/page.php';

class UserProfilePage implements IPage {
	use TPage;

	public function getTitle() {
		$id = isset($_GET['id']) ? $_GET['id'] : Session::getCurrentUser()->getId();

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

	public function getContent() {
		$content = null;
		$id = isset($_GET['id']) ? $_GET['id'] : Session::getCurrentUser()->getId();

		if (Session::isAuthenticated()) {
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

                  $content .= '<ul class="list-group list-group-unbordered">';
                    $content .= '<li class="list-group-item">';
                      $content .= '<b>Followers</b> <a class="pull-right">1,322</a>';
                    $content .= '</li>';
                    $content .= '<li class="list-group-item">';
                      $content .= '<b>Following</b> <a class="pull-right">543</a>';
                    $content .= '</li>';
                    $content .= '<li class="list-group-item">';
                      $content .= '<b>Friends</b> <a class="pull-right">13,287</a>';
                    $content .= '</li>';
                  $content .= '</ul>';

                  $content .= '<a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>';
                $content .= '</div><!-- /.box-body -->';
              $content .= '</div><!-- /.box -->';

              $content .= '<!-- About Me Box -->';
              $content .= '<div class="box box-primary">';
                $content .= '<div class="box-header with-border">';
                  $content .= '<h3 class="box-title">About Me</h3>';
                $content .= '</div><!-- /.box-header -->';
                $content .= '<div class="box-body">';
                  $content .= '<strong><i class="fa fa-book margin-r-5"></i>  Education</strong>';
                  $content .= '<p class="text-muted">';
                    $content .= 'B.S. in Computer Science from the University of Tennessee at Knoxville';
                  $content .= '</p>';

                  $content .= '<hr>';

                  $content .= '<strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>';
                  $content .= '<p class="text-muted">Malibu, California</p>';

                  $content .= '<hr>';

                  $content .= '<strong><i class="fa fa-pencil margin-r-5"></i> Skills</strong>';
                  $content .= '<p>';
                    $content .= '<span class="label label-danger">UI Design</span>';
                    $content .= '<span class="label label-success">Coding</span>';
                    $content .= '<span class="label label-info">Javascript</span>';
                    $content .= '<span class="label label-warning">PHP</span>';
                    $content .= '<span class="label label-primary">Node.js</span>';
                  $content .= '</p>';

                  $content .= '<hr>';

                  $content .= '<strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>';
                  $content .= '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>';

									if ($user->hasPermission('user.note')) {
										$content .= '<form class="edit-user-note" method="post">';
											$content .= '<input type="hidden" name="id" value="' . $profileUser->getId() . '">';
											$content .= '<div class="form-group">';
												$content .= '<div class="col-sm-10">';
													$content .= '<textarea class="form-control" placeholder="Skriv inn et notat her...">' . ($profileUser->hasNote() ? $profileUser->getNote() : null) . '</textarea>';
												$content .= '</div>';
											$content .= '</div>';
											$content .= '<input type="submit" value="' . ($profileUser->hasNote() ? 'Lagre notat' : 'Legg til notat') . '">';
										$content .= '</form>';
									}

									$content .= '<form>';
										$content .= '<div class="form-group">';
											$content .= '<div class="col-sm-10">';
												$content .= '<textarea class="form-control" placeholder="Skriv "></textarea>';
											$content .= '</div>';
										$content .= '</div>';
									$content .= '</form>';
                $content .= '</div><!-- /.box-body -->';
              $content .= '</div><!-- /.box -->';
            $content .= '</div><!-- /.col -->';
            $content .= '<div class="col-md-9">';
              $content .= '<div class="nav-tabs-custom">';
                $content .= '<ul class="nav nav-tabs">';
                  $content .= '<li class="active"><a href="#activity" data-toggle="tab">Activity</a></li>';
                  $content .= '<li><a href="#timeline" data-toggle="tab">Timeline</a></li>';
                  $content .= '<li><a href="#settings" data-toggle="tab">Settings</a></li>';
									$content .= '<li><a href="#more" data-toggle="tab">Mer</a></li>';
									$content .= '<li><a href="#history" data-toggle="tab">Historikk</a></li>';
                $content .= '</ul>';
                $content .= '<div class="tab-content">';
                  $content .= '<div class="active tab-pane" id="activity">';
                    //<!-- Post -->
                    $content .= '<div class="post">';
                      $content .= '<div class="user-block">';
                        $content .= '<img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image">';
                        $content .= '<span class="username">';
                          $content .= '<a href="#">Jonathan Burke Jr.</a>';
                          $content .= '<a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>';
                        $content .= '</span>';
                        $content .= '<span class="description">Shared publicly - 7:30 PM today</span>';
                      $content .= '</div><!-- /.user-block -->';
                      $content .= '<p>';
                        $content .= 'Lorem ipsum represents a long-held tradition for designers,';
                        $content .= 'typographers and the like. Some people hate it and argue for';
                        $content .= 'its demise, but others ignore the hate as they create awesome';
                        $content .= 'tools to help create filler text for everyone from bacon lovers';
                        $content .= 'to Charlie Sheen fans.';
                      $content .= '</p>';
                      $content .= '<ul class="list-inline">';
                        $content .= '<li><a href="#" class="link-black text-sm"><i class="fa fa-share margin-r-5"></i> Share</a></li>';
                        $content .= '<li><a href="#" class="link-black text-sm"><i class="fa fa-thumbs-o-up margin-r-5"></i> Like</a></li>';
                        $content .= '<li class="pull-right"><a href="#" class="link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i> Comments (5)</a></li>';
                      $content .= '</ul>';

                      $content .= '<input class="form-control input-sm" type="text" placeholder="Type a comment">';
                    $content .= '</div><!-- /.post -->';

                    //<!-- Post -->
                    $content .= '<div class="post clearfix">';
                      $content .= '<div class="user-block">';
                        $content .= '<img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="user image">';
                        $content .= '<span class="username">';
                          $content .= '<a href="#">Sarah Ross</a>';
                          $content .= '<a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>';
                        $content .= '</span>';
                        $content .= '<span class="description">Sent you a message - 3 days ago</span>';
                      $content .= '</div><!-- /.user-block -->';
                      $content .= '<p>';
                        $content .= 'Lorem ipsum represents a long-held tradition for designers,';
                        $content .= 'typographers and the like. Some people hate it and argue for';
                        $content .= 'its demise, but others ignore the hate as they create awesome';
                        $content .= 'tools to help create filler text for everyone from bacon lovers';
                        $content .= 'to Charlie Sheen fans.';
                      $content .= '</p>';

                      $content .= '<form class="form-horizontal">';
                        $content .= '<div class="form-group margin-bottom-none">';
                          $content .= '<div class="col-sm-9">';
                            $content .= '<input class="form-control input-sm" placeholder="Response">';
                          $content .= '</div>';
                          $content .= '<div class="col-sm-3">';
                            $content .= '<button class="btn btn-danger pull-right btn-block btn-sm">Send</button>';
                          $content .= '</div>';
                        $content .= '</div>';
                      $content .= '</form>';
                    $content .= '</div><!-- /.post -->';

                    //<!-- Post -->
                    $content .= '<div class="post">';
                      $content .= '<div class="user-block">';
                        $content .= '<img class="img-circle img-bordered-sm" src="../../dist/img/user6-128x128.jpg" alt="user image">';
                        $content .= '<span class="username">';
                          $content .= '<a href="#">Adam Jones</a>';
                          $content .= '<a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>';
                        $content .= '</span>';
                        $content .= '<span class="description">Posted 5 photos - 5 days ago</span>';
                      $content .= '</div><!-- /.user-block -->';
                      $content .= '<div class="row margin-bottom">';
                        $content .= '<div class="col-sm-6">';
                          $content .= '<img class="img-responsive" src="../../dist/img/photo1.png" alt="Photo">';
                        $content .= '</div><!-- /.col -->';
                        $content .= '<div class="col-sm-6">';
                          $content .= '<div class="row">';
                            $content .= '<div class="col-sm-6">';
                              $content .= '<img class="img-responsive" src="../../dist/img/photo2.png" alt="Photo">';
                              $content .= '<br>';
                              $content .= '<img class="img-responsive" src="../../dist/img/photo3.jpg" alt="Photo">';
                            $content .= '</div><!-- /.col -->';
                            $content .= '<div class="col-sm-6">';
                              $content .= '<img class="img-responsive" src="../../dist/img/photo4.jpg" alt="Photo">';
                              $content .= '<br>';
                              $content .= '<img class="img-responsive" src="../../dist/img/photo1.png" alt="Photo">';
                            $content .= '</div><!-- /.col -->';
                          $content .= '</div><!-- /.row -->';
                        $content .= '</div><!-- /.col -->';
                      $content .= '</div><!-- /.row -->';

                      $content .= '<ul class="list-inline">';
                        $content .= '<li><a href="#" class="link-black text-sm"><i class="fa fa-share margin-r-5"></i> Share</a></li>';
                        $content .= '<li><a href="#" class="link-black text-sm"><i class="fa fa-thumbs-o-up margin-r-5"></i> Like</a></li>';
                        $content .= '<li class="pull-right"><a href="#" class="link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i> Comments (5)</a></li>';
                      $content .= '</ul>';

                      $content .= '<input class="form-control input-sm" type="text" placeholder="Type a comment">';
                    $content .= '</div><!-- /.post -->';
                  $content .= '</div><!-- /.tab-pane -->';
                  $content .= '<div class="tab-pane" id="timeline">';
                    //<!-- The timeline -->
                    $content .= '<ul class="timeline timeline-inverse">';
                      //<!-- timeline time label -->
                      $content .= '<li class="time-label">';
                        $content .= '<span class="bg-red">';
                          $content .= '10 Feb. 2014';
                        $content .= '</span>';
                      $content .= '</li>';
                      //<!-- /.timeline-label -->
                      //<!-- timeline item -->
                      $content .= '<li>';
                        $content .= '<i class="fa fa-envelope bg-blue"></i>';
                        $content .= '<div class="timeline-item">';
                          $content .= '<span class="time"><i class="fa fa-clock-o"></i> 12:05</span>';
                          $content .= '<h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>';
                          $content .= '<div class="timeline-body">';
                            $content .= 'Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,';
                            $content .= 'weebly ning heekya handango imeem plugg dopplr jibjab, movity';
                            $content .= 'jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle';
                            $content .= 'quora plaxo ideeli hulu weebly balihoo...';
                          $content .= '</div>';
                          $content .= '<div class="timeline-footer">';
                            $content .= '<a class="btn btn-primary btn-xs">Read more</a>';
                            $content .= '<a class="btn btn-danger btn-xs">Delete</a>';
                          $content .= '</div>';
                        $content .= '</div>';
                      $content .= '</li>';
                      //<!-- END timeline item -->
                      //<!-- timeline item -->
                      $content .= '<li>';
                        $content .= '<i class="fa fa-user bg-aqua"></i>';
                        $content .= '<div class="timeline-item">';
                          $content .= '<span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>';
                          $content .= '<h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request</h3>';
                        $content .= '</div>';
                      $content .= '</li>';
                      //<!-- END timeline item -->
                      //<!-- timeline item -->
                      $content .= '<li>';
                        $content .= '<i class="fa fa-comments bg-yellow"></i>';
                        $content .= '<div class="timeline-item">';
                          $content .= '<span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>';
                          $content .= '<h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>';
                          $content .= '<div class="timeline-body">';
                            $content .= 'Take me to your leader!';
                            $content .= 'Switzerland is small and neutral!';
                            $content .= 'We are more like Germany, ambitious and misunderstood!';
                          $content .= '</div>';
                          $content .= '<div class="timeline-footer">';
                            $content .= '<a class="btn btn-warning btn-flat btn-xs">View comment</a>';
                          $content .= '</div>';
                        $content .= '</div>';
                      $content .= '</li>';
                      //<!-- END timeline item -->
                      //<!-- timeline time label -->
                      $content .= '<li class="time-label">';
                        $content .= '<span class="bg-green">';
                          $content .= '3 Jan. 2014';
                        $content .= '</span>';
                      $content .= '</li>';
                      //<!-- /.timeline-label -->
                      //<!-- timeline item -->
                      $content .= '<li>';
                        $content .= '<i class="fa fa-camera bg-purple"></i>';
                        $content .= '<div class="timeline-item">';
                          $content .= '<span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>';
                          $content .= '<h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>';
                          $content .= '<div class="timeline-body">';
                            $content .= '<img src="http://placehold.it/150x100" alt="..." class="margin">';
                            $content .= '<img src="http://placehold.it/150x100" alt="..." class="margin">';
                            $content .= '<img src="http://placehold.it/150x100" alt="..." class="margin">';
                            $content .= '<img src="http://placehold.it/150x100" alt="..." class="margin">';
                          $content .= '</div>';
                        $content .= '</div>';
                      $content .= '</li>';
                      //<!-- END timeline item -->
                      $content .= '<li>';
                        $content .= '<i class="fa fa-clock-o bg-gray"></i>';
                      $content .= '</li>';
                    $content .= '</ul>';
                  $content .= '</div><!-- /.tab-pane -->';

                  $content .= '<div class="tab-pane" id="settings">';
                    $content .= '<form class="form-horizontal">';
                      $content .= '<div class="form-group">';
                        $content .= '<label for="inputName" class="col-sm-2 control-label">Name</label>';
                        $content .= '<div class="col-sm-10">';
                          $content .= '<input type="email" class="form-control" id="inputName" placeholder="Name">';
                        $content .= '</div>';
                      $content .= '</div>';
                      $content .= '<div class="form-group">';
                        $content .= '<label for="inputEmail" class="col-sm-2 control-label">Email</label>';
                        $content .= '<div class="col-sm-10">';
                          $content .= '<input type="email" class="form-control" id="inputEmail" placeholder="Email">';
                        $content .= '</div>';
                      $content .= '</div>';
                      $content .= '<div class="form-group">';
                        $content .= '<label for="inputName" class="col-sm-2 control-label">Name</label>';
                        $content .= '<div class="col-sm-10">';
                          $content .= '<input type="text" class="form-control" id="inputName" placeholder="Name">';
                        $content .= '</div>';
                      $content .= '</div>';
                      $content .= '<div class="form-group">';
                        $content .= '<label for="inputExperience" class="col-sm-2 control-label">Experience</label>';
                        $content .= '<div class="col-sm-10">';
                          $content .= '<textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>';
                        $content .= '</div>';
                      $content .= '</div>';
                      $content .= '<div class="form-group">';
                        $content .= '<label for="inputSkills" class="col-sm-2 control-label">Skills</label>';
                        $content .= '<div class="col-sm-10">';
                          $content .= '<input type="text" class="form-control" id="inputSkills" placeholder="Skills">';
                        $content .= '</div>';
                      $content .= '</div>';
                      $content .= '<div class="form-group">';
                        $content .= '<div class="col-sm-offset-2 col-sm-10">';
                          $content .= '<div class="checkbox">';
                            $content .= '<label>';
                              $content .= '<input type="checkbox"> I agree to the <a href="#">terms and conditions</a>';
                            $content .= '</label>';
                          $content .= '</div>';
                        $content .= '</div>';
                      $content .= '</div>';
                      $content .= '<div class="form-group">';
                        $content .= '<div class="col-sm-offset-2 col-sm-10">';
                          $content .= '<button type="submit" class="btn btn-danger">Submit</button>';
                        $content .= '</div>';
                      $content .= '</div>';
                    $content .= '</form>';
                  $content .= '</div><!-- /.tab-pane -->';

									$content .= '<div class="tab-pane" id="more">';
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
												$content .= '<table>';
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
