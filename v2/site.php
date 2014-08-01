<?php
require_once 'session.php';
require_once 'settings.php';
require_once 'handlers/restrictedpagehandler.php';
require_once 'handlers/grouphandler.php';
	
class Site {
	// Variable definitions.
	private $pageName;
	
	public function __construct() {
		// Set the variables.
		$this->pageName = isset($_GET['page']) ? strtolower($_GET['page']) : null;
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
				echo '<link rel="stylesheet" type="text/css" href="styles/style.css">';
				echo '<script src="scripts/jquery.js"></script>';
				echo '<script src="scripts/session.js"></script>';
				echo '<script src="scripts/ckeditor/ckeditor.js"></script>';
			echo '</head>';
			echo '<body>';
				echo '<header>';
					echo '<img src="images/Infected_crew_logo.png">';
					echo '<ul>';
						if (Session::isAuthenticated()) {
							$user = Session::getCurrentUser();

							if (isset($_GET['page']) && 
								$user->isGroupMember()) {
								$group = $user->getGroup();
								
								$groupPageList = RestrictedPageHandler::getPagesForGroup($user->getGroup()->getId());
								$groupPageNameList = array();
							
								foreach ($groupPageList as $value) {
									array_push($groupPageNameList, strtolower($value->getName()));
								}
								
								if ($this->pageName == 'crew') {
									$groupList = GroupHandler::getGroups();
									
									foreach ($groupList as $value) {
										echo '<li><a href="index.php?page=crew&id=' . $value->getId() . '">' . $value->getTitle() . '</a></li>';
									}
								} else if ($this->pageName == 'my-crew' || 
									in_array($this->pageName, $groupPageNameList)) {
									$teamList = $group->getTeams();
									$teamNameList = array();
									
									foreach ($teamList as $team) {
										array_push($teamNameList, strtolower($team->getName()));
									}
									
									echo '<li><a href="index.php?page=my-crew">' . $group->getTitle() . '</a></li>';
									
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
								} else if ($this->pageName == 'functions' || 
									$this->pageName == 'functions-site-list-pages' || 
									$this->pageName == 'functions-mycrew' || 
									$this->pageName == 'functions-site-list-games' || 
									$this->pageName == 'functions-info') {
									
									if ($user->hasPermission('functions.site-list-pages') ||
										$user->hasPermission('admin') || 
										$user->hasPermission('site-admin')) {
										echo '<li><a href="index.php?page=functions-site-list-pages">Infected.no</a></li>';
									}
									
									if ($user->hasPermission('functions.mycrew') ||
										$user->isGroupLeader() || 
										$user->hasPermission('admin') || 
										$user->hasPermission('crew-admin')) {
										echo '<li><a href="index.php?page=functions-mycrew">My Crew</a></li>';
									}
									
									if ($user->hasPermission('functions.site-list-games') ||
										$user->getGroup()->getId() == 26 || 
										$user->hasPermission('admin') || 
										$user->hasPermission('crew-admin')) {
										echo '<li><a href="index.php?page=functions-site-list-games">Spill</a></li>';
									}
									
									if ($user->hasPermission('functions.info') ||
										$user->getGroup()->getId() == 15 || 
										$user->hasPermission('admin') || 
										$user->hasPermission('crew-admin')) {
										echo '<li><a href="index.php?page=functions-info">Infoskjerm</a></li>';
									}
								} else if ($this->pageName == 'chief' || 
									$this->pageName == 'edit-page' || 
									$this->pageName == 'chief-groups' || 
									$this->pageName == 'chief-teams' || 
									$this->pageName == 'chief-applications' || 
									$this->pageName == 'chief-avatars') {
									
									if ($user->hasPermission('chief.home') ||
										$user->isGroupLeader() ||
										$user->hasPermission('admin') ||
										$user->hasPermission('crew-admin')) {
										echo '<li><a href="index.php?page=edit-page&site=1&id=1&returnPage=index.php?page=chief">Hjem</a></li>';
									}
									
									if ($user->hasPermission('chief.groups') ||
										$user->isGroupLeader() ||
										$user->hasPermission('admin') ||
										$user->hasPermission('crew-admin')) {
										echo '<li><a href="index.php?page=chief-groups">Grupper</a></li>';
									}
									
									if ($user->hasPermission('chief.teams') ||
										$user->isGroupLeader() ||
										$user->hasPermission('admin') ||
										$user->hasPermission('crew-admin')) {
										echo '<li><a href="index.php?page=chief-teams">Lag</a></li>';
									}
									
									if ($user->hasPermission('chief.applications') ||
										$user->isGroupLeader() ||
										$user->hasPermission('admin') ||
										$user->hasPermission('crew-admin')) {
										echo '<li><a href="index.php?page=chief-applications">Søknader</a></li>';
									}
									
									if ($user->hasPermission('chief.avatars') ||
										$user->isGroupLeader() ||
										$user->hasPermission('admin') ||
										$user->hasPermission('crew-admin')) {
										echo '<li><a href="index.php?page=chief-avatars">Profilbilder</a></li>';
									}
								}
							}
							//Admin stuff
							if ($this->pageName == 'admin' || 
								$this->pageName == 'admin-events') {
								
								if ($user->hasPermission('admin.events') ||
									$user->hasPermission('admin')) {
									echo '<li><a href="index.php?page=admin-events">Arrangementer</a></li>';
								}
								
								if ($user->hasPermission('admin.changeuser') ||
									$user->hasPermission('admin')) {
									echo '<li><a href="index.php?page=admin-changeuser">Logg inn som en annan</a></li>';
								}

								if ($user->hasPermission('admin.seatmap') ||
									$user->hasPermission('admin')) {
									echo '<li><a href="index.php?page=admin-seatmap">Edit seatmaps</a></li>';
								}
							}
						}
					echo '</ul>';
					echo '<div class="user">';
						if (Session::isAuthenticated()) {
							$user = Session::getCurrentUser();
							
							echo 'Logget inn som ' . $user->getFullName() . '. <a class="logout" href=".">Logg ut</a>';
						}
					echo '</div>';
				echo '</header>';
				echo '<div id="content">';
					// TODO: Implement this in a better way.
					if (isset($_GET["error"])) {
						echo '<div class="warning">' . XssBegone($_GET["error"]) . '</div>';
					}
					
					if (isset($_GET["info"])) {
						echo '<div class="information">' . XssBegone($_GET["info"]) . '</div>';
					}
					
					if (Session::isAuthenticated()) {
						$user = Session::getCurrentUser();
						
						if ($user->isGroupMember()) {
							if (isset($_GET['page'])) {
								// View the page specified by "pageName" variable.
								$this->viewPage($this->pageName);
							} else {
								// View the page specified by "pageName" variable.
								$this->viewNotifications();
								$this->viewPage('home');
							}
						} else {
							$publicPages = array('apply', 
												 'crew', 
												 'profile', 
												 'edit-profile', 
												 'edit-password', 
												 'edit-avatar');
							
							if (in_array($this->pageName, $publicPages)) {
								// View the page specified by "pageName" variable.
								$this->viewPage($this->pageName);
							}
						}
					} else {
						if (isset($_GET['page'])) {
							$publicPages = array('register',
												 'reset-password');
							
							if (in_array($this->pageName, $publicPages)) {
								// View the page specified by "pageName" variable.
								$this->viewPage($this->pageName);
							}
						} else {
							$this->viewLogin();
						}
					}
				echo '</div>';

				if (Session::isAuthenticated()) {
					$user = Session::getCurrentUser();
				
					if ($this->pageName == 'index' || !isset($_GET['page'])) {
						echo '<div class="homeicon" id="active"><a href="index.php"><img src="images/home.png"></a></div>';
					} else {
						echo '<div class="homeicon"><a href="index.php"><img src="images/home.png"></a></div>';   
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
							echo '<div class="icon" id="active"><a href="index.php?page=my-crew"><img src="images/mycrew.png"></a></div>';
						} else {
							echo '<div class="icon"><a href="index.php?page=my-crew"><img src="images/mycrew.png"></a></div>';
						}
					} else {
						if ($this->pageName == 'apply') {
							echo '<div class="icon" id="active">';
						} else {
							echo '<div class="icon">';
						}
					
							echo '<a href="index.php?page=apply"><img src="images/apply.png"></a>';
						echo '</div>';
					}
					
					if ($user->isGroupMember()) {
						if ($user->getGroup()->getId() == 15 || 
							$user->getGroup()->getId() == 26 || 
							$user->isGroupLeader() || 
							$user->hasPermission('admin') || 
							$user->hasPermission('crew-admin')) {
							if ($this->pageName == 'functions' || 
								$this->pageName == 'functions-mycrew' || 
								$this->pageName == 'functions-site-list-pages' || 
								$this->pageName == 'functions-site-list-games' || 
								$this->pageName == 'functions-info') {
								echo '<div class="icon" id="active"><a href="index.php?page=functions"><img src="images/functions.png"></a></div>';
							} else {
								echo '<div class="icon"><a href="index.php?page=functions"><img src="images/functions.png"></a></div>';
							}
						}
						
						if ($user->isGroupLeader() ||
							$user->hasPermission('admin') || 
							$user->hasPermission('crew-admin')) {
							if ($this->pageName == 'edit-page' && $_GET['site'] == 1 && $_GET['id'] == 1 || 
								$this->pageName == 'chief' || 
								$this->pageName == 'chief-applications' || 
								$this->pageName == 'chief-avatars' || 
								$this->pageName == 'chief-teams') {
								echo '<div class="icon" id="active"><a href="index.php?page=chief"><img src="images/chief.png"></a></div>';
							} else {
								echo '<div class="icon"><a href="index.php?page=chief"><img src="images/chief.png"></a></div>';
							}
						}
					}

					if ($user->hasPermission('admin') ||
						$user->hasPermission('crew-admin')) {
						if ($this->pageName == 'admin' || 
							$this->pageName == 'admin-events') {
							echo ' <div class="icon" id="active"><a href="index.php?page=admin"><img src="images/admin.png"></a></div>';
						} else {
							echo ' <div class="icon"><a href="index.php?page=admin"><img src="images/admin.png"></a></div>';
						}
					}

					if ($this->pageName == 'profile') {
						echo '<div class="icon" id="active"><a href="index.php?page=profile"><img src="images/profile.png"></a></div>';
					} else {
						echo '<div class="icon"><a href="index.php?page=profile"><img src="images/profile.png"></a></div>';
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
		echo '<form class="login">';
			echo '<table>';
				echo '<tr>';
					echo '<td><h2>Logg inn</h2></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Brukernavn:</td>';
					echo '<td><input type="text" name="username"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Passord:</td>';
					echo '<td><input type="password" name="password"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><input type="submit" id="submit" value="Logg inn"><td>';
				echo '</tr>';
			echo '</table>';
		echo '</form>';
		echo 'Har du ikke en bruker? <a href="index.php?page=register">Registrer!</a>. Glemt passord? <a href="index.php?page=forgotten">Reset passordet ditt!</a>';
		echo '<p>Du har samme bruker her som på <a href="https://tickets.infected.no/">tickets.infected.no</a></p>';
	}
	
	private function viewNotifications() {
		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			// TODO: Rewrite this.
			if ($user->isGroupMember() && $user->isGroupLeader()) {
				$soknads = mysql_query("SELECT * FROM `soknader` WHERE `crew` = '" . $user->getGroup()->getName() . "' AND `status`='PROCESSING';"); // TODO: Update this.
			
				if ($soknads != FALSE && mysql_num_rows($soknads) > 0) {
					echo '<div class="information">Du har <b>' . mysql_num_rows($soknads) . '</b> søknader som venter på svar!</div>';
				}
			
				$pics = mysql_query("SELECT * FROM `avatars` WHERE `state`='1';");
			
				if ($pics != FALSE && mysql_num_rows($pics) > 0) {
					echo '<div class="information">Du har <b>' . mysql_num_rows($pics) . '</b> avatarer som må godkjennes!</div>';
				}
			}
		}
	}
	
	private function viewPage($pageName) {
		// Fetch the page object from the database and display it.
		$page = RestrictedPageHandler::getPageByName($pageName);
		
		if ($page != null) {
			$page->display();
		} else {
			$directory = 'pages/';
			$fileName = $directory . $pageName . '.php';
			
			// If page doesn't exist in the database, check if there is a .php file that do. Else an error is shown.
			if (in_array($fileName, glob($directory . '*.php'))) {
				include $fileName;
			} else {
				echo '<article>';
					echo '<h1>Siden ble ikke funnet!</h1>';
				echo '</article>';
			}
		}
	}
}
?>