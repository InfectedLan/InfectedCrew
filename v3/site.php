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
require_once 'handlers/avatarhandler.php';
require_once 'handlers/grouphandler.php';
require_once 'handlers/restrictedpagehandler.php';
require_once 'utils/dateutils.php';
require_once 'utils/stringutils.php';

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
		  		echo '<title>' . Settings::name . ' Crew</title>';
				echo '<meta name="description" content="' . Settings::description . '">';
				echo '<meta name="keywords" content="' . Settings::keywords . '">';
				echo '<meta name="author" content="halvors and petterroea">';
				echo '<meta charset="UTF-8">';
				echo '<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">';
				echo '<link rel="shortcut icon" href="images/favicon.ico">';
				//<!-- Bootstrap 3.3.4 -->
				echo '<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />';
				//<!-- FontAwesome 4.3.0 -->
				echo '<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />';
				//<!-- Theme style -->
				echo '<link href="dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />';

				if (Session::isAuthenticated()) {
					//<!-- Ionicons 2.0.0 -->
					echo '<link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />';
					//<!-- AdminLTE Skins. Choose a skin from the css/skins 
					//	   folder instead of downloading all of them to reduce the load. -->
					echo '<link href="dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />';
					//<!-- iCheck -->
					echo '<link href="plugins/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />';
					//<!-- Morris chart -->
					echo '<link href="plugins/morris/morris.css" rel="stylesheet" type="text/css" />';
					//<!-- jvectormap -->
					echo '<link href="plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />';
					//<!-- Date Picker -->
					echo '<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />';
					//<!-- Daterange picker -->
					echo '<link href="plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />';
					//<!-- bootstrap wysihtml5 - text editor -->
					echo '<link href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />';
				} else {
				    //<!-- iCheck -->
				    echo '<link href="plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />';
				}

				//<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
				//<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
				echo '<!--[if lt IE 9]>';
					echo '<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>';
					echo '<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>';
				echo '<![endif]-->';

				//<!-- jQuery 2.1.4 -->
				echo '<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>';
				//<!-- iCheck -->
				echo '<script src="plugins/iCheck/icheck.min.js" type="text/javascript"></script>';
				
				echo '<script>';
					echo '(function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){';
					echo '(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),';
					echo 'm=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)';
					echo '})(window,document,\'script\',\'//www.google-analytics.com/analytics.js\',\'ga\');';

					echo 'ga(\'create\', \'UA-54254513-3\', \'auto\');';
					echo 'ga(\'send\', \'pageview\');';
				echo '</script>';
		  	echo '</head>';
		  	
			if (Session::isAuthenticated()) {
				$user = Session::getCurrentUser();

				echo '<body class="skin-blue sidebar-mini">';
					echo '<div class="wrapper">';
				  		echo '<header class="main-header">';
							echo '<!-- Logo -->';
							echo '<a href="." class="logo">';
							  		//<!-- mini logo for sidebar mini 50x50 pixels -->
							  		echo '<span class="logo-mini"><b>' . Settings::name[0] . '</b>C</span>';

							  		//<!-- logo for regular state and mobile devices -->
						  			echo '<span class="logo-lg"><b>' . Settings::name . '</b> Crew</span>';
							echo '</a>';
							echo '<!-- Header Navbar: style can be found in header.less -->';
							echo '<nav class="navbar navbar-static-top" role="navigation">';
						  		echo '<!-- Sidebar toggle button-->';
						  		echo '<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">';
									echo '<span class="sr-only">Toggle navigation</span>';
						  		echo '</a>';
						  		echo '<div class="navbar-custom-menu">';
						   			echo '<ul class="nav navbar-nav">';
						   				/*
							  			echo '<!-- Messages: style can be found in dropdown.less-->';
							  			echo '<li class="dropdown messages-menu">';
											echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
								  				echo '<i class="fa fa-envelope-o"></i>';
								  				echo '<span class="label label-success">4</span>';
											echo '</a>';
											echo '<ul class="dropdown-menu">';
							  					echo '<li class="header">You have 4 messages</li>';
							  					echo '<li>';
													echo '<!-- inner menu: contains the actual data -->';
													echo '<ul class="menu">';
								  						echo '<li><!-- start message -->';
															echo '<a href="#">';
									  							echo '<div class="pull-left">';
																	echo '<img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>';
															  	echo '</div>';
															  	echo '<h4>';
																	echo 'Support Team';
																	echo '<small><i class="fa fa-clock-o"></i> 5 mins</small>';
															  	echo '</h4>';
															  	echo '<p>Why not buy a new awesome theme?</p>';
															echo '</a>';
								 						echo '</li><!-- end message -->';
								  						echo '<li>';
															echo '<a href="#">';
															  	echo '<div class="pull-left">';
																	echo '<img src="dist/img/user3-128x128.jpg" class="img-circle" alt="user image"/>';
															  	echo '</div>';
															  	echo '<h4>';
																	echo 'AdminLTE Design Team';
																	echo '<small><i class="fa fa-clock-o"></i> 2 hours</small>';
															  	echo '</h4>';
															  	echo '<p>Why not buy a new awesome theme?</p>';
															echo '</a>';
														echo '</li>';
														echo '<li>';
															echo '<a href="#">';
															  	echo '<div class="pull-left">';
																	echo '<img src="dist/img/user4-128x128.jpg" class="img-circle" alt="user image"/>';
															  	echo '</div>';
															  	echo '<h4>';
																	echo 'Developers';
																	echo '<small><i class="fa fa-clock-o"></i> Today</small>';
															  	echo '</h4>';
															  	echo '<p>Why not buy a new awesome theme?</p>';
															echo '</a>';
														echo '</li>';
														echo '<li>';
															echo '<a href="#">';
															  	echo '<div class="pull-left">';
																	echo '<img src="dist/img/user3-128x128.jpg" class="img-circle" alt="user image"/>';
															  	echo '</div>';
															  	echo '<h4>';
																	echo 'Sales Department';
																	echo '<small><i class="fa fa-clock-o"></i> Yesterday</small>';
															  	echo '</h4>';
															  	echo '<p>Why not buy a new awesome theme?</p>';
															echo '</a>';
														echo '</li>';
														echo '<li>';
															echo '<a href="#">';
															  	echo '<div class="pull-left">';
																	echo '<img src="dist/img/user4-128x128.jpg" class="img-circle" alt="user image"/>';
															  	echo '</div>';
															  	echo '<h4>';
																	echo 'Reviewers';
																	echo '<small><i class="fa fa-clock-o"></i> 2 days</small>';
															  	echo '</h4>';
															  	echo '<p>Why not buy a new awesome theme?</p>';
															echo '</a>';
														echo '</li>';
													echo '</ul>';
												echo '</li>';
												echo '<li class="footer"><a href="#">See All Messages</a></li>';
											echo '</ul>';
										echo '</li>';
										*/

										$pendingApplicationList = null;
										$pendingAvatarList = null;

										// Check for group applications.
										if ($user->hasPermission('*')) {
											$pendingApplicationList = ApplicationHandler::getPendingApplications();
										} else if ($user->hasPermission('chief.applications') && 
												   $user->isGroupMember()) {
											$pendingApplicationList = ApplicationHandler::getPendingApplicationsByGroup($user->getGroup());
										}
										
										// Check for avatars.
										if ($user->hasPermission('*') ||
											$user->hasPermission('chief.applications') && $user->isGroupMember()) {
											$pendingAvatarList = AvatarHandler::getPendingAvatars();
										}

										$notificationsCount = count($pendingApplicationList) + count($pendingAvatarList);
										
										//<!-- Notifications: style can be found in dropdown.less -->';
										echo '<li class="dropdown notifications-menu">';
											echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
										  		echo '<i class="fa fa-bell-o"></i>';

										  		if ($notificationsCount > 0) {
										  			echo '<span class="label label-warning">' . $notificationsCount . '</span>';
										  		}
										  		
											echo '</a>';
											echo '<ul class="dropdown-menu">';
										  		
										  		if ($notificationsCount > 0) {
													echo '<li class="header">Du har ' . $notificationsCount . ' varsler.</li>';
													echo '<li>';
														//<!-- inner menu: contains the actual data -->';
														echo '<ul class="menu">';
														
															if (!empty($pendingApplicationList)) {
																foreach ($pendingApplicationList as $pendingApplication) {
																	echo '<li><a href="?page=application&id=' . $pendingApplication->getId() . '"><i class="fa fa-users text-aqua"></i>Søknaden til ' . $pendingApplication->getUser()->getFullName() . ' venter på godkjenning.</a></li>';
																}
															}

															if (!empty($pendingAvatarList)) {
																foreach ($pendingAvatarList as $pendingAvatar) {
																	echo '<li><a href="?page=chief-avatars"><i class="fa fa-users text-aqua"></i>Profilbilde til ' . $pendingAvatar->getUser()->getFullName() . ' venter på godkjenning.</a></li>';
																}
															}

														echo '</ul>';
										  			echo '</li>';
											  		/*
											  		echo '<li class="footer"><a href="#">View all</a></li>';
											  		*/
										  		} else {
										  			echo '<li class="header">Du har for øyeblikket ingen varsler.</li>';
										  		}
										  		
											echo '</ul>';
									  	echo '</li>';

									  	/*
										//<!-- Tasks: style can be found in dropdown.less -->
										echo '<li class="dropdown tasks-menu">';
											echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
											  	echo '<i class="fa fa-flag-o"></i>';
											  	echo '<span class="label label-danger">9</span>';
											echo '</a>';
											echo '<ul class="dropdown-menu">';
											  	echo '<li class="header">You have 9 tasks</li>';
											  	echo '<li>';
													echo '<!-- inner menu: contains the actual data -->';
													echo '<ul class="menu">';
												  		echo '<li><!-- Task item -->';
															echo '<a href="#">';
														  		echo '<h3>';
																	echo 'Design some buttons';
																	echo '<small class="pull-right">20%</small>';
														  		echo '</h3>';
														  		echo '<div class="progress xs">';
																	echo '<div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">';
															  			echo '<span class="sr-only">20% Complete</span>';
																	echo '</div>';
														  		echo '</div>';
															echo '</a>';
													  	echo '</li><!-- end task item -->';
													  	echo '<li><!-- Task item -->';
															echo '<a href="#">';
														  		echo '<h3>';
																	echo 'Create a nice theme';
																	echo '<small class="pull-right">40%</small>';
														  		echo '</h3>';
														  		echo '<div class="progress xs">';
																	echo '<div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">';
															  			echo '<span class="sr-only">40% Complete</span>';
																	echo '</div>';
														  		echo '</div>';
															echo '</a>';
													  	echo '</li><!-- end task item -->';
													  	echo '<li><!-- Task item -->';
															echo '<a href="#">';
														  		echo '<h3>';
																	echo 'Some task I need to do';
																	echo '<small class="pull-right">60%</small>';
														  		echo '</h3>';
														  		echo '<div class="progress xs">';
																	echo '<div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">';
															  			echo '<span class="sr-only">60% Complete</span>';
																	echo '</div>';
														  		echo '</div>';
															echo '</a>';
													  	echo '</li><!-- end task item -->';
													  	echo '<li><!-- Task item -->';
															echo '<a href="#">';
														  		echo '<h3>';
																	echo 'Make beautiful transitions';
																	echo '<small class="pull-right">80%</small>';
														  		echo '</h3>';
														  		echo '<div class="progress xs">';
																	echo '<div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">';
															  			echo '<span class="sr-only">80% Complete</span>';
																	echo '</div>';
														  		echo '</div>';
															echo '</a>';
													  	echo '</li><!-- end task item -->';
													echo '</ul>';
												echo '</li>';
												echo '<li class="footer">';
													echo '<a href="#">View all tasks</a>';
												echo '</li>';
											echo '</ul>';
										echo '</li>';
										*/

					  					//<!-- User Account: style can be found in dropdown.less -->
						  				echo '<li class="dropdown user user-menu">';
						  				
											if ($user->hasValidAvatar()) {
												$avatarFile = $user->getAvatar()->getThumbnail();
											} else {
												$avatarFile = $user->getDefaultAvatar();
											}

											echo '<a href="" class="dropdown-toggle" data-toggle="dropdown">';
											  	echo '<img src="' . $avatarFile . '" class="user-image" alt="' . $user->getFullName . '\'s profilbilde">';
											  	echo '<span class="hidden-xs">' . $user->getFullName() . '</span>';
											echo '</a>';

											echo '<ul class="dropdown-menu">';
												// <!-- User image -->
										 		echo '<li class="user-header">';
													echo '<img src="' . $avatarFile . '" class="img-circle" alt="' . $user->getFullName . '\'s profilbilde">';
													echo '<p>';
											  			echo $user->getFullName();
											  			echo '<small>' . $user->getRole() . '</small>';
											  			echo '<small>Registret den ' . date('d', $user->getRegisteredDate()) . ' ' . DateUtils::getMonthFromInt(date('m', $user->getRegisteredDate())) . ' ' . date('Y', $user->getRegisteredDate()) . '</small>';
													echo '</p>';
										  		echo '</li>';
										  		/*
										  		//<!-- Menu Body -->
										  		echo '<li class="user-body">';
													echo '<div class="col-xs-4 text-center">';
													  	echo '<a href="#">Followers</a>';
													echo '</div>';
													echo '<div class="col-xs-4 text-center">';
													  	echo '<a href="#">Sales</a>';
													echo '</div>';
													echo '<div class="col-xs-4 text-center">';
													  	echo '<a href="#">Friends</a>';
													echo '</div>';
												echo '</li>';
												*/
											  	// <!-- Menu Footer -->
											  	echo '<li class="user-footer">';
													echo '<div class="pull-left">';
												  		echo '<a href="?page=my-profile" class="btn btn-default btn-flat">Min profil</a>';
													echo '</div>';
													echo '<div class="pull-right">';
												  		echo '<a href="" onClick="logout()" class="btn btn-default btn-flat">Logg ut</a>';
													echo '</div>';
											  	echo '</li>';
											echo '</ul>';
										echo '</li>';
						  				echo '<!-- Control Sidebar Toggle Button -->';
										echo '<li>';
											echo '<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>';
									  	echo '</li>';
									echo '</ul>';
					  			echo '</div>';
							echo '</nav>';
				  		echo '</header>';
				  		echo '<!-- Left side column. contains the logo and sidebar -->';
				  		echo '<aside class="main-sidebar">';
							echo '<!-- sidebar: style can be found in sidebar.less -->';
							echo '<section class="sidebar">';
					  			echo '<!-- search form -->';
					  			echo '<form action="#" method="get" class="sidebar-form">';
					   				echo '<div class="input-group">';
						  				echo '<input type="text" name="q" class="form-control" placeholder="Search..."/>';
						  				echo '<span class="input-group-btn">';
											echo '<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>';
						  				echo '</span>';
									echo '</div>';
					  			echo '</form>';
								echo '<!-- /.search form -->';
								echo '<!-- sidebar menu: : style can be found in sidebar.less -->';
					  			echo '<ul class="sidebar-menu">';
					   				echo '<li class="header">Hovedmeny</li>';

					   				$groupList = GroupHandler::getGroups();

					   				if (!empty($groupList)) {
						   				echo '<li class="treeview' . ($this->pageName == 'all-crew' ? ' active' : null) . '">';
										  	echo '<a href="?page=all-crew">';
												echo '<i class="fa fa-users"></i><span>Crew</span><i class="fa fa-angle-left pull-right"></i>';
										  	echo '</a>';
										  	echo '<ul class="treeview-menu">';

												foreach ($groupList as $group) {
													echo '<li' . (isset($_GET['id']) && $group->getId() == $_GET['id'] ? ' class="active"' : null) .'><a href="?page=all-crew&id=' . $group->getId() . '"><i class="fa fa-circle-o"></i> ' . $group->getTitle() . '</a></li>';
												}

										  	echo '</ul>';
										echo '</li>';
					   				}

					   				if ($user->isGroupMember()) {
										$group = $user->getGroup();

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
										
										$teamList = $group->getTeams();
										$teamNameList = array();
										
										foreach ($teamList as $team) {
											array_push($teamNameList, strtolower($team->getName()));
										}

										// Only show pages for that group.
										if (empty($pageList) &&
											empty($teamList)) {
											echo '<li><a href="?page=my-crew"><i class="fa fa-user"></i><span>Mitt crew</span></a></li>';
										} else {
											echo '<li class="treeview' . ($this->pageName == 'my-crew' ? ' active' : null) . '">';
											  	echo '<a href="?page=my-crew">';
													echo '<i class="fa fa-user"></i><span>Mitt crew</span><i class="fa fa-angle-left pull-right"></i>';
											  	echo '</a>';
											  	echo '<ul class="treeview-menu">';
													echo '<li><a href="?page=my-crew"><i class="fa fa-circle-o"></i>' . $group->getTitle() . '</a></li>';

													// Only create link for groups that actually contain teams.
													if (!empty($teamList)) {
														foreach ($teamList as $team) {
															echo '<li' . (isset($_GET['teamId']) && $team->getId() == $_GET['teamId'] ? ' class="active"' : null) .'><a href="?page=my-crew&teamId=' . $team->getId() . '"><i class="fa fa-circle-o"></i> ' . $team->getTitle() . '</a></li>';
														}
													}
													
													if (!empty($pageList)) {
														foreach ($pageList as $page) {
															if (strtolower($page->getName()) != strtolower($group->getName())) {
																if (!in_array(strtolower($page->getName()), $teamNameList)) {
																	echo '<li><a href="?page=' . $page->getName() . '"><i class="fa fa-circle-o"></i> ' . $page->getTitle() . '</a></li>';
																}
															}
														}
													}

												echo '</ul>';
											echo '</li>';
										}
									}

									if ($user->hasPermission('*') ||
										$user->hasPermission('event')) {

										echo '<li class="treeview' . (StringUtils::startsWith($this->pageName, 'event') ? ' active' : null) . '">';
										  	echo '<a href="?page=event">';
												echo '<i class="fa fa-calendar"></i><span>Event</span><i class="fa fa-angle-left pull-right"></i>';
										  	echo '</a>';
										  	echo '<ul class="treeview-menu">';

												if ($user->hasPermission('*') ||
													$user->hasPermission('event.checkin')) {
													echo '<li' . ($this->pageName == 'event-checkin' ? ' class="active"' : null) . '><a href="?page=event-checkin"><i class="fa fa-check"></i>Innsjekk</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('event.seatmap')) {
													echo '<li' . ($this->pageName == 'event-seatmap' ? ' class="active"' : null) . '><a href="?page=event-seatmap"><i class="fa fa-map-marker"></i>Setekart</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('event.screen')) {
													echo '<li' . ($this->pageName == 'event-screen' ? ' class="active"' : null) . '><a href="?page=event-screen"><i class="fa fa-desktop"></i>Skjerm</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('event.agenda')) {
													echo '<li' . ($this->pageName == 'event-agenda' ? ' class="active"' : null) . '><a href="?page=event-agenda"><i class="fa fa-clock-o"></i>Agenda</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('event.memberlist')) {
													echo '<li' . ($this->pageName == 'event-memberlist' ? ' class="active"' : null) . '><a href="?page=event-memberlist"><i class="fa fa-file-text-o"></i>Medlemsliste</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('event.table.labels')) {
													echo '<li><a href="../api/pages/utils/printTableLabels.php"><i class="fa fa-external-link"></i>Print bordlapper</a></li>';
												}

											echo '</ul>';
										echo '</li>';
									}

									if ($user->hasPermission('*') ||
										$user->hasPermission('chief')) {

										echo '<li class="treeview' . (StringUtils::startsWith($this->pageName, 'chief') ? ' active' : null) . '">';
										  	echo '<a href="?page=chief">';
												echo '<i class="fa fa-gavel"></i><span>Chief</span><i class="fa fa-angle-left pull-right"></i>';
										  	echo '</a>';
										  	echo '<ul class="treeview-menu">';

												if ($user->hasPermission('*') ||
													$user->hasPermission('chief.groups')) {
													echo '<li' . ($this->pageName == 'chief-groups' ? ' class="active"' : null) . '><a href="?page=chief-groups"><i class="fa fa-circle-o"></i>Crew</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('chief.teams')) {
													echo '<li' . ($this->pageName == 'chief-teams' ? ' class="active"' : null) . '><a href="?page=chief-teams"><i class="fa fa-circle-o"></i>Lag</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('chief.avatars')) {
													echo '<li' . ($this->pageName == 'chief-avatars' ? ' class="active"' : null) . '><a href="?page=chief-avatars"><i class="fa fa-circle-o"></i>Profilbilder</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('chief.applications')) {
													echo '<li' . ($this->pageName == 'chief-applications' || $this->pageName == 'application' ? ' class="active"' : null) . '><a href="?page=chief-applications"><i class="fa fa-circle-o"></i>Søknader</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('chief.my-crew')) {
													echo '<li' . ($this->pageName == 'chief-my-crew' || $this->pageName == 'edit-restricted-page' ? ' class="active"' : null) . '><a href="?page=chief-my-crew"><i class="fa fa-circle-o"></i>My crew</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('chief.email')) {
													echo '<li' . ($this->pageName == 'chief-email' ? ' class="active"' : null) . '><a href="?page=chief-email"><i class="fa fa-send"></i>Send e-post</a></li>';
												}

											echo '</ul>';
										echo '</li>';
									}

									if ($user->hasPermission('*') ||
										$user->hasPermission('developer')) {

										echo '<li class="treeview' . (StringUtils::startsWith($this->pageName, 'developer') ? ' active' : null) . '">';
										  	echo '<a href="?page=developer">';
												echo '<i class="fa fa-wrench"></i><span>Utvikler</span><i class="fa fa-angle-left pull-right"></i>';
										  	echo '</a>';
										  	echo '<ul class="treeview-menu">';

												if ($user->hasPermission('*') ||
													$user->hasPermission('developer.switch.user')) {
													echo '<li' . ($this->pageName == 'developer-switch-user' ? ' class="active"' : null) . '><a href="?page=developer-switch-user"><i class="fa fa-rocket"></i>Bytt bruker</a></li>';
												}
												
											echo '</ul>';
										echo '</li>';
									}

									if ($user->hasPermission('*') ||
										$user->hasPermission('admin')) {

										echo '<li class="treeview' . (StringUtils::startsWith($this->pageName, 'admin') ? ' active' : null) . '">';
										  	echo '<a href="?page=admin">';
												echo '<i class="fa fa-wrench"></i><span>Administrator</span><i class="fa fa-angle-left pull-right"></i>';
										  	echo '</a>';
										  	echo '<ul class="treeview-menu">';

												if ($user->hasPermission('*') ||
													$user->hasPermission('admin.events')) {
													echo '<li' . ($this->pageName == 'admin-events' ? ' class="active"' : null) . '><a href="?page=admin-events"><i class="fa fa-calendar"></i>Arrangementer</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('admin.permissions')) {
													echo '<li' . ($this->pageName == 'admin-permissions' ? ' class="active"' : null) . '><a href="?page=admin-permissions"><i class="fa fa-check-square-o"></i>Rettigheter</a></li>';
												}

												if ($user->hasPermission('*') ||
													$user->hasPermission('admin.seatmap')) {
													echo '<li' . ($this->pageName == 'admin-seatmap' ? ' class="active"' : null) . '><a href="?page=admin-seatmap"><i class="fa fa-map-marker"></i>Endre setekart</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('admin.website')) {
													echo '<li' . ($this->pageName == 'admin-website' || $this->pageName == 'edit-page' ? ' class="active"' : null) . '><a href="?page=admin-website"><i class="fa fa-edit"></i>Endre hovedsiden</a></li>';
												}

											echo '</ul>';
										echo '</li>';
									}

								echo '</ul>';
							echo '</section>';
							echo '<!-- /.sidebar -->';
				  		echo '</aside>';

					  	echo '<!-- Content Wrapper. Contains page content -->';
					  	echo '<div class="content-wrapper">';

							if ($user->hasPermission('*') ||
								$user->isGroupMember()) {
								// View the page specified by "pageName" variable.
								$this->getPage($this->pageName);
							} else {
								$publicPages = array('apply', 
													 'all-crew', 
													 'my-profile', 
													 'edit-profile', 
													 'edit-password', 
													 'edit-avatar');
								
								if (in_array($this->pageName, $publicPages)) {
									$this->getPage($this->pageName);
								} else {
									$this->getPage('all-crew');
								}
							}
							/*
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
							*/

					 	echo '</div><!-- /.content-wrapper -->';
					  	echo '<footer class="main-footer">';
					  		/*
							echo '<div class="pull-right hidden-xs">';
						  		echo '<b>Version</b> 2.0';
							echo '</div>';
							*/
							echo '<strong>Copyright &copy; 2015 <a href="https://infected.no/">Infected</a>.</strong> All rights reserved.';
					  	echo '</footer>';
					  
					  	//<!-- Control Sidebar -->  
					  	echo '<aside class="control-sidebar control-sidebar-dark">';		
							echo '<!-- Create the tabs -->';
							echo '<ul class="nav nav-tabs nav-justified control-sidebar-tabs">';
						  		echo '<li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>';
						  		echo '<li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>';
							echo '</ul>';
							echo '<!-- Tab panes -->';
							echo '<div class="tab-content">';
						  		echo '<!-- Home tab content -->';
						  		echo '<div class="tab-pane" id="control-sidebar-home-tab">';
									echo '<h3 class="control-sidebar-heading">Recent Activity</h3>';
									echo '<ul class="control-sidebar-menu">';
									  	echo '<li>';
											echo '<a href="javascript::;">';
											  	echo '<i class="menu-icon fa fa-birthday-cake bg-red"></i>';
											  	echo '<div class="menu-info">';
													echo '<h4 class="control-sidebar-subheading">Langdon\'s Birthday</h4>';
													echo '<p>Will be 23 on April 24th</p>';
											  	echo '</div>';
											echo '</a>';
									  	echo '</li>';
									  	echo '<li>';
											echo '<a href="javascript::;">';
											  	echo '<i class="menu-icon fa fa-user bg-yellow"></i>';
											  	echo '<div class="menu-info">';
													echo '<h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>';
													echo '<p>New phone +1(800)555-1234</p>';
											  	echo '</div>';
											echo '</a>';
									  	echo '</li>';
									  	echo '<li>';
											echo '<a href="javascript::;">';
										  		echo '<i class="menu-icon fa fa-envelope-o bg-light-blue"></i>';
										  		echo '<div class="menu-info">';
													echo '<h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>';
													echo '<p>nora@example.com</p>';
										 		echo '</div>';
											echo '</a>';
									  	echo '</li>';
									  	echo '<li>';
											echo '<a href="javascript::;">';
										  		echo '<i class="menu-icon fa fa-file-code-o bg-green"></i>';
										  		echo '<div class="menu-info">';
													echo '<h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>';
													echo '<p>Execution time 5 seconds</p>';
										  		echo '</div>';
											echo '</a>';
									  	echo '</li>';
									echo '</ul><!-- /.control-sidebar-menu -->';

									echo '<h3 class="control-sidebar-heading">Tasks Progress</h3>';
									echo '<ul class="control-sidebar-menu">';
									  	echo '<li>';
											echo '<a href="javascript::;">'; 
											  	echo '<h4 class="control-sidebar-subheading">';
													echo 'Custom Template Design';
													echo '<span class="label label-danger pull-right">70%</span>';
											  	echo '</h4>';
											  	echo '<div class="progress progress-xxs">';
													echo '<div class="progress-bar progress-bar-danger" style="width: 70%"></div>';
											  	echo '</div>';
											echo '</a>';
									  	echo '</li>';
									  	echo '<li>';
											echo '<a href="javascript::;">';
										 		echo '<h4 class="control-sidebar-subheading">';
													echo 'Update Resume';
													echo '<span class="label label-success pull-right">95%</span>';
										  		echo '</h4>';
										  		echo '<div class="progress progress-xxs">';
													echo '<div class="progress-bar progress-bar-success" style="width: 95%"></div>';
										  		echo '</div>';
											echo '</a>';
							  			echo '</li>';
							  			echo '<li>';
											echo '<a href="javascript::;">'; 
									  			echo '<h4 class="control-sidebar-subheading">';
													echo 'Laravel Integration';
													echo '<span class="label label-waring pull-right">50%</span>';
									 			echo '</h4>';
									  			echo '<div class="progress progress-xxs">';
													echo '<div class="progress-bar progress-bar-warning" style="width: 50%"></div>';
									  			echo '</div>';
											echo '</a>';
							 			echo '</li>';
							  			echo '<li>';
											echo '<a href="javascript::;">';
												echo '<h4 class="control-sidebar-subheading">';
													echo 'Back End Framework';
													echo '<span class="label label-primary pull-right">68%</span>';
												echo '</h4>';
												echo '<div class="progress progress-xxs">';
													echo '<div class="progress-bar progress-bar-primary" style="width: 68%"></div>';
												echo '</div>';
											echo '</a>';
									  	echo '</li>';
									echo '</ul><!-- /.control-sidebar-menu -->';
						  		echo '</div><!-- /.tab-pane -->';
						  		//<!-- Stats tab content -->
						 		echo '<div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div><!-- /.tab-pane -->';
								//<!-- Settings tab content -->
							  	echo '<div class="tab-pane" id="control-sidebar-settings-tab">';
									echo '<form method="post">';
									  	echo '<h3 class="control-sidebar-heading">General Settings</h3>';
									  	echo '<div class="form-group">';
											echo '<label class="control-sidebar-subheading">';
										  		echo 'Report panel usage';
										  		echo '<input type="checkbox" class="pull-right" checked />';
											echo '</label>';
											echo '<p>';
										  		echo 'Some information about this general settings option';
											echo '</p>';
									  	echo '</div><!-- /.form-group -->';

									  	echo '<div class="form-group">';
											echo '<label class="control-sidebar-subheading">';
										  		echo 'Allow mail redirect';
										  		echo '<input type="checkbox" class="pull-right" checked />';
											echo '</label>';
											echo '<p>';
										  		echo 'Other sets of options are available';
											echo '</p>';
									  	echo '</div><!-- /.form-group -->';

								  		echo '<div class="form-group">';
											echo '<label class="control-sidebar-subheading">';
											  	echo 'Expose author name in posts';
											  	echo '<input type="checkbox" class="pull-right" checked />';
											echo '</label>';
											echo '<p>';
											  	echo 'Allow the user to show his name in blog posts';
											echo '</p>';
								  		echo '</div><!-- /.form-group -->';

								  		echo '<h3 class="control-sidebar-heading">Chat Settings</h3>';

									  	echo '<div class="form-group">';
											echo '<label class="control-sidebar-subheading">';
											  	echo 'Show me as online';
											  	echo '<input type="checkbox" class="pull-right" checked />';
											echo '</label>';
									  	echo '</div><!-- /.form-group -->';

									  	echo '<div class="form-group">';
											echo '<label class="control-sidebar-subheading">';
											  	echo 'Turn off notifications';
											  	echo '<input type="checkbox" class="pull-right" />';
											echo '</label>';
									  	echo '</div><!-- /.form-group -->';

									  	echo '<div class="form-group">';
											echo '<label class="control-sidebar-subheading">';
										  		echo 'Delete chat history';
									  			echo '<a href="javascript::;" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>';
											echo '</label>';
								  		echo '</div><!-- /.form-group -->';
									echo '</form>';
								echo '</div><!-- /.tab-pane -->';
							echo '</div>';
						echo '</aside><!-- /.control-sidebar -->';
					  	//<!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
					  	echo '<div class="control-sidebar-bg"></div>';
					echo '</div><!-- ./wrapper -->';

					//<!-- jQuery 2.1.4 -->
					//echo '<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>';
					//<!-- jQuery UI 1.11.2 -->
					echo '<script src="http://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script>';
					//<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
					echo '<script>';
						echo '$.widget.bridge(\'uibutton\', $.ui.button);';
					echo '</script>';
					//<!-- Bootstrap 3.3.2 JS -->
					echo '<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>';
					//<!-- Morris.js charts -->
					echo '<script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>';
					echo '<script src="plugins/morris/morris.min.js" type="text/javascript"></script>';
					//<!-- Sparkline -->
					echo '<script src="plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>';
					//<!-- jvectormap -->
					echo '<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>';
					echo '<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>';
					//<!-- jQuery Knob Chart -->
					echo '<script src="plugins/knob/jquery.knob.js" type="text/javascript"></script>';
					//<!-- daterangepicker -->
					echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js" type="text/javascript"></script>';
					echo '<script src="plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>';
					//<!-- datepicker -->
					echo '<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>';
					//<!-- Bootstrap WYSIHTML5 -->
					echo '<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>';
					//<!-- Slimscroll -->
					echo '<script src="plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>';
					//<!-- FastClick -->
					echo '<script src="plugins/fastclick/fastclick.min.js"></script>';
					//<!-- AdminLTE App -->
					echo '<script src="dist/js/app.min.js" type="text/javascript"></script>';

					//<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
					echo '<script src="dist/js/pages/dashboard.js" type="text/javascript"></script>';
					//<!-- AdminLTE for demo purposes -->
					echo '<script src="dist/js/demo.js" type="text/javascript"></script>';

					// Other
					echo '<script src="../api/scripts/logout.js"></script>';
				echo '</body>';
			} else {
				echo '<body class="login-page">';
    				echo '<div class="login-box">';
				      	echo '<div class="login-logo">';
				        	echo '<a href="."><b>' . Settings::name . '</b> Crew</a>';
				      	echo '</div><!-- /.login-logo -->';
				      	echo '<div class="login-box-body">';
				        	echo '<p class="login-box-msg">Du bruker den samme brukeren overalt på <b>' . Settings::name . '</b> sine nettsider.</p>';
					        echo '<form class="login" method="post">';
					          	echo '<div class="form-group has-feedback">';
					            	echo '<input type="text" name="identifier" class="form-control" placeholder="E-post"/>';
					            	echo '<span class="glyphicon glyphicon-envelope form-control-feedback"></span>';
					          	echo '</div>';
					          	echo '<div class="form-group has-feedback">';
					            	echo '<input type="password" name="password" class="form-control" placeholder="Passord"/>';
					            	echo '<span class="glyphicon glyphicon-lock form-control-feedback"></span>';
					          	echo '</div>';
					          	echo '<div class="row">';
					            	echo '<div class="col-xs-8">';
					              		echo '<div class="checkbox icheck">';
					                		echo '<label><input type="checkbox"> Husk meg</label>';
					              		echo '</div>';
					            	echo '</div><!-- /.col -->';
					           		echo '<div class="col-xs-4">';
					              		echo '<button type="submit" class="btn btn-primary btn-block btn-flat">Logg inn</button>';
					            	echo '</div><!-- /.col -->';
					          	echo '</div>';
					        echo '</form>';
					        echo '<a href="?page=reset-password">Har du glemt passordet ditt?</a><br>';
					        echo '<a href="?page=register">Register deg</a>';
					    echo '</div><!-- /.login-box-body -->';
					echo '</div><!-- /.login-box -->';

				    //<!-- jQuery 2.1.4 -->
				    //echo '<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>';
				    //<!-- Bootstrap 3.3.2 JS -->
				    echo '<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>';
				    //<!-- iCheck -->
				    //echo '<script src="plugins/iCheck/icheck.min.js" type="text/javascript"></script>';
				    echo '<script>';
				    	echo '$(function () {';
				        	echo '$(\'input\').iCheck({';
				          		echo 'checkboxClass: \'icheckbox_square-blue\',';
				          		echo 'radioClass: \'iradio_square-blue\',';
				          		echo 'increaseArea: \'20%\''; // optional
				        	echo '});';
				      	echo '});';
				    echo '</script>';

				    // Other
				    echo '<script src="../api/scripts/login.js"></script>';
				echo '</body>';
			}

		echo '</html>';
	}
	
	private function getPage($pageName) {
		// Fetch the page object from the database and display it.
		$page = RestrictedPageHandler::getPageByName($pageName);
		
		if ($page != null) {
			if (Session::isAuthenticated()) {
				$user = Session::getCurrentUser();
				
				//<!-- Content Header (Page header) -->';
				echo '<section class="content-header">';
			  		echo '<h1>';
						echo $page->getTitle();
						echo '<small>' . $page->getTitle() . '</small>';
			  		echo '</h1>';
			  		/*
			  		echo '<ol class="breadcrumb">';
						echo '<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>';
						echo '<li class="active">Dashboard</li>';
			  		echo '</ol>';
			  		*/
				echo '</section>';
				//<!-- Main content -->';
				echo '<section class="content">';

					echo $page->getContent();

				echo '</section><!-- /.content -->';

			} else {
				echo 'Du har ikke tilgang til dette.';
			}
		} else {
			$directoryList = array(Settings::api_path . 'pages',
								   'pages');
			$found = false;
			
			foreach ($directoryList as $directory) {
				$filePath = $directory . '/' . $pageName . '.php';
			
				if (in_array($filePath, glob($directory . '/*.php'))) {
					// Make sure we don't include pages with same name twice, 
					// and set the found varialbe so that we don't have to display the not found message.
					require_once $filePath;

					// Get the last declared class.
					$class = end(get_declared_classes());

					if (class_exists($class)) {
						// Create a new instance of this class.
						$page = new $class();
	 					
	 					// Print the page.
						//<!-- Content Header (Page header) -->';
						echo '<section class="content-header">';
					  		echo '<h1>';

					  			// Check if this page as a parent or not, and decide what to show.
					  			if ($page->hasParent()) {
									echo $page->getParent()->getTitle();
									echo '<small>' . $page->getTitle() . '</small>';
					  			} else {
									echo $page->getTitle();
					  			}

					  		echo '</h1>';
					  		/*
					  		echo '<ol class="breadcrumb">';
								echo '<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>';
								echo '<li class="active">Dashboard</li>';
					  		echo '</ol>';
					  		*/
						echo '</section>';
						//<!-- Main content -->';
						echo '<section class="content">';

							echo $page->getContent();

						echo '</section><!-- /.content -->';

						// The page is valid and should not be included twice.
						$found = true;
						break;
					}
				}
			}
			
			if (!$found) {
		        //<!-- Main content -->
		        echo '<section class="content">';
					echo '<div class="error-page">';
			            echo '<h2 class="headline text-yellow"> 404</h2>';
			            echo '<div class="error-content">';
			              	echo '<h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>';
			              	echo '<p>';
			                	echo 'We could not find the page you were looking for.';
			                	echo 'Meanwhile, you may <a href=".">return to last page</a> or try using the search form.';
			              	echo '</p>';
			            echo '</div><!-- /.error-content -->';
			       echo '</div><!-- /.error-page -->';
			    echo '</section><!-- /.content -->';
			}
		}
	}
}
?>