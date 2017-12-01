<?php
/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2017 Infected <http://infected.no>.
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
 * License along with this library.  If not, see <http://www.gnu.org/licenses>.
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
		  	echo '<title>' . $this->getTitle() . '</title>';
				echo '<meta name="description" content="' . Settings::description . '">';
				echo '<meta name="keywords" content="' . Settings::keywords . '">';
				echo '<meta name="author" content="halvors and petterroea">';
				echo '<meta charset="utf-8">';
				echo '<link rel="shortcut icon" href="images/favicon.ico">';

				/* AdminLTE begin */
			  echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
				// Tell the browser to be responsive to screen width
			  echo '<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">';

				/* On top instead of bottom? Is this ok? - begin */
				// jQuery 3
				echo '<script src="bower_components/jquery/dist/jquery.min.js"></script>';
				// jQuery UI 1.11.4
				echo '<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>';
				/* On top instead of bottom? Is this ok? - end */

			  // Bootstrap 3.3.7
			  echo '<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">';
			  // Font Awesome
			  echo '<link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">';
			  // Ionicons
			  echo '<link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">';
			  // Theme style
			  echo '<link rel="stylesheet" href="dist/css/AdminLTE.min.css">';
			  // AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load.
			  echo '<link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">';
			  // Morris chart
			  echo '<link rel="stylesheet" href="bower_components/morris.js/morris.css">';
			  // jvectormap
			  echo '<link rel="stylesheet" href="bower_components/jvectormap/jquery-jvectormap.css">';
			  // Date Picker
			  echo '<link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">';
			  // Daterange picker
			  echo '<link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">';
			  // bootstrap wysihtml5 - text editor
			  echo '<link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">';
			  // Google Font
			  echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">';
				/* AdminLTE end */

				/* AdminLTE - Login begin */
				// iCheck
				echo '<link rel="stylesheet" href="plugins/iCheck/square/blue.css">';
				/* AdminLTE - Login end */

				if (!Session::isAuthenticated()) {
					echo '<script src="../api/scripts/login.js"></script>';
				} else {
					echo '<script src="../api/scripts/logout.js"></script>';
				}

				//echo '<link rel="stylesheet" href="styles/style.css">';
				//echo '<link rel="stylesheet" href="styles/topmenu.css">';
				//echo '<link rel="stylesheet" href="styles/menu.css">';
				//echo '<link rel="stylesheet" href="../api/libraries/chosen/chosen.css">';
        //echo '<link rel="stylesheet" href="fonts/font-awesome/css/font-awesome.min.css">';
				//echo '<script src="../api/scripts/jquery-1.11.3.min.js"></script>';
				//echo '<script src="../api/scripts/jquery.form.min.js"></script>';
				//echo '<script src="../api/scripts/login.js"></script>';
				//echo '<script src="../api/scripts/logout.js"></script>';
				//echo '<script src="../api/libraries/chosen/chosen.jquery.js"></script>';
				//echo '<script src="../api/libraries/ckeditor/ckeditor.js"></script>';
				//echo '<script src="../api/libraries/ckeditor/adapters/jquery.js"></script>';
				//echo '<script src="scripts/site.js"></script>';
				//echo '<script src="scripts/common.js"></script>';

				// Google analytics
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
				echo '<body class="hold-transition skin-blue sidebar-mini">';
					echo '<div class="wrapper">';
						echo '<header class="main-header">';
							// Logo
							echo '<a href="." class="logo">';
								echo '<span class="logo-mini"><b>' . substr(Settings::name, 0, 1) . '</b>C</span>'; // mini logo for sidebar mini 50x50 pixels
								echo '<span class="logo-lg"><b>' . Settings::name . '</b> Crew</span>'; // logo for regular state and mobile devices
							echo '</a>';

							echo <<< EOD
							<!-- Header Navbar: style can be found in header.less -->
							<nav class="navbar navbar-static-top">
								<!-- Sidebar toggle button-->
								<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
									<span class="sr-only">Toggle navigation</span>
								</a>
								<div class="navbar-custom-menu">
									<ul class="nav navbar-nav">
										<!-- Messages: style can be found in dropdown.less-->
										<li class="dropdown messages-menu">
											<a href="#" class="dropdown-toggle" data-toggle="dropdown">
												<i class="fa fa-envelope-o"></i>
												<span class="label label-success">4</span>
											</a>
											<ul class="dropdown-menu">
												<li class="header">You have 4 messages</li>
												<li>
													<!-- inner menu: contains the actual data -->
													<ul class="menu">
														<li><!-- start message -->
															<a href="#">
																<div class="pull-left">
																	<img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
																</div>
																<h4>
																	Support Team
																	<small><i class="fa fa-clock-o"></i> 5 mins</small>
																</h4>
																<p>Why not buy a new awesome theme?</p>
															</a>
														</li>
														<!-- end message -->
														<li>
															<a href="#">
																<div class="pull-left">
																	<img src="dist/img/user3-128x128.jpg" class="img-circle" alt="User Image">
																</div>
																<h4>
																	AdminLTE Design Team
																	<small><i class="fa fa-clock-o"></i> 2 hours</small>
																</h4>
																<p>Why not buy a new awesome theme?</p>
															</a>
														</li>
														<li>
															<a href="#">
																<div class="pull-left">
																	<img src="dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">
																</div>
																<h4>
																	Developers
																	<small><i class="fa fa-clock-o"></i> Today</small>
																</h4>
																<p>Why not buy a new awesome theme?</p>
															</a>
														</li>
														<li>
															<a href="#">
																<div class="pull-left">
																	<img src="dist/img/user3-128x128.jpg" class="img-circle" alt="User Image">
																</div>
																<h4>
																	Sales Department
																	<small><i class="fa fa-clock-o"></i> Yesterday</small>
																</h4>
																<p>Why not buy a new awesome theme?</p>
															</a>
														</li>
														<li>
															<a href="#">
																<div class="pull-left">
																	<img src="dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">
																</div>
																<h4>
																	Reviewers
																	<small><i class="fa fa-clock-o"></i> 2 days</small>
																</h4>
																<p>Why not buy a new awesome theme?</p>
															</a>
														</li>
													</ul>
												</li>
												<li class="footer"><a href="#">See All Messages</a></li>
											</ul>
										</li>
										<!-- Notifications: style can be found in dropdown.less -->
										<li class="dropdown notifications-menu">
											<a href="#" class="dropdown-toggle" data-toggle="dropdown">
												<i class="fa fa-bell-o"></i>
												<span class="label label-warning">10</span>
											</a>
											<ul class="dropdown-menu">
												<li class="header">You have 10 notifications</li>
												<li>
													<!-- inner menu: contains the actual data -->
													<ul class="menu">
														<li>
															<a href="#">
																<i class="fa fa-users text-aqua"></i> 5 new members joined today
															</a>
														</li>
														<li>
															<a href="#">
																<i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
																page and may cause design problems
															</a>
														</li>
														<li>
															<a href="#">
																<i class="fa fa-users text-red"></i> 5 new members joined
															</a>
														</li>
														<li>
															<a href="#">
																<i class="fa fa-shopping-cart text-green"></i> 25 sales made
															</a>
														</li>
														<li>
															<a href="#">
																<i class="fa fa-user text-red"></i> You changed your username
															</a>
														</li>
													</ul>
												</li>
												<li class="footer"><a href="#">View all</a></li>
											</ul>
										</li>
										<!-- Tasks: style can be found in dropdown.less -->
										<li class="dropdown tasks-menu">
											<a href="#" class="dropdown-toggle" data-toggle="dropdown">
												<i class="fa fa-flag-o"></i>
												<span class="label label-danger">9</span>
											</a>
											<ul class="dropdown-menu">
												<li class="header">You have 9 tasks</li>
												<li>
													<!-- inner menu: contains the actual data -->
													<ul class="menu">
														<li><!-- Task item -->
															<a href="#">
																<h3>
																	Design some buttons
																	<small class="pull-right">20%</small>
																</h3>
																<div class="progress xs">
																	<div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"
																			 aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
																		<span class="sr-only">20% Complete</span>
																	</div>
																</div>
															</a>
														</li>
														<!-- end task item -->
														<li><!-- Task item -->
															<a href="#">
																<h3>
																	Create a nice theme
																	<small class="pull-right">40%</small>
																</h3>
																<div class="progress xs">
																	<div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar"
																			 aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
																		<span class="sr-only">40% Complete</span>
																	</div>
																</div>
															</a>
														</li>
														<!-- end task item -->
														<li><!-- Task item -->
															<a href="#">
																<h3>
																	Some task I need to do
																	<small class="pull-right">60%</small>
																</h3>
																<div class="progress xs">
																	<div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar"
																			 aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
																		<span class="sr-only">60% Complete</span>
																	</div>
																</div>
															</a>
														</li>
														<!-- end task item -->
														<li><!-- Task item -->
															<a href="#">
																<h3>
																	Make beautiful transitions
																	<small class="pull-right">80%</small>
																</h3>
																<div class="progress xs">
																	<div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar"
																			 aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
																		<span class="sr-only">80% Complete</span>
																	</div>
																</div>
															</a>
														</li>
														<!-- end task item -->
													</ul>
												</li>
												<li class="footer">
													<a href="#">View all tasks</a>
												</li>
											</ul>
										</li>
										<!-- User Account: style can be found in dropdown.less -->
										<li class="dropdown user user-menu">
											<a href="#" class="dropdown-toggle" data-toggle="dropdown">
												<img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
												<span class="hidden-xs">Alexander Pierce</span>
											</a>
											<ul class="dropdown-menu">
												<!-- User image -->
												<li class="user-header">
													<img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

													<p>
														Alexander Pierce - Web Developer
														<small>Member since Nov. 2012</small>
													</p>
												</li>
												<!-- Menu Body -->
												<li class="user-body">
													<div class="row">
														<div class="col-xs-4 text-center">
															<a href="#">Followers</a>
														</div>
														<div class="col-xs-4 text-center">
															<a href="#">Sales</a>
														</div>
														<div class="col-xs-4 text-center">
															<a href="#">Friends</a>
														</div>
													</div>
													<!-- /.row -->
												</li>
												<!-- Menu Footer-->
												<li class="user-footer">
													<div class="pull-left">
														<a href="#" class="btn btn-default btn-flat">Profile</a>
													</div>
													<div class="pull-right">
														<a href="#" class="btn btn-default btn-flat">Sign out</a>
													</div>
												</li>
											</ul>
										</li>
										<!-- Control Sidebar Toggle Button -->
										<li>
											<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
										</li>
									</ul>
								</div>
							</nav>
						</header>
						<!-- Left side column. contains the logo and sidebar -->
						<aside class="main-sidebar">
							<!-- sidebar: style can be found in sidebar.less -->
							<section class="sidebar">
								<!-- Sidebar user panel -->
								<div class="user-panel">
									<div class="pull-left image">
										<img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
									</div>
									<div class="pull-left info">
										<p>Alexander Pierce</p>
										<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
									</div>
								</div>
								<!-- search form -->
								<form action="#" method="get" class="sidebar-form">
									<div class="input-group">
										<input type="text" name="q" class="form-control" placeholder="Search...">
										<span class="input-group-btn">
													<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
													</button>
												</span>
									</div>
								</form>
								<!-- /.search form -->
								<!-- sidebar menu: : style can be found in sidebar.less -->
								<ul class="sidebar-menu" data-widget="tree">
									<li class="header">MAIN NAVIGATION</li>
									<li class="active treeview">
										<a href="#">
											<i class="fa fa-dashboard"></i> <span>Dashboard</span>
											<span class="pull-right-container">
												<i class="fa fa-angle-left pull-right"></i>
											</span>
										</a>
										<ul class="treeview-menu">
											<li class="active"><a href="index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
											<li><a href="index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
										</ul>
									</li>
									<li class="treeview">
										<a href="#">
											<i class="fa fa-files-o"></i>
											<span>Layout Options</span>
											<span class="pull-right-container">
												<span class="label label-primary pull-right">4</span>
											</span>
										</a>
										<ul class="treeview-menu">
											<li><a href="pages/layout/top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>
											<li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>
											<li><a href="pages/layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>
											<li><a href="pages/layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>
										</ul>
									</li>
									<li>
										<a href="pages/widgets.html">
											<i class="fa fa-th"></i> <span>Widgets</span>
											<span class="pull-right-container">
												<small class="label pull-right bg-green">new</small>
											</span>
										</a>
									</li>
									<li class="treeview">
										<a href="#">
											<i class="fa fa-pie-chart"></i>
											<span>Charts</span>
											<span class="pull-right-container">
												<i class="fa fa-angle-left pull-right"></i>
											</span>
										</a>
										<ul class="treeview-menu">
											<li><a href="pages/charts/chartjs.html"><i class="fa fa-circle-o"></i> ChartJS</a></li>
											<li><a href="pages/charts/morris.html"><i class="fa fa-circle-o"></i> Morris</a></li>
											<li><a href="pages/charts/flot.html"><i class="fa fa-circle-o"></i> Flot</a></li>
											<li><a href="pages/charts/inline.html"><i class="fa fa-circle-o"></i> Inline charts</a></li>
										</ul>
									</li>
									<li class="treeview">
										<a href="#">
											<i class="fa fa-laptop"></i>
											<span>UI Elements</span>
											<span class="pull-right-container">
												<i class="fa fa-angle-left pull-right"></i>
											</span>
										</a>
										<ul class="treeview-menu">
											<li><a href="pages/UI/general.html"><i class="fa fa-circle-o"></i> General</a></li>
											<li><a href="pages/UI/icons.html"><i class="fa fa-circle-o"></i> Icons</a></li>
											<li><a href="pages/UI/buttons.html"><i class="fa fa-circle-o"></i> Buttons</a></li>
											<li><a href="pages/UI/sliders.html"><i class="fa fa-circle-o"></i> Sliders</a></li>
											<li><a href="pages/UI/timeline.html"><i class="fa fa-circle-o"></i> Timeline</a></li>
											<li><a href="pages/UI/modals.html"><i class="fa fa-circle-o"></i> Modals</a></li>
										</ul>
									</li>
									<li class="treeview">
										<a href="#">
											<i class="fa fa-edit"></i> <span>Forms</span>
											<span class="pull-right-container">
												<i class="fa fa-angle-left pull-right"></i>
											</span>
										</a>
										<ul class="treeview-menu">
											<li><a href="pages/forms/general.html"><i class="fa fa-circle-o"></i> General Elements</a></li>
											<li><a href="pages/forms/advanced.html"><i class="fa fa-circle-o"></i> Advanced Elements</a></li>
											<li><a href="pages/forms/editors.html"><i class="fa fa-circle-o"></i> Editors</a></li>
										</ul>
									</li>
									<li class="treeview">
										<a href="#">
											<i class="fa fa-table"></i> <span>Tables</span>
											<span class="pull-right-container">
												<i class="fa fa-angle-left pull-right"></i>
											</span>
										</a>
										<ul class="treeview-menu">
											<li><a href="pages/tables/simple.html"><i class="fa fa-circle-o"></i> Simple tables</a></li>
											<li><a href="pages/tables/data.html"><i class="fa fa-circle-o"></i> Data tables</a></li>
										</ul>
									</li>
									<li>
										<a href="pages/calendar.html">
											<i class="fa fa-calendar"></i> <span>Calendar</span>
											<span class="pull-right-container">
												<small class="label pull-right bg-red">3</small>
												<small class="label pull-right bg-blue">17</small>
											</span>
										</a>
									</li>
									<li>
										<a href="pages/mailbox/mailbox.html">
											<i class="fa fa-envelope"></i> <span>Mailbox</span>
											<span class="pull-right-container">
												<small class="label pull-right bg-yellow">12</small>
												<small class="label pull-right bg-green">16</small>
												<small class="label pull-right bg-red">5</small>
											</span>
										</a>
									</li>
									<li class="treeview">
										<a href="#">
											<i class="fa fa-folder"></i> <span>Examples</span>
											<span class="pull-right-container">
												<i class="fa fa-angle-left pull-right"></i>
											</span>
										</a>
										<ul class="treeview-menu">
											<li><a href="pages/examples/invoice.html"><i class="fa fa-circle-o"></i> Invoice</a></li>
											<li><a href="pages/examples/profile.html"><i class="fa fa-circle-o"></i> Profile</a></li>
											<li><a href="pages/examples/login.html"><i class="fa fa-circle-o"></i> Login</a></li>
											<li><a href="pages/examples/register.html"><i class="fa fa-circle-o"></i> Register</a></li>
											<li><a href="pages/examples/lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen</a></li>
											<li><a href="pages/examples/404.html"><i class="fa fa-circle-o"></i> 404 Error</a></li>
											<li><a href="pages/examples/500.html"><i class="fa fa-circle-o"></i> 500 Error</a></li>
											<li><a href="pages/examples/blank.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>
											<li><a href="pages/examples/pace.html"><i class="fa fa-circle-o"></i> Pace Page</a></li>
										</ul>
									</li>
									<li class="treeview">
										<a href="#">
											<i class="fa fa-share"></i> <span>Multilevel</span>
											<span class="pull-right-container">
												<i class="fa fa-angle-left pull-right"></i>
											</span>
										</a>
										<ul class="treeview-menu">
											<li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
											<li class="treeview">
												<a href="#"><i class="fa fa-circle-o"></i> Level One
													<span class="pull-right-container">
														<i class="fa fa-angle-left pull-right"></i>
													</span>
												</a>
												<ul class="treeview-menu">
													<li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
													<li class="treeview">
														<a href="#"><i class="fa fa-circle-o"></i> Level Two
															<span class="pull-right-container">
																<i class="fa fa-angle-left pull-right"></i>
															</span>
														</a>
														<ul class="treeview-menu">
															<li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
															<li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
														</ul>
													</li>
												</ul>
											</li>
											<li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
										</ul>
									</li>
									<li><a href="https://adminlte.io/docs"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
									<li class="header">LABELS</li>
									<li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
									<li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
									<li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>
								</ul>
							</section>
							<!-- /.sidebar -->
						</aside>

						<!-- Content Wrapper. Contains page content -->
						<div class="content-wrapper">
							<!-- Content Header (Page header) -->
							<section class="content-header">
								<h1>
									Dashboard
									<small>Control panel</small>
								</h1>
								<ol class="breadcrumb">
									<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
									<li class="active">Dashboard</li>
								</ol>
							</section>

							<!-- Main content -->
							<section class="content">

								// TODO: Main content.

							</section>
							<!-- /.content -->
						</div>
						<!-- /.content-wrapper -->
						<footer class="main-footer">
							<div class="pull-right hidden-xs">
								<b>Version</b> 2.4.0
							</div>
							<strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
							reserved.
						</footer>

						<!-- Control Sidebar -->
						<aside class="control-sidebar control-sidebar-dark">
							<!-- Create the tabs -->
							<ul class="nav nav-tabs nav-justified control-sidebar-tabs">
								<li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
								<li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
							</ul>
							<!-- Tab panes -->
							<div class="tab-content">
								<!-- Home tab content -->
								<div class="tab-pane" id="control-sidebar-home-tab">
									<h3 class="control-sidebar-heading">Recent Activity</h3>
									<ul class="control-sidebar-menu">
										<li>
											<a href="javascript:void(0)">
												<i class="menu-icon fa fa-birthday-cake bg-red"></i>

												<div class="menu-info">
													<h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

													<p>Will be 23 on April 24th</p>
												</div>
											</a>
										</li>
										<li>
											<a href="javascript:void(0)">
												<i class="menu-icon fa fa-user bg-yellow"></i>

												<div class="menu-info">
													<h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

													<p>New phone +1(800)555-1234</p>
												</div>
											</a>
										</li>
										<li>
											<a href="javascript:void(0)">
												<i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

												<div class="menu-info">
													<h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

													<p>nora@example.com</p>
												</div>
											</a>
										</li>
										<li>
											<a href="javascript:void(0)">
												<i class="menu-icon fa fa-file-code-o bg-green"></i>

												<div class="menu-info">
													<h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

													<p>Execution time 5 seconds</p>
												</div>
											</a>
										</li>
									</ul>
									<!-- /.control-sidebar-menu -->

									<h3 class="control-sidebar-heading">Tasks Progress</h3>
									<ul class="control-sidebar-menu">
										<li>
											<a href="javascript:void(0)">
												<h4 class="control-sidebar-subheading">
													Custom Template Design
													<span class="label label-danger pull-right">70%</span>
												</h4>

												<div class="progress progress-xxs">
													<div class="progress-bar progress-bar-danger" style="width: 70%"></div>
												</div>
											</a>
										</li>
										<li>
											<a href="javascript:void(0)">
												<h4 class="control-sidebar-subheading">
													Update Resume
													<span class="label label-success pull-right">95%</span>
												</h4>

												<div class="progress progress-xxs">
													<div class="progress-bar progress-bar-success" style="width: 95%"></div>
												</div>
											</a>
										</li>
										<li>
											<a href="javascript:void(0)">
												<h4 class="control-sidebar-subheading">
													Laravel Integration
													<span class="label label-warning pull-right">50%</span>
												</h4>

												<div class="progress progress-xxs">
													<div class="progress-bar progress-bar-warning" style="width: 50%"></div>
												</div>
											</a>
										</li>
										<li>
											<a href="javascript:void(0)">
												<h4 class="control-sidebar-subheading">
													Back End Framework
													<span class="label label-primary pull-right">68%</span>
												</h4>

												<div class="progress progress-xxs">
													<div class="progress-bar progress-bar-primary" style="width: 68%"></div>
												</div>
											</a>
										</li>
									</ul>
									<!-- /.control-sidebar-menu -->

								</div>
								<!-- /.tab-pane -->
								<!-- Stats tab content -->
								<div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
								<!-- /.tab-pane -->
								<!-- Settings tab content -->
								<div class="tab-pane" id="control-sidebar-settings-tab">
									<form method="post">
										<h3 class="control-sidebar-heading">General Settings</h3>

										<div class="form-group">
											<label class="control-sidebar-subheading">
												Report panel usage
												<input type="checkbox" class="pull-right" checked>
											</label>

											<p>
												Some information about this general settings option
											</p>
										</div>
										<!-- /.form-group -->

										<div class="form-group">
											<label class="control-sidebar-subheading">
												Allow mail redirect
												<input type="checkbox" class="pull-right" checked>
											</label>

											<p>
												Other sets of options are available
											</p>
										</div>
										<!-- /.form-group -->

										<div class="form-group">
											<label class="control-sidebar-subheading">
												Expose author name in posts
												<input type="checkbox" class="pull-right" checked>
											</label>

											<p>
												Allow the user to show his name in blog posts
											</p>
										</div>
										<!-- /.form-group -->

										<h3 class="control-sidebar-heading">Chat Settings</h3>

										<div class="form-group">
											<label class="control-sidebar-subheading">
												Show me as online
												<input type="checkbox" class="pull-right" checked>
											</label>
										</div>
										<!-- /.form-group -->

										<div class="form-group">
											<label class="control-sidebar-subheading">
												Turn off notifications
												<input type="checkbox" class="pull-right">
											</label>
										</div>
										<!-- /.form-group -->

										<div class="form-group">
											<label class="control-sidebar-subheading">
												Delete chat history
												<a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
											</label>
										</div>
										<!-- /.form-group -->
									</form>
								</div>
								<!-- /.tab-pane -->
							</div>
						</aside>
						<!-- /.control-sidebar -->
						<!-- Add the sidebar's background. This div must be placed
								 immediately after the control sidebar -->
						<div class="control-sidebar-bg"></div>
					</div>
					<!-- ./wrapper -->
EOD;
				} else {
					$publicPages = ['register',
													'activation',
													'reset-password'];

					// Show page if whitelisted.
					if (in_array($this->pageName, $publicPages)) {
						$this->getPage($this->pageName);
					} else {
						$this->getLoginForm();
					}
				}

				// jQuery 3
				echo '<script src="bower_components/jquery/dist/jquery.min.js"></script>';
				// jQuery UI 1.11.4
				echo '<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>';
				// Resolve conflict in jQuery UI tooltip with Bootstrap tooltip
				echo '<script>';
					echo '$.widget.bridge(\'uibutton\', $.ui.button);';
				echo '</script>';
				// Bootstrap 3.3.7
				echo '<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>';
				// Morris.js charts
				echo '<script src="bower_components/raphael/raphael.min.js"></script>';
				echo '<script src="bower_components/morris.js/morris.min.js"></script>';
				// Sparkline
				echo '<script src="bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>';
				// jvectormap
				echo '<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>';
				echo '<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>';
				// jQuery Knob Chart
				echo '<script src="bower_components/jquery-knob/dist/jquery.knob.min.js"></script>';
				// daterangepicker
				echo '<script src="bower_components/moment/min/moment.min.js"></script>';
				echo '<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>';
				// datepicker
				echo '<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>';
				// Bootstrap WYSIHTML5
				echo '<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>';
				// Slimscroll
				echo '<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>';
				// FastClick
				echo '<script src="bower_components/fastclick/lib/fastclick.js"></script>';
				// AdminLTE App
				echo '<script src="dist/js/adminlte.min.js"></script>';
				/*
				// AdminLTE dashboard demo (This is only for demo purposes) -->
				echo '<script src="dist/js/pages/dashboard.js"></script>';
				// AdminLTE for demo purposes -->
				echo '<script src="dist/js/demo.js"></script>';
				*/
			echo '</body>';
		echo '</html>';
	}

	// Generates title based on current page / article.
	private function getTitle() {
		return Settings::name . ' Crew';
	}

	private function getLoginForm() {
		echo '<body class="hold-transition login-page">';
			echo '<div class="login-box">';
				echo '<div class="login-logo">';
					echo '<a href="."><b>' . Settings::name . '</b> Crew</a>';
				echo '</div>'; // .login-logo';
				echo '<div class="login-box-body">';
					echo '<p class="login-box-msg">Du kan bruke samme bruker overalt hos <b>' . Settings::name . '</b>.</p>';
					echo '<form class="login" method="post">';
						echo '<div class="form-group has-feedback">';
							echo '<input type="text" name="identifier" class="form-control" placeholder="Brukernavn eller e-post">';
							echo '<span class="glyphicon glyphicon-envelope form-control-feedback"></span>';
						echo '</div>';
						echo '<div class="form-group has-feedback">';
							echo '<input type="password" name="password" class="form-control" placeholder="Passord">';
							echo '<span class="glyphicon glyphicon-lock form-control-feedback"></span>';
						echo '</div>';
						echo '<div class="row">';
							echo '<div class="col-xs-8">';
								/*
								echo '<div class="checkbox icheck">';
									echo '<label><input type="checkbox"> Husk meg</label>';
								echo '</div>';
								*/
							echo '</div>'; // .col-xs-8
							echo '<div class="col-xs-4">';
									echo '<button class="btn btn-primary btn-block btn-flat">Logg inn</button>';
							echo '</div>'; // .col-xs-4
						echo '</div>';
					echo '</form>';
					echo '<a href="?page=reset-password">Jeg har glemt passordet mitt!</a><br>';
					echo '<a href="?page=register">Register ny bruker</a>';
				echo '</div><!-- /.login-box-body -->';
			echo '</div><!-- /.login-box -->';
			// iCheck
			/*
			echo '<script src="../../plugins/iCheck/icheck.min.js"></script>';
			echo '<script>';
				echo '$(function () {';
					echo '$(\'input\').iCheck({';
						echo 'checkboxClass: \'icheckbox_square-blue\',';
						echo 'radioClass: \'iradio_square-blue\',';
						echo 'increaseArea: \'20%\''; // Optional
					echo '});';
				echo '});';
			echo '</script>';
			*/
		echo '</body>';
	}

	private function getMessages() {

	}

	private function getNotifications() {
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

	private getTasks() {

	}

	private getMenu() {
		echo '<ul class="sidebar-menu">';
			echo '<li class="header">Hovedmeny</li>';

			if ($user->isGroupMember()) {
				$group = $user->getGroup();

				// If the user is member of a team, also fetch team only pages.
				if ($user->isTeamMember()) {
					$pageList = RestrictedPageHandler::getPagesForGroupAndTeam($group, $user->getTeam());
				} else {
					$pageList = RestrictedPageHandler::getPagesForGroup($group);
				}

				$pageNameList = [];

				foreach ($pageList as $page) {
					array_push($pageNameList, strtolower($page->getName()));
				}

				$teamList = $group->getTeams();
				$teamNameList = [];

				foreach ($teamList as $team) {
					array_push($teamNameList, strtolower($team->getName()));
				}

				// Only show pages for that group.
				if (empty($pageList) &&
					empty($teamList)) {
					echo '<li><a href="?page=my-crew"><i class="fa fa-user"></i><span>Mitt crew</span></a></li>';
				} else {
					echo '<li class="treeview' . ($this->pageName == 'my-crew' ? ' active' : null) . '">';
						echo '<a href="?page=my-crew"><i class="fa fa-user"></i><span>Mitt crew</span><i class="fa fa-angle-left pull-right"></i></a>';
						echo '<ul class="treeview-menu">';
							echo '<li><a href="?page=my-crew"><i class="fa fa-circle-o"></i>' . $group->getTitle() . '</a></li>';

							// Only create link for groups that actually contain teams.
							if (!empty($teamList)) {
								foreach ($teamList as $team) {
									echo '<li' . (isset($_GET['teamId']) && $team->getId() == $_GET['teamId'] ? ' class="active"' : null) .'><a href="?page=my-crew&teamId=' . $team->getId() . '"><i class="fa fa-circle-o"></i>' . $team->getTitle() . '</a></li>';
								}
							}

							if (!empty($pageList)) {
								foreach ($pageList as $page) {
									if (strtolower($page->getName()) != strtolower($group->getName())) {
										if (!in_array(strtolower($page->getName()), $teamNameList)) {
											echo '<li><a href="?page=' . $page->getName() . '"><i class="fa fa-circle-o"></i>' . $page->getTitle() . '</a></li>';
										}
									}
								}
							}

						echo '</ul>';
					echo '</li>';
				}
			}

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

			if ($user->hasPermission('event')) {
				echo '<li class="treeview' . (StringUtils::startsWith($this->pageName, 'event') || $this->pageName == 'edit-note') ? ' active' : null) . '">';
					echo '<a href="?page=event"><i class="fa fa-calendar"></i><span>Arrangement</span><i class="fa fa-angle-left pull-right"></i></a>';
					echo '<ul class="treeview-menu">';

						if ($user->hasPermission('event.checkin')) {
							echo '<li' . ($this->pageName == 'event-checkin' ? ' class="active"' : null) . '><a href="?page=event-checkin"><i class="fa fa-check"></i>Innsjekk</a></li>';
						}

						if ($user->hasPermission('event.checklist')) {
							echo '<li><a' . ($this->pageName == 'event-checklist' || $this->pageName == 'edit-note' ? ' class="active"' : null) . ' href="index.php?page=event-checklist"><i class="fa fa-check"></i>Sjekkliste</a></li>';
						}








						if ($user->hasPermission('event.seatmap')) {
							echo '<li' . ($this->pageName == 'event-seatmap' ? ' class="active"' : null) . '><a href="?page=event-seatmap"><i class="fa fa-map-marker"></i>Setekart</a></li>';
						}

						if ($user->hasPermission('event.screen')) {
							echo '<li' . ($this->pageName == 'event-screen' ? ' class="active"' : null) . '><a href="?page=event-screen"><i class="fa fa-desktop"></i>Skjerm</a></li>';
						}

						if ($user->hasPermission('event.agenda')) {
							echo '<li' . ($this->pageName == 'event-agenda' ? ' class="active"' : null) . '><a href="?page=event-agenda"><i class="fa fa-clock-o"></i>Agenda</a></li>';
						}

						if ($user->hasPermission('event.table-labels')) {
							echo '<li><a href="../api/pages/utils/printTableLabels.php"><i class="fa fa-external-link"></i>Print bordlapper</a></li>';
						}

					echo '</ul>';
				echo '</li>';
			}

			if ($user->hasPermission('chief')) {
				echo '<li class="treeview' . (StringUtils::startsWith($this->pageName, 'chief') ? ' active' : null) . '">';
					echo '<a href="?page=chief"><i class="fa fa-gavel"></i><span>Chief</span><i class="fa fa-angle-left pull-right"></i></a>';
					echo '<ul class="treeview-menu">';

						if ($user->hasPermission('chief.group')) {
							echo '<li' . ($this->pageName == 'chief-group' ? ' class="active"' : null) . '><a href="?page=chief-group"><i class="fa fa-circle-o"></i>Crew</a></li>';
						}

						if ($user->hasPermission('chief.team')) {
							echo '<li' . ($this->pageName == 'chief-team' ? ' class="active"' : null) . '><a href="?page=chief-team"><i class="fa fa-circle-o"></i>Lag</a></li>';
						}

						if ($user->hasPermission('chief.avatar')) {
							echo '<li' . ($this->pageName == 'chief-avatar' ? ' class="active"' : null) . '><a href="?page=chief-avatar"><i class="fa fa-circle-o"></i>Profilbilder</a></li>';
						}

						if ($user->hasPermission('chief.application')) {
							echo '<li' . ($this->pageName == 'chief-application' || $this->pageName == 'application' ? ' class="active"' : null) . '><a href="?page=chief-application"><i class="fa fa-circle-o"></i>Søknader</a></li>';
						}

						if ($user->hasPermission('chief.my-crew')) {
							echo '<li' . ($this->pageName == 'chief-my-crew' || $this->pageName == 'edit-restricted-page' ? ' class="active"' : null) . '><a href="?page=chief-my-crew"><i class="fa fa-circle-o"></i>My crew</a></li>';
						}

						if ($user->hasPermission('chief.email')) {
							echo '<li' . ($this->pageName == 'chief-email' ? ' class="active"' : null) . '><a href="?page=chief-email"><i class="fa fa-send"></i>Send e-post</a></li>';
						}

					echo '</ul>';
				echo '</li>';
			}

			if ($user->hasPermission('developer')) {
				echo '<li class="treeview' . (StringUtils::startsWith($this->pageName, 'developer') ? ' active' : null) . '">';
					echo '<a href="?page=developer"><i class="fa fa-wrench"></i><span>Utvikler</span><i class="fa fa-angle-left pull-right"></i></a>';
					echo '<ul class="treeview-menu">';

						if ($user->hasPermission('developer.change-user')) {
							echo '<li' . ($this->pageName == 'developer-change-user' ? ' class="active"' : null) . '><a href="?page=developer-change-user"><i class="fa fa-rocket"></i>Bytt bruker</a></li>';
						}

					echo '</ul>';
				echo '</li>';
			}

			if ($user->hasPermission('admin')) {
				echo '<li class="treeview' . (StringUtils::startsWith($this->pageName, 'admin') ? ' active' : null) . '">';
					echo '<a href="?page=admin"><i class="fa fa-wrench"></i><span>Administrator</span><i class="fa fa-angle-left pull-right"></i></a>';
					echo '<ul class="treeview-menu">';

						if ($user->hasPermission('admin.events')) {
							echo '<li' . ($this->pageName == 'admin-events' ? ' class="active"' : null) . '><a href="?page=admin-events"><i class="fa fa-calendar"></i>Arrangementer</a></li>';
						}

						if ($user->hasPermission('admin.permissions')) {
							echo '<li' . ($this->pageName == 'admin-permissions' ? ' class="active"' : null) . '><a href="?page=admin-permissions"><i class="fa fa-check-square-o"></i>Rettigheter</a></li>';
						}

						if ($user->hasPermission('admin.memberlist')) {
							echo '<li><a' . ($this->pageName == 'admin-memberlist' ? ' class="active"' : null) . ' href="index.php?page=admin-memberlist"><i class="fa fa-check-square-o"></i>Medlemsliste</a></li>';
						}

						if ($user->hasPermission('admin.seatmap')) {
							echo '<li' . ($this->pageName == 'admin-seatmap' ? ' class="active"' : null) . '><a href="?page=admin-seatmap"><i class="fa fa-map-marker"></i>Endre setekart</a></li>';
						}

						if ($user->hasPermission('admin.website')) {
							echo '<li' . ($this->pageName == 'admin-website' || $this->pageName == 'edit-page' ? ' class="active"' : null) . '><a href="?page=admin-website"><i class="fa fa-edit"></i>Endre hovedsiden</a></li>';
						}

					echo '</ul>';
				echo '</li>';
			}

			echo '</ul>';
	}

	private function getPage($pageName) {
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
