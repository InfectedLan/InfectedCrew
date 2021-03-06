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

//Maintenance trap
require_once 'SiteMaintenanceTrap.php';

require_once 'session.php';
require_once 'settings.php';
require_once 'handlers/restrictedpagehandler.php';
require_once 'handlers/grouphandler.php';
require_once 'handlers/applicationhandler.php';
require_once 'handlers/avatarhandler.php';
require_once 'handlers/compohandler.php';
require_once 'database.php';

class Site {
	private $pageName;

	public function __construct() {
		$this->pageName = isset($_GET['page']) ? strtolower($_GET['page']) : 'my-crew';
	}

	// Execute the site.
	public function execute() {
	    $start = explode(' ', microtime())[0] + explode(' ', microtime())[1];
		echo '<!DOCTYPE html>';
		echo '<html>';
			echo '<head>';
				echo '<title>' . $this->getTitle() . '</title>';
				echo '<meta name="description" content="' . Settings::description . '">';
				echo '<meta name="keywords" content="' . Settings::keywords . '">';
				echo '<meta name="author" content="halvors and petterroea">';
				echo '<meta charset="UTF-8">';
				echo '<link rel="shortcut icon" href="images/favicon.ico">';
				echo '<link rel="stylesheet" href="styles/style.css">';
				echo '<link rel="stylesheet" href="styles/topmenu.css">';
				echo '<link rel="stylesheet" href="styles/menu.css">';
				echo '<link rel="stylesheet" href="../api/libraries/chosen/chosen.css">';
                echo '<link rel="stylesheet" href="fonts/font-awesome/css/font-awesome.min.css">';
				echo '<script src="../api/scripts/jquery-1.11.3.min.js"></script>';
				echo '<script src="../api/scripts/jquery.form.min.js"></script>';
				echo '<script src="../api/scripts/login.js"></script>';
				echo '<script src="../api/scripts/logout.js"></script>';
				echo '<script src="../api/libraries/chosen/chosen.jquery.js"></script>';
				echo '<script src="../api/libraries/ckeditor/ckeditor.js"></script>';
				echo '<script src="../api/libraries/ckeditor/adapters/jquery.js"></script>';
				echo '<script src="scripts/site.js"></script>';
				echo '<script src="scripts/common.js"></script>';
				echo '<script>';
					echo '(function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){';
					echo '(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),';
					echo 'm=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)';
					echo '})(window,document,\'script\',\'//www.google-analytics.com/analytics.js\',\'ga\');';

					echo 'ga(\'create\', \'UA-54254513-3\', \'auto\');';
					echo 'ga(\'send\', \'pageview\');';
				echo '</script>';

				if (Session::isAuthenticated()) {
					$user = Session::getCurrentUser();

					if ($user->hasEasterEgg()) {
						echo '<style>';
							echo 'body {';
								echo 'background: url(\'images/hello-kitty-edition.jpg\') right top,
								                  url(\'images/hello-kitty-edition.jpg\') left top;';
								echo 'background-repeat: repeat-y;';
								echo 'background-size: 350px;';
							echo '}';
							echo '.main .content {';
								echo 'box-shadow: 0px 0px 0px 7px rgba(230, 14, 99, 0.30);';
							echo '}';

							echo '.information {';
								echo 'background-color: rgba(230, 14, 99, 0.30);';
								echo 'border-color: rgba(230, 14, 99, 0.40);';
							echo '}';

							echo '.topmenu ul .active, .topmenu ul li a:hover {';
								echo 'border-bottom: 3px solid rgba(230, 14, 99, 0.50);';
							echo '}';

							echo '.menu ul .active {';
								echo 'background: rgba(230, 14, 99, 1);';
							echo '}';

							echo '.menu ul li a:hover {';
								echo 'background: rgba(230, 14, 99, 0.5);';
							echo '}';

							echo '.menu ul .active:after {';
								echo 'border-left-color: rgba(230, 14, 99, 1);';
							echo '}';

							echo '.menu ul li a:after {';
								echo 'border-left-color: rgba(230, 14, 99, 0.3);';
							echo '}';
						echo '</style>';
					}
				}
			echo '</head>';
			echo '<body>';
				echo '<div class="user">';
					if (Session::isAuthenticated()) {
						$user = Session::getCurrentUser();

						echo '<p>Logget inn som ' . $user->getFullName() . ' <button type="button" onClick="logout()">Logg ut</button></p>';
					}
				echo '</div>';
				echo '<header class="header">';
					echo '<div class="logo">';
						echo '<a href="."><img src="images/logo.png"></a>';
					echo '</div>';
					echo '<nav class="topmenu">';
						echo '<ul>';
							if (Session::isAuthenticated()) {
								$user = Session::getCurrentUser();

								if ($user->isGroupMember()) {
									$group = $user->getGroup();
									$pageList = null;

									// If the user is member of a team, also fetch team only pages.
									if ($user->isTeamMember()) {
										$pageList = RestrictedPageHandler::getPagesForGroupAndTeam($group, $user->getTeam());
									} else {
										$pageList = RestrictedPageHandler::getPagesForGroup($group);
									}

									$pageNameList = [];

									foreach ($pageList as $page) {
										$pageNameList[] = strtolower($page->getName());
									}

									if ($this->pageName == 'my-crew' ||
										in_array($this->pageName, $pageNameList)) {
										$teamList = $group->getTeams();
										$teamNameList = [];

										foreach ($teamList as $team) {
											$teamNameList[] = strtolower($team->getName());
										}

										// Only show pages for that group.
										if (!empty($pageList) ||
											!empty($teamList)) {
											echo '<li><a' . ($this->pageName == 'my-crew' && !isset($_GET['teamId']) ? ' class="active"' : null) . ' href="index.php?page=my-crew">' . $group->getTitle() . '</a></li>';

											// Only create link for groups that actually contain teams.
											if (!empty($teamList)) {
												foreach ($teamList as $team) {
													echo '<li><a' . (isset($_GET['teamId']) && $team->getId() == $_GET['teamId'] ? ' class="active"' : null) . ' href="index.php?page=my-crew&teamId=' . $team->getId() . '">' . $team->getTitle() . '</a></li>';
												}
											}

											if (!empty($pageList)) {
												foreach ($pageList as $page) {
													if (strtolower($page->getName()) != strtolower($group->getName())) {
														if (!in_array(strtolower($page->getName()), $teamNameList)) {
															echo '<li><a' . ($this->pageName == strtolower($page->getName()) ? ' class="active"' : null) . ' href="index.php?page=' . $page->getName() . '">' . $page->getTitle() . '</a></li>';
														}
													}
												}
											}
										}
									}
								}

								if ($this->pageName == 'all-crew') {
									$groupList = GroupHandler::getGroups();

									foreach ($groupList as $group) {
										echo '<li><a' . (isset($_GET['id']) && $group->getId() == $_GET['id'] ? ' class="active"' : null) . ' href="index.php?page=all-crew&id=' . $group->getId() . '">' . $group->getTitle() . '</a></li>';
									}
								} else if ($this->pageName == 'event' ||
										   $this->pageName == 'event-checkin' ||
											 $this->pageName == 'event-checklist' ||
											 $this->pageName == 'edit-note' ||
										   $this->pageName == 'event-seatmap' ||
                                    		$this->pageName == 'event-screen' ||
                                    $this->pageName == 'event-nfcoverview' ||
                                    $this->pageName == 'event-bongtransactions' ||
                                    $this->pageName == 'event-bongoverview' ||
                                    $this->pageName == 'event-nfcassign' ||
										   $this->pageName == 'event-agenda') {

									if ($user->hasPermission('event.checkin')) {
										echo '<li><a' . ($this->pageName == 'event-checkin' ? ' class="active"' : null) . ' href="index.php?page=event-checkin">Innsjekk</a></li>';
									}

									if ($user->hasPermission('event.checklist')) {
										echo '<li><a' . ($this->pageName == 'event-checklist' || $this->pageName == 'edit-note' ? ' class="active"' : null) . ' href="index.php?page=event-checklist">Sjekkliste</a></li>';
									}

									if ($user->hasPermission('event.seatmap')) {
										echo '<li><a' . ($this->pageName == 'event-seatmap' ? ' class="active"' : null) . ' href="index.php?page=event-seatmap">Setekart</a></li>';
									}

									if ($user->hasPermission('event.screen')) {
										echo '<li><a' . ($this->pageName == 'event-screen' ? ' class="active"' : null) . ' href="index.php?page=event-screen">Skjerm</a></li>';
									}

									if ($user->hasPermission('event.agenda')) {
										echo '<li><a' . ($this->pageName == 'event-agenda' ? ' class="active"' : null) . ' href="index.php?page=event-agenda">Agenda</a></li>';
									}

									if ($user->hasPermission('event.table-labels')) {
										echo '<li><a href="../api/pages/utils/printTableLabels.php">Print bordlapper</a></li>';
									}

                                    if ($user->hasPermission('nfc.management')) {
                                        echo '<li><a' . ($this->pageName == 'event-nfcoverview' ? ' class="active"' : null) . ' href="index.php?page=event-nfcoverview">NFC-oversikt</a></li>';
                                    }
                                    if ($user->hasPermission('nfc.bong.management')) {
                                        echo '<li><a' . ($this->pageName == 'event-bongtransactions' ? ' class="active"' : null) . ' href="index.php?page=event-bongtransactions">Bong-transaksjoner</a></li>';
                                    }
                                    if ($user->hasPermission('nfc.bong.management')) {
                                        echo '<li><a' . ($this->pageName == 'event-bongoverview' ? ' class="active"' : null) . ' href="index.php?page=event-bongoverview">Bong-oversikt</a></li>';
                                    }
                                    if ($user->hasPermission('nfc.card.management')) {
                                        echo '<li><a' . ($this->pageName == 'event-nfcassign' ? ' class="active"' : null) . ' href="index.php?page=event-nfcassign">Bind NFC-kort til bruker</a></li>';
                                    }
								} else if ($this->pageName == 'chief' ||
									$this->pageName == 'chief-groups' ||
									$this->pageName == 'chief-teams' ||
									$this->pageName == 'chief-avatars' ||
									$this->pageName == 'chief-applications' ||
									$this->pageName == 'chief-my-crew' ||
									$this->pageName == 'chief-email' ||
									$this->pageName == 'application') {

									if ($user->hasPermission('chief.group')) {
										echo '<li><a' . ($this->pageName == 'chief-groups' ? ' class="active"' : null) . ' href="index.php?page=chief-groups">Crew</a></li>';
									}

									if ($user->hasPermission('chief.team')) {
										echo '<li><a' . ($this->pageName == 'chief-teams' ? ' class="active"' : null) . ' href="index.php?page=chief-teams">Lag</a></li>';
									}

									if ($user->hasPermission('chief.avatars')) {
										echo '<li><a' . ($this->pageName == 'chief-avatars' ? ' class="active"' : null) . ' href="index.php?page=chief-avatars">Profilbilder</a></li>';
									}

									if ($user->hasPermission('chief.applications')) {
										echo '<li><a' . ($this->pageName == 'chief-applications' || $this->pageName == 'application' ? ' class="active"' : null) . ' href="index.php?page=chief-applications">Søknader</a></li>';
									}

									if ($user->hasPermission('chief.my-crew')) {
										echo '<li><a' . ($this->pageName == 'chief-my-crew' || $this->pageName == 'edit-restricted-page' ? ' class="active"' : null) . ' href="index.php?page=chief-my-crew">My Crew</a></li>';
									}

									if ($user->hasPermission('chief.email')) {
										echo '<li><a' . ($this->pageName == 'chief-email' ? ' class="active"' : null) . ' href="index.php?page=chief-email">Send e-post</a></li>';
									}
                                } else if ($this->pageName == 'network') {
                                    echo '<li><a' . ($this->pageName == 'network' && !isset($_GET['platform']) ? ' class="active"' : null) . ' href="index.php?page=network">Informasjon</a></li>';
                                    echo '<li><a' . ($this->pageName == 'network' && $_GET['platform'] == 'android' ? ' class="active"' : null) . ' href="index.php?page=network&platform=android">Android</a></li>';
                                    echo '<li><a' . ($this->pageName == 'network' && $_GET['platform'] == 'ios' ? ' class="active"' : null) . ' href="index.php?page=network&platform=ios">IOS</a></li>';
                                    echo '<li><a' . ($this->pageName == 'network' && $_GET['platform'] == 'windows' ? ' class="active"' : null) . ' href="index.php?page=network&platform=windows">Windows</a></li>';
                                } else if ($this->pageName == 'admin' ||
									$this->pageName == 'admin-events' ||
									$this->pageName == 'admin-permissions' ||
									$this->pageName == 'admin-seatmap' ||
									$this->pageName == 'admin-website' ||
									$this->pageName == 'admin-memberlist' ||
									$this->pageName == 'admin-wsconsole') {

									if ($user->hasPermission('admin.events')) {
										echo '<li><a' . ($this->pageName == 'admin-events' ? ' class="active"' : null) . ' href="index.php?page=admin-events">Arrangementer</a></li>';
									}

									if ($user->hasPermission('admin.permissions')) {
										echo '<li><a' . ($this->pageName == 'admin-permissions' ? ' class="active"' : null) . ' href="index.php?page=admin-permissions">Rettigheter</a></li>';
									}

									if ($user->hasPermission('admin.memberlist')) {
										echo '<li><a' . ($this->pageName == 'admin-memberlist' ? ' class="active"' : null) . ' href="index.php?page=admin-memberlist">Medlemsliste</a></li>';
									}

									if ($user->hasPermission('admin.seatmap')) {
										echo '<li><a' . ($this->pageName == 'admin-seatmap' ? ' class="active"' : null) . ' href="index.php?page=admin-seatmap">Endre seatmap</a></li>';
									}

									if ($user->hasPermission('admin.website')) {
										echo '<li><a' . ($this->pageName == 'admin-website' || $this->pageName == 'edit-page' ? ' class="active"' : null) . ' href="index.php?page=admin-website">Endre hovedsiden</a></li>';
									}

									if ($user->hasPermission('admin.websocket')) {
									    echo '<li><a' . ($this->pageName == 'admin-wsconsole' ? ' class="active"' : null) . ' href="index.php?page=admin-wsconsole">Websocket-konsoll</a></li>';
									}
								} else if ($this->pageName == 'stats' ||
													 $this->pageName == 'stats-age' ||
													 $this->pageName == 'stats-gender' ||
													 $this->pageName == 'stats-ticketsale') {
									if ($user->hasPermission('stats.age')) {
 										echo '<li><a' . ($this->pageName == 'stats-age' ? ' class="active"' : null) . ' href="index.php?page=stats-age">Alders-statistikk</a></li>';
 									}

									if ($user->hasPermission('stats.gender')) {
										echo '<li><a' . ($this->pageName == 'stats-gender' ? ' class="active"' : null) . ' href="index.php?page=stats-gender">Kjønns-statistikk</a></li>';
									}

									if ($user->hasPermission('stats.ticketsale')) {
										echo '<li><a' . ($this->pageName == 'stats-ticketsale' ? ' class="active"' : null) . ' href="index.php?page=stats-ticketsale">Billettsalg-statistikk</a></li>';
									}
                } else if ($this->pageName=='compo-overview' ||
                          $this->pageName=='compo-new' ||
                          $this->pageName=='compo-view' ||
                          $this->pageName=='compo-clans' ||
                          $this->pageName=='compo-matches' ||
												  $this->pageName=='compo-brackets' ||
												  $this->pageName=='compo-chat' ||
												  $this->pageName=='compo-servers' ||
                          $this->pageName == 'compo-casting' ||
                          $this->pageName=='compo-clan') {
	                if ($user->hasPermission('compo.management')) {
	                    echo '<li><a ' . ($this->pageName == 'compo-overview' ? ' class="active"' : null) . ' href="index.php?page=compo-overview">Oversikt</a></li>';
	                }

	                if ($user->hasPermission('compo.edit')) {
	                    echo '<li><a ' . ($this->pageName == 'compo-new' ? ' class="active"' : null) . ' href="index.php?page=compo-new">Ny compo</a></li>';
	                }

	                if ($user->hasPermission('compo.management')) {
                    $compos = CompoHandler::getCompos();

										if (count($compos) > 0) {
                      echo "<li>|</li>";

											foreach($compos as $compo) {
                          echo '<li><a ' . ( ( $this->pageName == 'compo-view' || $this->pageName == 'compo-clans' || $this->pageName == 'compo-matches' || $this->pageName == 'compo-brackets' || $this->pageName == 'compo-chat' || $this->pageName == 'compo-servers' ) && isset($_GET["id"]) && $_GET["id"] == $compo->getId() ? ' class="active"' : '') . ' href="index.php?page=compo-view&id=' . $compo->getId() . '">' . $compo->getTag() . '</a></li>';
                      }

											echo "<li>|</li>";
                    }
	                }

                  if ($user->hasPermission('compo.casting')) {
                    echo '<li><a ' . ($this->pageName == 'compo-casting' ? ' class="active"' : null) . ' href="index.php?page=compo-casting">Casting</a></li>';
                  }
								} else if ($this->pageName == 'developer' ||
									$this->pageName == 'developer-change-user' ||
									$this->pageName == 'developer-syslog' ||
                                    $this->pageName == 'developer-phpinfo' || 
                                    $this->pageName == 'developer-maintenance') {

									if ($user->hasPermission('*') ||
										$user->hasPermission('developer.change-user')) {
										echo '<li><a' . ($this->pageName == 'developer-change-user' ? ' class="active"' : null) . ' href="index.php?page=developer-change-user">Logg inn som en annan</a></li>';
									}

									if ($user->hasPermission('*') ||
										$user->hasPermission('developer.phpinfo')) {
										echo '<li><a' . ($this->pageName == 'developer-phpinfo' ? ' class="active"' : null) . ' href="index.php?page=developer-phpinfo">Vis phpinfo</a></li>';
									}

									if ($user->hasPermission('*') ||
										$user->hasPermission('developer.syslog')) {
										echo '<li><a' . ($this->pageName == 'developer-syslog' ? ' class="active"' : null) . ' href="index.php?page=developer-syslog">Systemlogg</a></li>';
                                    }
                                    
                                    if ($user->hasPermission('*') ||
										$user->hasPermission('developer.maintenance')) {
										echo '<li><a' . ($this->pageName == 'developer-maintenance' ? ' class="active"' : null) . ' href="index.php?page=developer-maintenance">Vedlikeholdsmodus</a></li>';
									}

								}
							}
						echo '</ul>';
					echo '</nav>';
				echo '</header>';
				echo '<section class="main">';
					echo '<article class="content">';
						echo '<div id="error" class="warning" style="display:none;">';
							echo '<span id="innerError">';
							echo '</span>';
						echo '</div>';
						echo '<div id="info" class="information" style="display:none;">';
							echo '<span id="innerInfo">';
							echo '</span>';
						echo '</div>';

						if (isset($_GET['error'])) {
							echo '<script>error("' . $_GET['error'] . '");</script>';
						}

						if (isset($_GET['info'])) {
							echo '<script>info("' . $_GET['info'] . '");</script>';
						}

						if (Session::isAuthenticated()) {
							$user = Session::getCurrentUser();

							if ($user->hasPermission('*') ||
								$user->isGroupMember()) {
								// Show notifications.
								$this->viewNotifications();

								// View the page specified by "pageName" variable.
								$this->viewPage($this->pageName);
							} else {
								$publicPages = ['apply',
															  'all-crew',
															  'user-profile',
															  'edit-profile',
															  'edit-password',
															  'edit-avatar'];

								if (in_array($this->pageName, $publicPages)) {
									$this->viewPage($this->pageName);
								} else {
									$this->viewPage('crew');
								}
							}
						} else {
							$publicPages = ['register',
															'activation',
															'reset-password'];

							if (in_array($this->pageName, $publicPages)) {
								$this->viewPage($this->pageName);
							} else {
								$this->viewLogin();
							}
						}
					echo '</article>';
					echo '<nav class="menu">';
						echo '<ul>';
							if (Session::isAuthenticated()) {
								$user = Session::getCurrentUser();

								if ($user->hasPermission('user.search')) {

									echo '<li' . ($this->pageName == 'user-search' ? ' class="active"' : null) . '><a href="index.php?page=user-search"><img src="images/search.png"></a></li>';
								}

								if ($user->isGroupMember()) {
									echo '<li' . ($this->pageName == 'my-crew' || in_array(strtolower($this->pageName), $pageNameList) ? ' class="active"' : null) . '><a href="index.php?page=my-crew"><img src="images/my-crew.png"></a></li>';
								} else {
									echo '<li' . ($this->pageName == 'apply' ? ' class="active"' : null) . '><a href="index.php?page=apply"><img src="images/apply.png"></a></li>';
								}

								echo '<li' . ($this->pageName == 'all-crew' ? ' class="active"' : null) . '><a href="index.php?page=all-crew"><img src="images/all-crew.png"></a></li>';

								if ($user->hasPermission('event')) {
									if ($this->pageName == 'event' ||
										$this->pageName == 'event-checkin' ||
										$this->pageName == 'event-checklist' ||
										$this->pageName == 'edit-note' ||
										$this->pageName == 'event-seatmap' ||
                                        $this->pageName == 'event-screen' ||
                                        $this->pageName == 'event-bongoverview' ||
                                        $this->pageName == 'event-nfcoverview' ||
                                        $this->pageName == 'event-bongtransactions' ||
										$this->pageName == 'event-agenda') {
										echo '<li class="active"><a href="index.php?page=event"><img src="images/event.png"></a></li>';
									} else {
										echo '<li><a href="index.php?page=event"><img src="images/event.png"></a></li>';
									}
								}

								if ($user->hasPermission('chief')) {
									if ($this->pageName == 'edit-restricted-page' && $_GET['id'] == 1 ||
										$this->pageName == 'chief' ||
										$this->pageName == 'chief-groups' ||
										$this->pageName == 'chief-teams' ||
										$this->pageName == 'chief-avatars' ||
										$this->pageName == 'chief-applications' ||
										$this->pageName == 'chief-my-crew' ||
										$this->pageName == 'chief-email') {
										echo '<li class="active"><a href="index.php?page=chief"><img src="images/chief.png"></a></li>';
									} else {
										echo '<li><a href="index.php?page=chief"><img src="images/chief.png"></a></li>';
									}
								}

                                if ($user->isGroupMember()) {
                                    if ($this->pageName == 'network') {
                                        echo '<li class="active"><a href="index.php?page=network"><img src="images/wifi.png"></a></li>';
                                    } else {
                                        echo '<li><a href="index.php?page=network"><img src="images/wifi.png"></a></li>';
                                    }
                                }

								if ($user->hasPermission('admin')) {
									if ($this->pageName == 'admin' ||
										$this->pageName == 'admin-events' ||
										$this->pageName == 'admin-permissions' ||
										$this->pageName == 'admin-seatmap' ||
										$this->pageName == 'admin-website' ||
										$this->pageName == 'admin-memberlist' ||
									        $this->pageName == 'admin-wsconsole') {
										echo '<li class="active"><a href="index.php?page=admin"><img src="images/admin.png"></a></li>';
									} else {
										echo '<li><a href="index.php?page=admin"><img src="images/admin.png"></a></li>';
									}
								}

								if ($user->hasPermission('developer')) {
									if ($this->pageName == 'developer' ||
										$this->pageName == 'developer-change-user' ||
                                        $this->pageName == 'developer-syslog' ||
                                        $this->pageName == 'developer-maintenance') {
										echo '<li class="active"><a href="index.php?page=developer"><img src="images/developer.png"></a></li>';
									} else {
										echo '<li><a href="index.php?page=developer"><img src="images/developer.png"></a></li>';
									}
								}

                                if ($user->hasPermission('compo.management')) {
                                    if ($this->pageName == 'compo-overview' || $this->pageName == 'compo-clans' || $this->pageName == 'compo-matches') {
                                        echo '<li class="active"><a href="index.php?page=compo-overview"><img src="images/compo.png"></a></li>';
                                    } else {
                                        echo '<li><a href="index.php?page=compo-overview"><img src="images/compo.png"></a></li>';
                                    }
                                }

                                if ($user->hasPermission('stats')) {
                                    if ($this->pageName == 'stats' ||
                                        $this->pageName == 'stats-ticketsales' ) {
                                        echo '<li class="active"><a href="index.php?page=stats"><img src="images/stats.png"></a></li>';
                                    } else {
                                        echo '<li><a href="index.php?page=stats"><img src="images/stats.png"></a></li>';
                                    }
                                }

								if ($this->pageName == 'user-profile' ||
									$this->pageName == 'user-history' ||
									$this->pageName == 'edit-profile' ||
									$this->pageName == 'edit-password' ||
									$this->pageName == 'edit-user-location' ||
									$this->pageName == 'edit-avatar') {
									echo '<li class="active"><a href="index.php?page=user-profile"><img src="images/user-profile.png"></a></li>';
								} else {
									echo '<li><a href="index.php?page=user-profile"><img src="images/user-profile.png"></a></li>';
								}
							}
						echo '</ul>';
					echo '</nav>';
				echo '</section>';
			echo '</body>';
			echo('<!-- Page generated in '.round((explode(' ', microtime())[0] + explode(' ', microtime())[1]) - $start, 4).' seconds with a peak memory consumption of ' . (memory_get_peak_usage(true)/1024/1024) . ' MiB-->');
		echo '</html>';
	}

	// Generates title based on current page / article.
	private function getTitle() {
		return Settings::name . ' Crew';
	}

	private function viewLogin() {
		echo '<form class="login" method="post">';
			echo '<table>';
				echo '<tr>';
					echo '<td><h2>Logg inn</h2></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Brukernavn, E-post eller Telefon:</td>';
					echo '<td><input type="text" name="identifier" required autofocus></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Passord:</td>';
					echo '<td><input type="password" name="password" required></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><input type="submit" value="Logg inn"><td>';
				echo '</tr>';
			echo '</table>';
		echo '</form>';
		echo 'Har du ikke en bruker? <a href="index.php?page=register">Registrer!</a>. Glemt passord? <a href="index.php?page=reset-password">Reset passordet ditt!</a>';
		echo '<p>Du har samme bruker her som på <a href="https://tickets.infected.no/">tickets.infected.no</a></p>';
	}

	private function viewNotifications() {
		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			$pendingApplicationList = null;
			$pendingAvatarList = null;

			if ($user->hasPermission('*')) {
				$pendingApplicationList = ApplicationHandler::getPendingApplications();

				if (!empty($pendingApplicationList)) {
					echo '<div class="information">Det er <b>' . count($pendingApplicationList) . '</b> søknader som venter på svar.</div>';
				}
			} else if ($user->hasPermission('chief.applications') &&
					   $user->isGroupMember()) {
				$group = $user->getGroup();
				$pendingApplicationList = ApplicationHandler::getPendingApplicationsByGroup($group);

				if (!empty($pendingApplicationList)) {
					echo '<div class="information">Det er <b>' . count($pendingApplicationList) . '</b> nye søknader til ' . $group->getTitle() . ', de venter på svar fra deg.</div>';
				}
			}

			if ($user->hasPermission('*') ||
				$user->hasPermission('chief.applications') && $user->isGroupMember()) {
				$pendingAvatarList = AvatarHandler::getPendingAvatars();

				if (!empty($pendingAvatarList)) {
					echo '<div class="information">Det er <b>' . count($pendingAvatarList) . '</b> ' . (count($pendingAvatarList) == 1 ? 'profilbilde' : 'profilbilder') . ' som venter på godkjenning.</div>';
				}
			}
		}
	}

	private function viewPage($pageName) {
		// Fetch the page object from the database and display it.
		$page = RestrictedPageHandler::getPageByName($pageName);

		if ($page != null) {
			if (Session::isAuthenticated()) {
				$user = Session::getCurrentUser();

				if ($user->hasPermission('*') ||
					$user->hasPermission('chief.my-crew')) {
					echo '<h3>' . $page->getTitle() . '<input type="button" value="Endre" onClick="editRestrictedPage(' . $page->getId() . ')"></h3>';
				} else {
					echo '<h3>' . $page->getTitle() . '</h3>';
				}

				echo $page->getContent();
			} else {
				echo 'Du har ikke tilgang til dette.';
			}
		} else {
			$directoryList = [Settings::api_path . 'pages',
								   			'pages'];
			$includedPages = [];
			$found = false;

			foreach ($directoryList as $directory) {
				$filePath = $directory . '/' . $pageName . '.php';

				if (!in_array($pageName, $includedPages) &&
					in_array($filePath, glob($directory . '/*.php'))) {
					// Make sure we don't include pages with same name twice,
					// and set the found varialbe so that we don't have to display the not found message.
					$includedPages[] = $pageName;
					$found = true;

					include_once $filePath;
				}
			}

			if (!$found) {
				echo '<article>';
					echo '<h1>Siden ble ikke funnet!</h1>';
				echo '</article>';
			}
		}
	}
}
Database::cleanup();
?>
