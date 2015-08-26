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
require_once 'settings.php';
require_once 'handlers/restrictedpagehandler.php';
require_once 'handlers/grouphandler.php';
require_once 'handlers/applicationhandler.php';
require_once 'handlers/avatarhandler.php';

class Site {
	private $pageName;

	public function __construct() {
		$this->pageName = isset($_GET['page']) ? strtolower($_GET['page']) : 'my-crew';
	}

	// Execute the site.
	public function execute() {
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
				echo '<script src="../api/scripts/jquery-1.11.1.min.js"></script>';
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

									$pageNameList = array();

									foreach ($pageList as $page) {
										array_push($pageNameList, strtolower($page->getName()));
									}

									if ($this->pageName == 'my-crew' ||
										in_array($this->pageName, $pageNameList)) {
										$teamList = $group->getTeams();
										$teamNameList = array();

										foreach ($teamList as $team) {
											array_push($teamNameList, strtolower($team->getName()));
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
										   $this->pageName == 'event-seatmap' ||
										   $this->pageName == 'event-screen' ||
										   $this->pageName == 'event-agenda' ||
										   $this->pageName == 'event-compo' ||
										   $this->pageName == 'event-memberlist') {

									if ($user->hasPermission('event.checkin')) {
										echo '<li><a' . ($this->pageName == 'event-checkin' ? ' class="active"' : null) . ' href="index.php?page=event-checkin">Innsjekk</a></li>';
									}

									if ($user->hasPermission('event.seatmap')) {
										echo '<li><a' . ($this->pageName == 'event-seatmap' ? ' class="active"' : null) . ' href="index.php?page=event-seatmap">Seatmap</a></li>';
									}

									if ($user->hasPermission('event.screen')) {
										echo '<li><a' . ($this->pageName == 'event-screen' ? ' class="active"' : null) . ' href="index.php?page=event-screen">Skjerm</a></li>';
									}

									if ($user->hasPermission('event.agenda')) {
										echo '<li><a' . ($this->pageName == 'event-agenda' ? ' class="active"' : null) . ' href="index.php?page=event-agenda">Agenda</a></li>';
									}

									if ($user->hasPermission('event.compo')) {
										echo '<li><a' . ($this->pageName == 'event-compo' ? ' class="active"' : null) . ' href="index.php?page=event-compo">Compo</a></li>';
									}

									if ($user->hasPermission('event.memberlist')) {
										echo '<li><a' . ($this->pageName == 'event-memberlist' ? ' class="active"' : null) . ' href="index.php?page=event-memberlist">Medlemsliste</a></li>';
									}

									if ($user->hasPermission('event.table-labels')) {
										echo '<li><a href="../api/pages/utils/printTableLabels.php">Print bordlapper</a></li>';
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
								} else if ($this->pageName == 'admin' ||
									$this->pageName == 'admin-events' ||
									$this->pageName == 'admin-permissions' ||
									$this->pageName == 'admin-seatmap' ||
									$this->pageName == 'admin-website') {

									if ($user->hasPermission('admin.events')) {
										echo '<li><a' . ($this->pageName == 'admin-events' ? ' class="active"' : null) . ' href="index.php?page=admin-events">Arrangementer</a></li>';
									}

									if ($user->hasPermission('admin.permissions')) {
										echo '<li><a' . ($this->pageName == 'admin-permissions' ? ' class="active"' : null) . ' href="index.php?page=admin-permissions">Rettigheter</a></li>';
									}

									if ($user->hasPermission('admin.seatmap')) {
										echo '<li><a' . ($this->pageName == 'admin-seatmap' ? ' class="active"' : null) . ' href="index.php?page=admin-seatmap">Endre seatmap</a></li>';
									}

									if ($user->hasPermission('admin.website')) {
										echo '<li><a' . ($this->pageName == 'admin-website' || $this->pageName == 'edit-page' ? ' class="active"' : null) . ' href="index.php?page=admin-website">Endre hovedsiden</a></li>';
									}
								} else if ($this->pageName == 'developer' ||
									$this->pageName == 'developer-change-user') {

									if ($user->hasPermission('*') ||
										$user->hasPermission('developer.change-user')) {
										echo '<li><a' . ($this->pageName == 'developer-change-user' ? ' class="active"' : null) . ' href="index.php?page=developer-change-user">Logg inn som en annan</a></li>';
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
								$publicPages = array('apply',
													 'all-crew',
													 'user-profile',
													 'edit-profile',
													 'edit-password',
													 'edit-avatar');

								if (in_array($this->pageName, $publicPages)) {
									$this->viewPage($this->pageName);
								} else {
									$this->viewPage('crew');
								}
							}
						} else {
							$publicPages = array('register',
												 'activation',
												 'reset-password');

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
										$this->pageName == 'event-seatmap' ||
										$this->pageName == 'event-screen' ||
										$this->pageName == 'event-agenda' ||
										$this->pageName == 'event-compo' ||
										$this->pageName == 'event-memberlist') {
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

								if ($user->hasPermission('admin')) {
									if ($this->pageName == 'admin' ||
										$this->pageName == 'admin-events' ||
										$this->pageName == 'admin-permissions' ||
										$this->pageName == 'admin-change-user' ||
										$this->pageName == 'admin-seatmap' ||
										$this->pageName == 'admin-website') {
										echo '<li class="active"><a href="index.php?page=admin"><img src="images/admin.png"></a></li>';
									} else {
										echo '<li><a href="index.php?page=admin"><img src="images/admin.png"></a></li>';
									}
								}

								if ($user->hasPermission('developer')) {
									if ($this->pageName == 'developer' ||
										$this->pageName == 'developer-change-user') {
										echo '<li class="active"><a href="index.php?page=developer"><img src="images/developer.png"></a></li>';
									} else {
										echo '<li><a href="index.php?page=developer"><img src="images/developer.png"></a></li>';
									}
								}

								if ($this->pageName == 'user-profile' ||
									$this->pageName == 'edit-user-location') {
									echo '<li class="active"><a href="index.php?page=user-profile"><img src="images/user-profile.png"></a></li>';
								} else {
									echo '<li><a href="index.php?page=user-profile"><img src="images/user-profile.png"></a></li>';
								}
							}
						echo '</ul>';
					echo '</nav>';
				echo '</section>';
			echo '</body>';
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
			$directoryList = array(Settings::api_path . 'pages',
								   'pages');
			$includedPages = array();
			$found = false;

			foreach ($directoryList as $directory) {
				$filePath = $directory . '/' . $pageName . '.php';

				if (!in_array($pageName, $includedPages) &&
					in_array($filePath, glob($directory . '/*.php'))) {
					// Make sure we don't include pages with same name twice,
					// and set the found varialbe so that we don't have to display the not found message.
					array_push($includedPages, $pageName);
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
?>
