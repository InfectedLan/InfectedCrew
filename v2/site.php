<?php
require_once 'session.php';
require_once 'settings.php';
require_once 'handlers/restrictedpagehandler.php';
require_once 'handlers/grouphandler.php';
	
class Site {
	private $pageName;
	
	public function __construct() {
		$this->pageName = isset($_GET['page']) ? strtolower($_GET['page']) : 'home';
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
				echo '<script src="../api/scripts/jquery.js"></script>';
				echo '<script src="../api/scripts/jquery.form.min.js"></script>';
				echo '<script src="../api/scripts/login.js"></script>';
				echo '<script src="../api/scripts/logout.js"></script>';
				echo '<script src="../api/scripts/ckeditor/ckeditor.js"></script>';
				echo '<script src="scripts/common.js"></script>';
				
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
									}
								}
								
								if ($this->pageName == 'crew') {
									$groupList = GroupHandler::getGroups();
									
									foreach ($groupList as $value) {
										echo '<li><a href="index.php?page=crew&id=' . $value->getId() . '">' . $value->getTitle() . '</a></li>';
									}
								} else if ($this->pageName == 'functions' || 
									$this->pageName == 'functions-find-user' ||
									$this->pageName == 'functions-site-list-pages' || 
									$this->pageName == 'functions-mycrew' || 
									$this->pageName == 'functions-site-list-games' || 
									$this->pageName == 'functions-info') {
									
									if ($user->hasPermission('functions.find.user') ||
										$user->hasPermission('admin') ||
										$user->isGroupLeader()) {
										echo '<li><a href="index.php?page=functions-find-user">Søk etter bruker</a></li>';
									}
									
									if ($user->hasPermission('functions.site-list-pages') ||
										$user->hasPermission('admin')) {
										echo '<li><a href="index.php?page=functions-site-list-pages">Infected.no</a></li>';
									}
									
									if ($user->hasPermission('functions.mycrew') ||
										$user->hasPermission('admin') ||
										$user->isGroupLeader()) {
										echo '<li><a href="index.php?page=functions-mycrew">My Crew</a></li>';
									}
									
									if ($user->hasPermission('functions.site-list-games') ||
										$user->hasPermission('admin')) {
										echo '<li><a href="index.php?page=functions-site-list-games">Spill</a></li>';
									}
									
									if ($user->hasPermission('functions.info') ||
										$user->hasPermission('admin')) {
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
										$user->hasPermission('admin')) {
										echo '<li><a href="index.php?page=edit-page&site=1&id=1&returnPage=index.php?page=chief">Hjem</a></li>';
									}
									
									if ($user->hasPermission('chief.groups') ||
										$user->isGroupLeader() ||
										$user->hasPermission('admin')) {
										echo '<li><a href="index.php?page=chief-groups">Crew</a></li>';
									}
									
									if ($user->hasPermission('chief.teams') ||
										$user->isGroupLeader() ||
										$user->hasPermission('admin')) {
										echo '<li><a href="index.php?page=chief-teams">Lag</a></li>';
									}
									
									if ($user->hasPermission('chief.applications') ||
										$user->isGroupLeader() ||
										$user->hasPermission('admin')) {
										echo '<li><a href="index.php?page=chief-applications">Søknader</a></li>';
									}
									
									if ($user->hasPermission('chief.avatars') ||
										$user->isGroupLeader() ||
										$user->hasPermission('admin')) {
										echo '<li><a href="index.php?page=chief-avatars">Profilbilder</a></li>';
									}
								}
							}
							
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
							
							echo 'Logget inn som ' . $user->getFullName() . '. <input type="button" value="Logg ut" onClick="logout()">';
						}
					echo '</div>';
				echo '</header>';
				echo '<div id="content">';
					// TODO: Implement this in a better way.
					echo '<div id="error" class="warning" style="display:none;">';
						echo '<span id="innerError">';

						echo '</span>';
						echo '<div id="errorClose" class="closeButton">';
							echo '<i>lukk</i>';
						echo '</div>';
					echo '</div>';
					echo '<div id="info" class="information" style="display:none;">';
						echo '<span id="innerInfo">';

						echo '</span>';
						echo '<div id="infoClose" class="closeButton">';
							echo '<i>lukk</i>';
						echo '</div>';
					echo '</div>';

					if (isset($_GET['error'])) {
						echo '<script>error("' . $_GET['error'] . '");</script>';
					}
					
					if (isset($_GET['info'])) {
						echo '<script>info("' . $_GET['info'] . '");</script>';
					}
					
					if (Session::isAuthenticated()) {
						$user = Session::getCurrentUser();
						
						if ($user->isGroupMember() ||
							$user->hasPermission('admin')) {
							// View the page specified by "pageName" variable.
							$this->viewNotifications();
							$this->viewPage($this->pageName);
						} else {
							$publicPages = array('application', 
												 'crew', 
												 'profile', 
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
						if (isset($_GET['page'])) {
							$publicPages = array('register',
												 'activation',
												 'reset-password');
							
							if (in_array($this->pageName, $publicPages)) {
								$this->viewPage($this->pageName);
							} else {
								$this->viewLogin();
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
						if ($this->pageName == 'application') {
							echo '<div class="icon" id="active">';
						} else {
							echo '<div class="icon">';
						}
					
							echo '<a href="index.php?page=application"><img src="images/apply.png"></a>';
						echo '</div>';
					}
					
					if ($user->isGroupMember() ||
						$user->hasPermission('admin')) {
						
						if ($this->pageName == 'functions' || 
							$this->pageName == 'functions-mycrew' || 
							$this->pageName == 'functions-site-list-pages' || 
							$this->pageName == 'functions-site-list-games' || 
							$this->pageName == 'functions-info') {
							echo '<div class="icon" id="active"><a href="index.php?page=functions"><img src="images/functions.png"></a></div>';
						} else {
							echo '<div class="icon"><a href="index.php?page=functions"><img src="images/functions.png"></a></div>';
						}
						
						
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

					if ($user->hasPermission('admin')) {
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
		echo '<form class="login" method="post">';
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
		echo 'Har du ikke en bruker? <a href="index.php?page=register">Registrer!</a>. Glemt passord? <a href="index.php?page=reset-password">Reset passordet ditt!</a>';
		echo '<p>Du har samme bruker her som på <a href="https://tickets.infected.no/">tickets.infected.no</a></p>';
	}
	
	private function viewNotifications() {
		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			// TODO: Rewrite this.
			/* if ($user->isGroupMember() && $user->isGroupLeader()) {
				$soknads = mysql_query("SELECT * FROM `soknader` WHERE `crew` = '" . $user->getGroup()->getName() . "' AND `status`='PROCESSING';"); // TODO: Update this.
			
				if ($soknads != FALSE && mysql_num_rows($soknads) > 0) {
					echo '<div class="information">Du har <b>' . mysql_num_rows($soknads) . '</b> søknader som venter på svar!</div>';
				}
			
				$pics = mysql_query("SELECT * FROM `avatars` WHERE `state`='1';");
			
				if ($pics != FALSE && mysql_num_rows($pics) > 0) {
					echo '<div class="information">Du har <b>' . mysql_num_rows($pics) . '</b> avatarer som må godkjennes!</div>';
				}
			} */
		}
	}
	
	private function viewPage($pageName) {
		// Fetch the page object from the database and display it.
		$page = RestrictedPageHandler::getPageByName($pageName);
		
		if ($page != null) {
			echo '<h1>' . $page->getTitle() . '</h1>';
			echo $page->getContent();
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