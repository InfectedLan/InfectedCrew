<?php
require_once 'session.php';
require_once 'settings.php';
require_once 'handlers/restrictedpagehandler.php';
require_once 'handlers/grouphandler.php';
require_once 'handlers/applicationhandler.php';
require_once 'handlers/avatarhandler.php';

class Site {
	private $pageName;
	
	public function __construct() {
		$this->pageName = isset($_GET['page']) ? strtolower($_GET['page']) : reset(RestrictedPageHandler::getPages())->getName();
	}
	
	// Execute the site.
	public function execute() {
		echo '<!DOCTYPE html>';
		echo '<html>';
			echo '<head>';
				echo '<title>' . $this->getTitle() . '</title>';
				echo '<meta name="description" content="' . Settings::description . '">';
				echo '<meta name="keywords" content="' . Settings::keywords . '">';
				echo '<meta name="author" content="' . implode(', ', Settings::$authors) . '">';
				echo '<meta charset="UTF-8">';
				echo '<link rel="shortcut icon" href="images/favicon.ico">';
				echo '<link rel="stylesheet" href="styles/style.css">';
				echo '<link rel="stylesheet" href="../api/scripts/chosen/chosen.css">';
				echo '<script src="../api/scripts/jquery.js"></script>';
				echo '<script src="../api/scripts/jquery.form.min.js"></script>';
				echo '<script src="../api/scripts/chosen/chosen.jquery.js"></script>';
				echo '<script src="../api/scripts/ckeditor/ckeditor.js"></script>';
				echo '<script src="../api/scripts/login.js"></script>';
				echo '<script src="../api/scripts/logout.js"></script>';
				echo '<script src="scripts/common.js"></script>';
				echo '<script>';
					echo '$(function() {';
						echo '$(\'.chosen-select\').chosen({';
							echo 'no_results_text: "Ingen resultater for "';
						echo '});';
					echo '});';
				echo '</script>';
			echo '</head>';
			echo '<body>';
				echo '<header>';
					echo '<img src="images/Infected_crew_logo.png">';
					echo '<ul>';
						if (Session::isAuthenticated()) {
							$user = Session::getCurrentUser();

							if (isset($_GET['page'])) {
								if ($user->isGroupMember()) {
									$groupPageList = RestrictedPageHandler::getPagesForGroup($user->getGroup()->getId());
									$groupPageNameList = array();
								
									foreach ($groupPageList as $value) {
										array_push($groupPageNameList, strtolower($value->getName()));
									}

									if ($this->pageName == 'my-crew' || 
										in_array($this->pageName, $groupPageNameList)) {
										$group = $user->getGroup();
										$teamList = $group->getTeams();
										$teamNameList = array();
										
										foreach ($teamList as $team) {
											array_push($teamNameList, strtolower($team->getName()));
										}
										
										echo '<li><a href="index.php?page=my-crew">Hele ' . $group->getTitle() . '</a></li>';
										
										foreach ($teamList as $team) {
											echo '<li><a href="index.php?page=my-crew&teamId=' . $team->getId() . '">' . $team->getTitle() . '</a></li>';
										}
										
										foreach ($groupPageList as $page) {
											if (strtolower($page->getName()) != strtolower($group->getName())) {
												if (!in_array(strtolower($page->getName()), $teamNameList)) {
													echo '<li><a href="index.php?page=' . $page->getName() . '">' . $page->getTitle() . '</a></li>';
												}
											}
										}
									}
								}
								
								if ($this->pageName == 'crew') {
									$groupList = GroupHandler::getGroups();
									
									foreach ($groupList as $value) {
										echo '<li><a href="index.php?page=crew&id=' . $value->getId() . '">' . $value->getTitle() . '</a></li>';
									}
								}
							
								if ($this->pageName == 'functions' || 
									$this->pageName == 'functions-search-users' ||
									$this->pageName == 'functions-my-crew' || 
									$this->pageName == 'functions-info' ||
									$this->pageName == 'functions-site-list-games' || 
									$this->pageName == 'functions-site-list-pages') {
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('functions.search-users') ||
										$user->isGroupLeader()) {
										echo '<li><a href="index.php?page=functions-search-users">Søk etter bruker</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('functions.my-crew') ||
										$user->isGroupLeader()) {
										echo '<li><a href="index.php?page=functions-my-crew">My Crew</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('functions.info')) {
										echo '<li><a href="index.php?page=functions-info">Infoskjerm</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('functions.site-list-games')) {
										echo '<li><a href="index.php?page=functions-site-list-games">Spill</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('functions.site-list-pages')) {
										echo '<li><a href="index.php?page=functions-site-list-pages">Infected.no</a></li>';
									}
								} else if ($this->pageName == 'chief' || 
									$this->pageName == 'edit-page' ||
									$this->pageName == 'chief-groups' ||
									$this->pageName == 'chief-teams' ||
									$this->pageName == 'chief-avatars' ||
									$this->pageName == 'chief-applications') {

									if ($user->hasPermission('*') ||
										$user->hasPermission('chief.home') ||
										$user->isGroupLeader()) {
										echo '<li><a href="index.php?page=edit-page&id=1">Hjem</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('chief.groups') ||
										$user->isGroupLeader()) {
										echo '<li><a href="index.php?page=chief-groups">Crew</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('chief.teams') ||
										$user->isGroupLeader()) {
										echo '<li><a href="index.php?page=chief-teams">Lag</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('chief.avatars') ||
										$user->isGroupLeader()) {
										echo '<li><a href="index.php?page=chief-avatars">Profilbilder</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('chief.applications') ||
										$user->isGroupLeader()) {
										echo '<li><a href="index.php?page=chief-applications">Søknader</a></li>';
									}
								} else if ($this->pageName == 'admin' || 
									$this->pageName == 'admin-events' || 
									$this->pageName == 'admin-permissions' || 
									$this->pageName == 'admin-change-user' || 
									$this->pageName == 'admin-seatmap') {
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('admin.events')) {
										echo '<li><a href="index.php?page=admin-events">Arrangementer</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('admin.permissions')) {
										echo '<li><a href="index.php?page=admin-permissions">Tilganger</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('admin.change-user')) {
										echo '<li><a href="index.php?page=admin-change-user">Logg inn som en annan</a></li>';
									}

									if ($user->hasPermission('*') ||
										$user->hasPermission('admin.seatmap')) {
										echo '<li><a href="index.php?page=admin-seatmap">Endre seatmap</a></li>';
									}
								}
							}
						}
					echo '</ul>';
					echo '<div class="user">';
						if (Session::isAuthenticated()) {
							$user = Session::getCurrentUser();
							
							echo 'Logget inn som ' . $user->getFullName() . ' <input type="button" value="Logg ut" onClick="logout()">';
						}
					echo '</div>';
				echo '</header>';
				echo '<div id="content">';
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
							// Only show notification on the default page.
							if (!isset($_GET['page'])) {
								$this->viewNotifications();
							}
							
							// View the page specified by "pageName" variable.
							$this->viewPage($this->pageName);
						} else {
							$publicPages = array('apply', 
												 'crew', 
												 'my-profile', 
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
				echo '</div>';

				if (Session::isAuthenticated()) {
					$user = Session::getCurrentUser();
				
					if ($user->hasPermission('*') ||
						$user->isGroupMember()) {
				
						if ($this->pageName == 'index' || !isset($_GET['page'])) {
							echo '<div class="homeicon" id="active"><a href="index.php"><img src="images/home.png"></a></div>';
						} else {
							echo '<div class="homeicon"><a href="index.php"><img src="images/home.png"></a></div>';   
						}
					}
					
					if ($this->pageName == 'crew') {
						echo '<div class="icon" id="active"><a href="index.php?page=crew"><img src="images/crew.png"></a></div>';
					} else {
						echo '<div class="icon"><a href="index.php?page=crew"><img src="images/crew.png"></a></div>';
					}
					
					if ($user->isGroupMember()) {
						$groupPageList = RestrictedPageHandler::getPagesForGroup($user->getGroup()->getId());
						$groupPageNameList = array();
							
						foreach ($groupPageList as $value) {
							array_push($groupPageNameList, strtolower($value->getName()));
						}
						
						if ($this->pageName == 'my-crew' ||
							in_array(strtolower($this->pageName), $groupPageNameList)) {
							echo '<div class="icon" id="active"><a href="index.php?page=my-crew"><img src="images/my-crew.png"></a></div>';
						} else {
							echo '<div class="icon"><a href="index.php?page=my-crew"><img src="images/my-crew.png"></a></div>';
						}
					} else {
						if ($this->pageName == 'apply') {
							echo '<div class="icon" id="active"><a href="index.php?page=apply"><img src="images/apply.png"></a></div>';
						} else {
							echo '<div class="icon"><a href="index.php?page=apply"><img src="images/apply.png"></a></div>';
						}
					}
					
					if ($user->hasPermission('*') ||
						$user->hasPermission('functions') ||
						$user->hasPermission('functions.search-users') ||
						$user->hasPermission('functions.my-crew') ||
						$user->hasPermission('functions.info') ||
						$user->hasPermission('functions.site-list-games') ||
						$user->hasPermission('functions.site-list-pages') ||
						$user->isGroupLeader()) {
						if ($this->pageName == 'functions' || 
							$this->pageName == 'functions-search-users' || 
							$this->pageName == 'functions-my-crew' || 
							$this->pageName == 'functions-info' ||
							$this->pageName == 'functions-site-list-games' || 
							$this->pageName == 'functions-site-list-pages') {
							echo '<div class="icon" id="active"><a href="index.php?page=functions"><img src="images/functions.png"></a></div>';
						} else {
							echo '<div class="icon"><a href="index.php?page=functions"><img src="images/functions.png"></a></div>';
						}
					}
					
					if ($user->hasPermission('*') ||
						$user->hasPermission('chief') ||
						$user->hasPermission('chief.groups') ||
						$user->hasPermission('chief.teams') ||
						$user->hasPermission('chief.avatars') ||
						$user->hasPermission('chief.applications') ||
						$user->isGroupLeader()) {
						if ($this->pageName == 'edit-page' && $_GET['id'] == 1 || 
							$this->pageName == 'chief' || 
							$this->pageName == 'chief-groups' ||
							$this->pageName == 'chief-teams' ||
							$this->pageName == 'chief-avatars' || 
							$this->pageName == 'chief-applications') {
							echo '<div class="icon" id="active"><a href="index.php?page=chief"><img src="images/chief.png"></a></div>';
						} else {
							echo '<div class="icon"><a href="index.php?page=chief"><img src="images/chief.png"></a></div>';
						}
					}

					if ($user->hasPermission('*') ||
						$user->hasPermission('admin') ||
						$user->hasPermission('admin.events') ||
						$user->hasPermission('admin.permissions') ||
						$user->hasPermission('admin.change-user') ||
						$user->hasPermission('admin.seatmap')) {
						if ($this->pageName == 'admin' || 
							$this->pageName == 'admin-events' ||
							$this->pageName == 'admin-permissions' ||
							$this->pageName == 'admin-change-user' ||
							$this->pageName == 'admin-seatmap') {
							echo ' <div class="icon" id="active"><a href="index.php?page=admin"><img src="images/admin.png"></a></div>';
						} else {
							echo ' <div class="icon"><a href="index.php?page=admin"><img src="images/admin.png"></a></div>';
						}
					}

					if ($this->pageName == 'my-profile') {
						echo '<div class="icon" id="active"><a href="index.php?page=my-profile"><img src="images/my-profile.png"></a></div>';
					} else {
						echo '<div class="icon"><a href="index.php?page=my-profile"><img src="images/my-profile.png"></a></div>';
					}
				}
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
					echo '<td>Brukernavn:</td>';
					echo '<td><input type="text" name="username" required autofocus></td>';
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
			
			if ($user->hasPermission('*') ||
				$user->hasPermission('chief.applications')) {
				$pendingApplicationList = ApplicationHandler::getPendingApplications();
				$pendingAvatarList = AvatarHandler::getPendingAvatars();
			} else if ($user->isGroupLeader() && $user->isGroupMember()) {
				$pendingApplicationList = ApplicationHandler::getPendingApplicationsForGroup($user->getGroup());
				$pendingAvatarList = AvatarHandler::getPendingAvatars();
			}

			if (!empty($pendingApplicationList)) {
				echo '<div class="information">Det er <b>' . count($pendingApplicationList) . '</b> søknader som venter på svar.</div>';
			}
			
			if (!empty($pendingAvatarList)) {
				echo '<div class="information">Det er <b>' . count($pendingAvatarList) . '</b> profilbilder som venter på godkjenning.</div>';
			}
		}
	}
	
	private function viewPage($pageName) {
		// Fetch the page object from the database and display it.
		$page = RestrictedPageHandler::getPageByName($pageName);
		
		if ($page != null) {
			echo '<h3>' . $page->getTitle() . '</h3>';
			echo $page->getContent();
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