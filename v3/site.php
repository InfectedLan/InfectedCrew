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
										  		echo '<span class="label label-warning">' . $notificationsCount . '</span>';
											echo '</a>';
											echo '<ul class="dropdown-menu">';
										  		echo '<li class="header">You have ' . $notificationsCount . ' notifications</li>';
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

											echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
											  	echo '<img src="' . $avatarFile . '" class="user-image" alt="User Image" />';
											  	echo '<span class="hidden-xs">' . $user->getFullName() . '</span>';
											echo '</a>';

											echo '<ul class="dropdown-menu">';
												// <!-- User image -->
										 		echo '<li class="user-header">';
													echo '<img src="' . $avatarFile . '" class="img-circle" alt="User Image" />';
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
												  		echo '<a href="#" onClick="logout()" class="btn btn-default btn-flat">Logg ut</a>';
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
					  			echo '<!-- Sidebar user panel -->';
					  			echo '<div class="user-panel">';
									echo '<div class="pull-left image">';
						  				echo '<img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image" />';
									echo '</div>';
									echo '<div class="pull-left info">';
						  				echo '<p>Alexander Pierce</p>';

						  				echo '<a href="#"><i class="fa fa-circle text-success"></i> Online</a>';
									echo '</div>';
					  			echo '</div>';
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
					   				echo '<li class="header">MAIN NAVIGATION</li>';

					   				echo '<li class="treeview">';
									  	echo '<a href="?page=all-crew">';
											echo '<i class="fa fa-users"></i><span>Crew</span><i class="fa fa-angle-left pull-right"></i>';
									  	echo '</a>';
									  	echo '<ul class="treeview-menu">';

									  		$groupList = GroupHandler::getGroups();
									
											foreach ($groupList as $group) {
												echo '<li><a href="?page=all-crew&id=' . $group->getId() . '"><i class="fa fa-circle-o"></i> ' . $group->getTitle() . '</a></li>';

												//echo '<li><a' . (isset($_GET['id']) && $group->getId() == $_GET['id'] ? ' class="active"' : null) . ' href="index.php?page=all-crew&id=' . $group->getId() . '">' . $group->getTitle() . '</a></li>';
											}

									  	echo '</ul>';
									echo '</li>';

					   				if ($user->isGroupMember()) {
										$group = $user->getGroup();
										
										echo '<li class="treeview">';
										  	echo '<a href="?page=my-crew">';
												echo '<i class="fa fa-user"></i><span>Mitt crew</span><i class="fa fa-angle-left pull-right"></i>';
										  	echo '</a>';
										  	echo '<ul class="treeview-menu">';

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
												if (!empty($pageList) ||
													!empty($teamList)) {
													echo '<li><a href="?page=my-crew"><i class="fa fa-circle-o"></i>' . $group->getTitle() . '</a></li>';

													// Only create link for groups that actually contain teams.
													if (!empty($teamList)) {
														foreach ($teamList as $team) {
															echo '<li><a href="?page=my-crew&teamId=' . $team->getId() . '"><i class="fa fa-circle-o"></i> ' . $team->getTitle() . '</a></li>';
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
												}

											echo '</ul>';
										echo '</li>';
									}

									if ($user->hasPermission('*') ||
										$user->hasPermission('event')) {

										echo '<li class="treeview">';
										  	echo '<a href="?page=event">';
												echo '<i class="fa fa-calendar"></i><span>Event</span><i class="fa fa-angle-left pull-right"></i>';
										  	echo '</a>';
										  	echo '<ul class="treeview-menu">';

												if ($user->hasPermission('*') ||
													$user->hasPermission('event.checkin')) {
													echo '<li><a href="?page=event-checkin"><i class="fa fa-circle-o"></i>Innsjekk</a></li>';

													//echo '<li><a' . ($this->pageName == 'event-checkin' ? ' class="active"' : null) . ' href="index.php?page=event-checkin">Innsjekk</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('event.seatmap')) {
													echo '<li><a href="?page=event-seatmap"><i class="fa fa-circle-o"></i>Setekart</a></li>';

													//echo '<li><a' . ($this->pageName == 'event-seatmap' ? ' class="active"' : null) . ' href="index.php?page=event-seatmap">Seatmap</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('event.screen')) {
													echo '<li><a href="?page=event-screen"><i class="fa fa-circle-o"></i>Skjerm</a></li>';

													//echo '<li><a' . ($this->pageName == 'event-screen' ? ' class="active"' : null) . ' href="index.php?page=event-screen">Skjerm</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('event.agenda')) {
													echo '<li><a href="?page=event-agenda"><i class="fa fa-circle-o"></i>Agenda</a></li>';

													//echo '<li><a' . ($this->pageName == 'event-agenda' ? ' class="active"' : null) . ' href="index.php?page=event-agenda">Agenda</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('event.compos')) {
													echo '<li><a href="?page=event-compos"><i class="fa fa-circle-o"></i>Compo</a></li>';

													//echo '<li><a' . ($this->pageName == 'event-compos' ? ' class="active"' : null) . ' href="index.php?page=event-compos">Compo</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('event.memberlist')) {
													echo '<li><a href="?page=event-memberlist"><i class="fa fa-circle-o"></i>Medlemsliste</a></li>';

													//echo '<li><a' . ($this->pageName == 'event-memberlist' ? ' class="active"' : null) . ' href="index.php?page=event-memberlist">Medlemsliste</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('event.table-labels')) {
													echo '<li><a href="../api/pages/utils/printTableLabels.php"><i class="fa fa-circle-o"></i>Print bordlapper</a></li>';

													//echo '<li><a href="../api/pages/utils/printTableLabels.php">Print bordlapper</a></li>';
												}

											echo '</ul>';
										echo '</li>';
									}

									if ($user->hasPermission('*') ||
										$user->hasPermission('chief')) {

										echo '<li class="treeview">';
										  	echo '<a href="?page=chief">';
												echo '<i class="fa fa-gavel"></i><span>Chief</span><i class="fa fa-angle-left pull-right"></i>';
										  	echo '</a>';
										  	echo '<ul class="treeview-menu">';

												if ($user->hasPermission('*') ||
													$user->hasPermission('chief.groups')) {
													echo '<li><a href="?page=chief-groups"><i class="fa fa-circle-o"></i>Crew</a></li>';

													//echo '<li><a' . ($this->pageName == 'chief-groups' ? ' class="active"' : null) . ' href="index.php?page=chief-groups">Crew</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('chief.teams')) {
													echo '<li><a href="?page=chief-teams"><i class="fa fa-circle-o"></i>Lag</a></li>';
													
													//echo '<li><a' . ($this->pageName == 'chief-teams' ? ' class="active"' : null) . ' href="index.php?page=chief-teams">Lag</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('chief.avatars')) {
													echo '<li><a href="?page=chief-avatars"><i class="fa fa-circle-o"></i>Profilbilder</a></li>';
													
													//echo '<li><a' . ($this->pageName == 'chief-avatars' ? ' class="active"' : null) . ' href="index.php?page=chief-avatars">Profilbilder</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('chief.applications')) {
													echo '<li><a href="?page=chief-applications"><i class="fa fa-circle-o"></i>Søknader</a></li>';
													
													//echo '<li><a' . ($this->pageName == 'chief-applications' || $this->pageName == 'application' ? ' class="active"' : null) . ' href="index.php?page=chief-applications">Søknader</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('chief.my-crew')) {
													echo '<li><a href="?page=event-memberlist"><i class="fa fa-circle-o"></i>My crew</a></li>';
													
													//echo '<li><a' . ($this->pageName == 'chief-my-crew' || $this->pageName == 'edit-restricted-page' ? ' class="active"' : null) . ' href="index.php?page=chief-my-crew">My Crew</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('chief.email')) {
													echo '<li><a href="?page=chief-email"><i class="fa fa-circle-o"></i>Send e-post</a></li>';
													
													//echo '<li><a' . ($this->pageName == 'chief-email' ? ' class="active"' : null) . ' href="index.php?page=chief-email">Send e-post</a></li>';
												}

											echo '</ul>';
										echo '</li>';
									}

									if ($user->hasPermission('*') ||
										$user->hasPermission('admin')) {

										echo '<li class="treeview">';
										  	echo '<a href="?page=admin">';
												echo '<i class="fa fa-wrench"></i><span>Administrator</span><i class="fa fa-angle-left pull-right"></i>';
										  	echo '</a>';
										  	echo '<ul class="treeview-menu">';

												if ($user->hasPermission('*') ||
													$user->hasPermission('admin.events')) {
													echo '<li><a href="?page=admin-events"><i class="fa fa-circle-o"></i>Arrangementer</a></li>';

													//echo '<li><a' . ($this->pageName == 'admin-events' ? ' class="active"' : null) . ' href="index.php?page=admin-events">Arrangementer</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('admin.permissions')) {
													echo '<li><a href="?page=admin-permissions"><i class="fa fa-circle-o"></i>Rettigheter</a></li>';

													//echo '<li><a' . ($this->pageName == 'admin-permissions' ? ' class="active"' : null) . ' href="index.php?page=admin-permissions">Tilganger</a></li>';
												}

												if ($user->hasPermission('*') ||
													$user->hasPermission('admin.seatmap')) {
													echo '<li><a href="?page=admin-seatmap"><i class="fa fa-circle-o"></i>Endre setekart</a></li>';

													//echo '<li><a' . ($this->pageName == 'admin-seatmap' ? ' class="active"' : null) . ' href="index.php?page=admin-seatmap">Endre seatmap</a></li>';
												}
												
												if ($user->hasPermission('*') ||
													$user->hasPermission('admin.website')) {
													echo '<li><a href="?page=admin-website"><i class="fa fa-circle-o"></i>Endre hovedsiden</a></li>';

													//echo '<li><a' . ($this->pageName == 'admin-website' || $this->pageName == 'edit-page' ? ' class="active"' : null) . ' href="index.php?page=admin-website">Endre hovedsiden</a></li>';
												}

											echo '</ul>';
										echo '</li>';
									}

									/*
									echo '<li class="active treeview">';
					  					echo '<a href="#">';
											echo '<i class="fa fa-dashboard"></i> <span>Dashboard</span> <i class="fa fa-angle-left pull-right"></i>';
					  					echo '</a>';
					  					echo '<ul class="treeview-menu">';
											echo '<li class="active"><a href="index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>';
											echo '<li><a href="index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>';
					  					echo '</ul>';
									echo '</li>';
									echo '<li class="treeview">';
										echo '<a href="#">';
											echo '<i class="fa fa-files-o"></i>';
											echo '<span>Layout Options</span>';
											echo '<span class="label label-primary pull-right">4</span>';
										echo '</a>';
										echo '<ul class="treeview-menu">';
											echo '<li><a href="pages/layout/top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>';
											echo '<li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>';
											echo '<li><a href="pages/layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>';
											echo '<li><a href="pages/layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>';
										echo '</ul>';
									echo '</li>';
									echo '<li>';
										echo '<a href="pages/widgets.html">';
											echo '<i class="fa fa-th"></i> <span>Widgets</span> <small class="label pull-right bg-green">new</small>';
										echo '</a>';
									echo '</li>';
									echo '<li class="treeview">';
										echo '<a href="#">';
											echo '<i class="fa fa-pie-chart"></i>';
											echo '<span>Charts</span>';
											echo '<i class="fa fa-angle-left pull-right"></i>';
										echo '</a>';
										echo '<ul class="treeview-menu">';
											echo '<li><a href="pages/charts/chartjs.html"><i class="fa fa-circle-o"></i> ChartJS</a></li>';
											echo '<li><a href="pages/charts/morris.html"><i class="fa fa-circle-o"></i> Morris</a></li>';
											echo '<li><a href="pages/charts/flot.html"><i class="fa fa-circle-o"></i> Flot</a></li>';
											echo '<li><a href="pages/charts/inline.html"><i class="fa fa-circle-o"></i> Inline charts</a></li>';
										echo '</ul>';
									echo '</li>';
									echo '<li class="treeview">';
									  	echo '<a href="#">';
											echo '<i class="fa fa-laptop"></i>';
											echo '<span>UI Elements</span>';
											echo '<i class="fa fa-angle-left pull-right"></i>';
									  	echo '</a>';
									  	echo '<ul class="treeview-menu">';
											echo '<li><a href="pages/UI/general.html"><i class="fa fa-circle-o"></i> General</a></li>';
											echo '<li><a href="pages/UI/icons.html"><i class="fa fa-circle-o"></i> Icons</a></li>';
											echo '<li><a href="pages/UI/buttons.html"><i class="fa fa-circle-o"></i> Buttons</a></li>';
											echo '<li><a href="pages/UI/sliders.html"><i class="fa fa-circle-o"></i> Sliders</a></li>';
											echo '<li><a href="pages/UI/timeline.html"><i class="fa fa-circle-o"></i> Timeline</a></li>';
											echo '<li><a href="pages/UI/modals.html"><i class="fa fa-circle-o"></i> Modals</a></li>';
									  	echo '</ul>';
									echo '</li>';
									echo '<li class="treeview">';
									  	echo '<a href="#">';
											echo '<i class="fa fa-edit"></i> <span>Forms</span>';
											echo '<i class="fa fa-angle-left pull-right"></i>';
									  	echo '</a>';
									  	echo '<ul class="treeview-menu">';
											echo '<li><a href="pages/forms/general.html"><i class="fa fa-circle-o"></i> General Elements</a></li>';
											echo '<li><a href="pages/forms/advanced.html"><i class="fa fa-circle-o"></i> Advanced Elements</a></li>';
											echo '<li><a href="pages/forms/editors.html"><i class="fa fa-circle-o"></i> Editors</a></li>';
									  	echo '</ul>';
									echo '</li>';
									echo '<li class="treeview">';
									  	echo '<a href="#">';
									  		echo '<i class="fa fa-table"></i> <span>Tables</span>';
									   		echo '<i class="fa fa-angle-left pull-right"></i>';
									  	echo '</a>';
									  	echo '<ul class="treeview-menu">';
											echo '<li><a href="pages/tables/simple.html"><i class="fa fa-circle-o"></i> Simple tables</a></li>';
											echo '<li><a href="pages/tables/data.html"><i class="fa fa-circle-o"></i> Data tables</a></li>';
									  	echo '</ul>';
									echo '</li>';
									echo '<li>';
									  	echo '<a href="pages/calendar.html">';
											echo '<i class="fa fa-calendar"></i> <span>Calendar</span>';
											echo '<small class="label pull-right bg-red">3</small>';
									  	echo '</a>';
									echo '</li>';
									echo '<li>';
									  	echo '<a href="pages/mailbox/mailbox.html">';
											echo '<i class="fa fa-envelope"></i> <span>Mailbox</span>';
											echo '<small class="label pull-right bg-yellow">12</small>';
									  	echo '</a>';
									echo '</li>';
									echo '<li class="treeview">';
									  	echo '<a href="#">';
											echo '<i class="fa fa-folder"></i> <span>Examples</span>';
											echo '<i class="fa fa-angle-left pull-right"></i>';
									  	echo '</a>';
									  	echo '<ul class="treeview-menu">';
											echo '<li><a href="pages/examples/invoice.html"><i class="fa fa-circle-o"></i> Invoice</a></li>';
											echo '<li><a href="pages/examples/login.html"><i class="fa fa-circle-o"></i> Login</a></li>';
											echo '<li><a href="pages/examples/register.html"><i class="fa fa-circle-o"></i> Register</a></li>';
											echo '<li><a href="pages/examples/lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen</a></li>';
											echo '<li><a href="pages/examples/404.html"><i class="fa fa-circle-o"></i> 404 Error</a></li>';
											echo '<li><a href="pages/examples/500.html"><i class="fa fa-circle-o"></i> 500 Error</a></li>';
											echo '<li><a href="pages/examples/blank.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>';
									  	echo '</ul>';
									echo '</li>';
									echo '<li class="treeview">';
									  	echo '<a href="#">';
											echo '<i class="fa fa-share"></i> <span>Multilevel</span>';
											echo '<i class="fa fa-angle-left pull-right"></i>';
									  	echo '</a>';
									  	echo '<ul class="treeview-menu">';
											echo '<li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>';
											echo '<li>';
										  		echo '<a href="#"><i class="fa fa-circle-o"></i> Level One <i class="fa fa-angle-left pull-right"></i></a>';
										  		echo '<ul class="treeview-menu">';
													echo '<li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>';
													echo '<li>';
														echo '<a href="#"><i class="fa fa-circle-o"></i> Level Two <i class="fa fa-angle-left pull-right"></i></a>';
													  	echo '<ul class="treeview-menu">';
															echo '<li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>';
															echo '<li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>';
													  	echo '</ul>';
													echo '</li>';
										  		echo '</ul>';
											echo '</li>';
											echo '<li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>';
									  	echo '</ul>';
									echo '</li>';
				   					echo '<li><a href="documentation/index.html"><i class="fa fa-book"></i> <span>Documentation</span></a></li>';
									echo '<li class="header">LABELS</li>';
									echo '<li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>';
									echo '<li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>';
									echo '<li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>';
									*/
								echo '</ul>';
							echo '</section>';
							echo '<!-- /.sidebar -->';
				  		echo '</aside>';

					  	echo '<!-- Content Wrapper. Contains page content -->';
					  	echo '<div class="content-wrapper">';
							echo '<!-- Content Header (Page header) -->';
							echo '<section class="content-header">';
						  		echo '<h1>';
									echo 'Dashboard';
									echo '<small>Control panel</small>';
						  		echo '</h1>';
						  		echo '<ol class="breadcrumb">';
									echo '<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>';
									echo '<li class="active">Dashboard</li>';
						  		echo '</ol>';
							echo '</section>';
							
							echo '<!-- Main content -->';
							echo '<section class="content">';

								if ($user->hasPermission('*') ||
									$user->isGroupMember()) {
									// View the page specified by "pageName" variable.
									$this->viewPage($this->pageName);
								} else {
									$publicPages = array('apply', 
														 'all-crew', 
														 'my-profile', 
														 'edit-profile', 
														 'edit-password', 
														 'edit-avatar');
									
									if (in_array($this->pageName, $publicPages)) {
										$this->viewPage($this->pageName);
									} else {
										$this->viewPage('all-crew');
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

								/*
							  	echo '<!-- Small boxes (Stat box) -->';
							  	echo '<div class="row">';
									echo '<div class="col-lg-3 col-xs-6">';
								  		echo '<!-- small box -->';
								  		echo '<div class="small-box bg-aqua">';
											echo '<div class="inner">';
										  		echo '<h3>150</h3>';
										 		echo '<p>New Orders</p>';
											echo '</div>';
											echo '<div class="icon">';
										  		echo '<i class="ion ion-bag"></i>';
											echo '</div>';
											echo '<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>';
									  	echo '</div>';
									echo '</div><!-- ./col -->';
									echo '<div class="col-lg-3 col-xs-6">';
									  	echo '<!-- small box -->';
									  	echo '<div class="small-box bg-green">';
											echo '<div class="inner">';
										 		echo '<h3>53<sup style="font-size: 20px">%</sup></h3>';
										  		echo '<p>Bounce Rate</p>';
											echo '</div>';
											echo '<div class="icon">';
										  		echo '<i class="ion ion-stats-bars"></i>';
											echo '</div>';
											echo '<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>';
									  	echo '</div>';
									echo '</div><!-- ./col -->';
									echo '<div class="col-lg-3 col-xs-6">';
									  	echo '<!-- small box -->';
									  	echo '<div class="small-box bg-yellow">';
											echo '<div class="inner">';
											  	echo '<h3>44</h3>';
											  	echo '<p>User Registrations</p>';
											echo '</div>';
											echo '<div class="icon">';
											  	echo '<i class="ion ion-person-add"></i>';
											echo '</div>';
											echo '<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>';
										echo '</div>';
									echo '</div><!-- ./col -->';
									echo '<div class="col-lg-3 col-xs-6">';
										echo '<!-- small box -->';
										echo '<div class="small-box bg-red">';
											echo '<div class="inner">';
												echo '<h3>65</h3>';
												echo '<p>Unique Visitors</p>';
											echo '</div>';
											echo '<div class="icon">';
												echo '<i class="ion ion-pie-graph"></i>';
											echo '</div>';
											echo '<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>';
										echo '</div>';
									echo '</div><!-- ./col -->';
								echo '</div><!-- /.row -->';
							 	//<!-- Main row -->
							  	echo '<div class="row">';
									echo '<!-- Left col -->';
									echo '<section class="col-lg-7 connectedSortable">';
								  		echo '<!-- Custom tabs (Charts with tabs)-->';
								  		echo '<div class="nav-tabs-custom">';
											echo '<!-- Tabs within a box -->';
											echo '<ul class="nav nav-tabs pull-right">';
									  			echo '<li class="active"><a href="#revenue-chart" data-toggle="tab">Area</a></li>';
									  			echo '<li><a href="#sales-chart" data-toggle="tab">Donut</a></li>';
									  			echo '<li class="pull-left header"><i class="fa fa-inbox"></i> Sales</li>';
											echo '</ul>';
											echo '<div class="tab-content no-padding">';
									  			echo '<!-- Morris chart - Sales -->';
									  			echo '<div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;"></div>';
									  			echo '<div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;"></div>';
											echo '</div>';
								  		echo '</div><!-- /.nav-tabs-custom -->';

								  		echo '<!-- Chat box -->';
								  		echo '<div class="box box-success">';
											echo '<div class="box-header">';
									  			echo '<i class="fa fa-comments-o"></i>';
									  			echo '<h3 class="box-title">Chat</h3>';
									  			echo '<div class="box-tools pull-right" data-toggle="tooltip" title="Status">';
													echo '<div class="btn-group" data-toggle="btn-toggle" >';
													  	echo '<button type="button" class="btn btn-default btn-sm active"><i class="fa fa-square text-green"></i></button>';
													  	echo '<button type="button" class="btn btn-default btn-sm"><i class="fa fa-square text-red"></i></button>';
													echo '</div>';
									  			echo '</div>';
											echo '</div>';
											echo '<div class="box-body chat" id="chat-box">';
										  		echo '<!-- chat item -->';
										  		echo '<div class="item">';
													echo '<img src="dist/img/user4-128x128.jpg" alt="user image" class="online"/>';
													echo '<p class="message">';
												  		echo '<a href="#" class="name">';
															echo '<small class="text-muted pull-right"><i class="fa fa-clock-o"></i> 2:15</small>';
															echo 'Mike Doe';
												  		echo '</a>';
												  		echo 'I would like to meet you to discuss the latest news about';
												  		echo 'the arrival of the new theme. They say it is going to be one the';
											  			echo 'best themes on the market';
													echo '</p>';
													echo '<div class="attachment">';
											  			echo '<h4>Attachments:</h4>';
											  			echo '<p class="filename">';
															echo 'Theme-thumbnail-image.jpg';
											  			echo '</p>';
											  			echo '<div class="pull-right">';
															echo '<button class="btn btn-primary btn-sm btn-flat">Open</button>';
											  			echo '</div>';
													echo '</div><!-- /.attachment -->';
										  		echo '</div><!-- /.item -->';
									  			echo '<!-- chat item -->';
											  	echo '<div class="item">';
													echo '<img src="dist/img/user3-128x128.jpg" alt="user image" class="offline"/>';
													echo '<p class="message">';
													 	echo '<a href="#" class="name">';
															echo '<small class="text-muted pull-right"><i class="fa fa-clock-o"></i> 5:15</small>';
															echo 'Alexander Pierce';
													  	echo '</a>';
													  	echo 'I would like to meet you to discuss the latest news about';
													  	echo 'the arrival of the new theme. They say it is going to be one the';
													  	echo 'best themes on the market';
													echo '</p>';
											  	echo '</div><!-- /.item -->';
									  			echo '<!-- chat item -->';
											  	echo '<div class="item">';
													echo '<img src="dist/img/user2-160x160.jpg" alt="user image" class="offline"/>';
													echo '<p class="message">';
												  		echo '<a href="#" class="name">';
															echo '<small class="text-muted pull-right"><i class="fa fa-clock-o"></i> 5:30</small>';
															echo 'Susan Doe';
												  		echo '</a>';
														echo 'I would like to meet you to discuss the latest news about';
														echo 'the arrival of the new theme. They say it is going to be one the';
														echo 'best themes on the market';
													echo '</p>';
											  	echo '</div><!-- /.item -->';
											echo '</div><!-- /.chat -->';
											echo '<div class="box-footer">';
									 			echo '<div class="input-group">';
													echo '<input class="form-control" placeholder="Type message..."/>';
													echo '<div class="input-group-btn">';
										 				echo '<button class="btn btn-success"><i class="fa fa-plus"></i></button>';
													echo '</div>';
									  			echo '</div>';
											echo '</div>';
								  		echo '</div><!-- /.box (chat box) -->';

									  	echo '<!-- TO DO List -->';
									  	echo '<div class="box box-primary">';
											echo '<div class="box-header">';
										  		echo '<i class="ion ion-clipboard"></i>';
										  		echo '<h3 class="box-title">To Do List</h3>';
										  		echo '<div class="box-tools pull-right">';
													echo '<ul class="pagination pagination-sm inline">';
											  			echo '<li><a href="#">&laquo;</a></li>';
											  			echo '<li><a href="#">1</a></li>';
											  			echo '<li><a href="#">2</a></li>';
											  			echo '<li><a href="#">3</a></li>';
											  			echo '<li><a href="#">&raquo;</a></li>';
													echo '</ul>';
										  		echo '</div>';
											echo '</div><!-- /.box-header -->';
											echo '<div class="box-body">';
									  			echo '<ul class="todo-list">';
													echo '<li>';
										  				echo '<!-- drag handle -->';
										  				echo '<span class="handle">';
															echo '<i class="fa fa-ellipsis-v"></i>';
															echo '<i class="fa fa-ellipsis-v"></i>';
										  				echo '</span>';
										  				echo '<!-- checkbox -->';
										  				echo '<input type="checkbox" value="" name=""/>';
													  	echo '<!-- todo text -->';
													  	echo '<span class="text">Design a nice theme</span>';
										  				echo '<!-- Emphasis label -->';
										  				echo '<small class="label label-danger"><i class="fa fa-clock-o"></i> 2 mins</small>';
										  				echo '<!-- General tools such as edit or delete-->';
										  				echo '<div class="tools">';
															echo '<i class="fa fa-edit"></i>';
															echo '<i class="fa fa-trash-o"></i>';
										  				echo '</div>';
													echo '</li>';
													echo '<li>';
										  				echo '<span class="handle">';
															echo '<i class="fa fa-ellipsis-v"></i>';
															echo '<i class="fa fa-ellipsis-v"></i>';
										  				echo '</span>';
										  				echo '<input type="checkbox" value="" name=""/>';
										  				echo '<span class="text">Make the theme responsive</span>';
										  				echo '<small class="label label-info"><i class="fa fa-clock-o"></i> 4 hours</small>';
										  				echo '<div class="tools">';
															echo '<i class="fa fa-edit"></i>';
															echo '<i class="fa fa-trash-o"></i>';
										  				echo '</div>';
													echo '</li>';
													echo '<li>';
												  		echo '<span class="handle">';
															echo '<i class="fa fa-ellipsis-v"></i>';
															echo '<i class="fa fa-ellipsis-v"></i>';
												  		echo '</span>';
												  		echo '<input type="checkbox" value="" name=""/>';
												  		echo '<span class="text">Let theme shine like a star</span>';
												  		echo '<small class="label label-warning"><i class="fa fa-clock-o"></i> 1 day</small>';
												  		echo '<div class="tools">';
															echo '<i class="fa fa-edit"></i>';
															echo '<i class="fa fa-trash-o"></i>';
												  		echo '</div>';
													echo '</li>';
													echo '<li>';
												  		echo '<span class="handle">';
															echo '<i class="fa fa-ellipsis-v"></i>';
															echo '<i class="fa fa-ellipsis-v"></i>';
												 		echo '</span>';
												  		echo '<input type="checkbox" value="" name=""/>';
												  		echo '<span class="text">Let theme shine like a star</span>';
												  		echo '<small class="label label-success"><i class="fa fa-clock-o"></i> 3 days</small>';
												  		echo '<div class="tools">';
															echo '<i class="fa fa-edit"></i>';
															echo '<i class="fa fa-trash-o"></i>';
												  		echo '</div>';
													echo '</li>';
													echo '<li>';
												  		echo '<span class="handle">';
															echo '<i class="fa fa-ellipsis-v"></i>';
															echo '<i class="fa fa-ellipsis-v"></i>';
												  		echo '</span>';
												  		echo '<input type="checkbox" value="" name=""/>';
												  		echo '<span class="text">Check your messages and notifications</span>';
												  		echo '<small class="label label-primary"><i class="fa fa-clock-o"></i> 1 week</small>';
												 		echo '<div class="tools">';
															echo '<i class="fa fa-edit"></i>';
															echo '<i class="fa fa-trash-o"></i>';
												  		echo '</div>';
													echo '</li>';
													echo '<li>';
													  	echo '<span class="handle">';
															echo '<i class="fa fa-ellipsis-v"></i>';
															echo '<i class="fa fa-ellipsis-v"></i>';
													  	echo '</span>';
													  	echo '<input type="checkbox" value="" name=""/>';
													  	echo '<span class="text">Let theme shine like a star</span>';
													  	echo '<small class="label label-default"><i class="fa fa-clock-o"></i> 1 month</small>';
													  	echo '<div class="tools">';
															echo '<i class="fa fa-edit"></i>';
															echo '<i class="fa fa-trash-o"></i>';
													  	echo '</div>';
													echo '</li>';
												echo '</ul>';
											echo '</div><!-- /.box-body -->';
											echo '<div class="box-footer clearfix no-border">';
									  			echo '<button class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add item</button>';
											echo '</div>';
								  		echo '</div><!-- /.box -->';

								  		echo '<!-- quick email widget -->';
								  		echo '<div class="box box-info">';
											echo '<div class="box-header">';
												echo '<i class="fa fa-envelope"></i>';
											  	echo '<h3 class="box-title">Quick Email</h3>';
									 			//<!-- tools box -->';
									  			echo '<div class="pull-right box-tools">';
													echo '<button class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>';
									  			echo '</div><!-- /. tools -->';
											echo '</div>';
											echo '<div class="box-body">';
									  			echo '<form action="#" method="post">';
													echo '<div class="form-group">';
										  				echo '<input type="email" class="form-control" name="emailto" placeholder="Email to:"/>';
													echo '</div>';
													echo '<div class="form-group">';
										  				echo '<input type="text" class="form-control" name="subject" placeholder="Subject"/>';
													echo '</div>';
													echo '<div>';
										  				echo '<textarea class="textarea" placeholder="Message" style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>';
													echo '</div>';
									  			echo '</form>';
											echo '</div>';
											echo '<div class="box-footer clearfix">';
									  			echo '<button class="pull-right btn btn-default" id="sendEmail">Send <i class="fa fa-arrow-circle-right"></i></button>';
											echo '</div>';
								  		echo '</div>';
									echo '</section><!-- /.Left col -->';

									echo '<!-- right col (We are only adding the ID to make the widgets sortable)-->';
									echo '<section class="col-lg-5 connectedSortable">';
									  	echo '<!-- Map box -->';
									  	echo '<div class="box box-solid bg-light-blue-gradient">';
											echo '<div class="box-header">';
										  		echo '<!-- tools box -->';
										  		echo '<div class="pull-right box-tools">';
													echo '<button class="btn btn-primary btn-sm daterange pull-right" data-toggle="tooltip" title="Date range"><i class="fa fa-calendar"></i></button>';
													echo '<button class="btn btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>';
										  		echo '</div><!-- /. tools -->';

										 		echo '<i class="fa fa-map-marker"></i>';
										  		echo '<h3 class="box-title">';
													echo 'Visitors';
										  		echo '</h3>';
											echo '</div>';
											echo '<div class="box-body">';
										  		echo '<div id="world-map" style="height: 250px; width: 100%;"></div>';
											echo '</div><!-- /.box-body-->';
											echo '<div class="box-footer no-border">';
										  		echo '<div class="row">';
													echo '<div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">';
											  			echo '<div id="sparkline-1"></div>';
											  			echo '<div class="knob-label">Visitors</div>';
													echo '</div><!-- ./col -->';
													echo '<div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">';
											  			echo '<div id="sparkline-2"></div>';
											  			echo '<div class="knob-label">Online</div>';
													echo '</div><!-- ./col -->';
													echo '<div class="col-xs-4 text-center">';
											  			echo '<div id="sparkline-3"></div>';
											  			echo '<div class="knob-label">Exists</div>';
													echo '</div><!-- ./col -->';
										  		echo '</div><!-- /.row -->';
											echo '</div>';
									  	echo '</div>';
									  	echo '<!-- /.box -->';

									  	echo '<!-- solid sales graph -->';
									  	echo '<div class="box box-solid bg-teal-gradient">';
											echo '<div class="box-header">';
												echo '<i class="fa fa-th"></i>';
										  		echo '<h3 class="box-title">Sales Graph</h3>';
										  		echo '<div class="box-tools pull-right">';
													echo '<button class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>';
													echo '<button class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>';
										  		echo '</div>';
											echo '</div>';
											echo '<div class="box-body border-radius-none">';
										  		echo '<div class="chart" id="line-chart" style="height: 250px;"></div>';
											echo '</div><!-- /.box-body -->';
											echo '<div class="box-footer no-border">';
										  		echo '<div class="row">';
													echo '<div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">';
											  			echo '<input type="text" class="knob" data-readonly="true" value="20" data-width="60" data-height="60" data-fgColor="#39CCCC"/>';
											  			echo '<div class="knob-label">Mail-Orders</div>';
													echo '</div><!-- ./col -->';
													echo '<div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">';
											  			echo '<input type="text" class="knob" data-readonly="true" value="50" data-width="60" data-height="60" data-fgColor="#39CCCC"/>';
											  			echo '<div class="knob-label">Online</div>';
													echo '</div><!-- ./col -->';
													echo '<div class="col-xs-4 text-center">';
											  			echo '<input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60" data-fgColor="#39CCCC"/>';
											  			echo '<div class="knob-label">In-Store</div>';
													echo '</div><!-- ./col -->';
										  		echo '</div><!-- /.row -->';
											echo '</div><!-- /.box-footer -->';
									  	echo '</div><!-- /.box -->';

									  	echo '<!-- Calendar -->';
									  	echo '<div class="box box-solid bg-green-gradient">';
											echo '<div class="box-header">';
										  		echo '<i class="fa fa-calendar"></i>';
										  		echo '<h3 class="box-title">Calendar</h3>';
										  		echo '<!-- tools box -->';
										  		echo '<div class="pull-right box-tools">';
													echo '<!-- button with a dropdown -->';
													echo '<div class="btn-group">';
											  			echo '<button class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i></button>';
											  			echo '<ul class="dropdown-menu pull-right" role="menu">';
															echo '<li><a href="#">Add new event</a></li>';
															echo '<li><a href="#">Clear events</a></li>';
															echo '<li class="divider"></li>';
															echo '<li><a href="#">View calendar</a></li>';
											  			echo '</ul>';
													echo '</div>';
													echo '<button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>';
													echo '<button class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>';
										  		echo '</div><!-- /. tools -->';
											echo '</div><!-- /.box-header -->';
											echo '<div class="box-body no-padding">';
										  		echo '<!--The calendar -->';
										  		echo '<div id="calendar" style="width: 100%"></div>';
											echo '</div><!-- /.box-body -->';
											echo '<div class="box-footer text-black">';
										  		echo '<div class="row">';
													echo '<div class="col-sm-6">';
													  	echo '<!-- Progress bars -->';
													  	echo '<div class="clearfix">';
															echo '<span class="pull-left">Task #1</span>';
															echo '<small class="pull-right">90%</small>';
													 	echo '</div>';
													  	echo '<div class="progress xs">';
															echo '<div class="progress-bar progress-bar-green" style="width: 90%;"></div>';
													  	echo '</div>';
													  	echo '<div class="clearfix">';
															echo '<span class="pull-left">Task #2</span>';
															echo '<small class="pull-right">70%</small>';
														echo '</div>';
													  	echo '<div class="progress xs">';
															echo '<div class="progress-bar progress-bar-green" style="width: 70%;"></div>';
													  	echo '</div>';
													echo '</div><!-- /.col -->';
													echo '<div class="col-sm-6">';
													  	echo '<div class="clearfix">';
															echo '<span class="pull-left">Task #3</span>';
															echo '<small class="pull-right">60%</small>';
													  	echo '</div>';
													  	echo '<div class="progress xs">';
															echo '<div class="progress-bar progress-bar-green" style="width: 60%;"></div>';
													  	echo '</div>';

													  	echo '<div class="clearfix">';
															echo '<span class="pull-left">Task #4</span>';
															echo '<small class="pull-right">40%</small>';
													  	echo '</div>';
													  	echo '<div class="progress xs">';
															echo '<div class="progress-bar progress-bar-green" style="width: 40%;"></div>';
													  	echo '</div>';
													echo '</div><!-- /.col -->';
										  		echo '</div><!-- /.row -->';
											echo '</div>';
									  	echo '</div><!-- /.box -->';
									echo '</section><!-- right col -->';
								echo '</div><!-- /.row (main row) -->';
							*/
							echo '</section><!-- /.content -->';
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
					echo '<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>';
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
				        	echo '<p class="login-box-msg">Du bruker samme bruker overalt hos Infected.</p>';
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
				    echo '<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>';
				    //<!-- Bootstrap 3.3.2 JS -->
				    echo '<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>';
				    //<!-- iCheck -->
				    echo '<script src="plugins/iCheck/icheck.min.js" type="text/javascript"></script>';
				    echo '<script>';
				    	echo '$(function () {';
				        	echo '$(\'input\').iCheck({';
				          		echo 'checkboxClass: \'icheckbox_square-blue\',';
				          		echo 'radioClass: \'iradio_square-blue\',';
				          		echo 'increaseArea: \'20%\''; // optional';
				        	echo '});';
				      	echo '});';
				    echo '</script>';

				    // Other
				    echo '<script src="../api/scripts/login.js"></script>';
				echo '</body>';
			}

		echo '</html>';
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