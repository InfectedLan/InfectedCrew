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
require_once 'utils/dateutils.php';

class Site {
	private $pageName;
	
	public function __construct() {
		$this->pageName = isset($_GET['page']) ? strtolower($_GET['page']) : 'my-crew';
	}
	
	// Execute the site.
	public function execute() {
		?>
		<!DOCTYPE html>
		<html>
		  	<head>
		  		<?php
			  		echo '<title>' . Settings::name . ' Crew</title>';
					echo '<meta name="description" content="' . Settings::description . '">';
					echo '<meta name="keywords" content="' . Settings::keywords . '">';
					echo '<meta name="author" content="halvors and petterroea">';
					echo '<meta charset="UTF-8">';
					echo '<script src="../api/scripts/login.js"></script>';
					echo '<script src="../api/scripts/logout.js"></script>';
		  		?>
				<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
				<!-- Bootstrap 3.3.4 -->
				<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />	
				<!-- FontAwesome 4.3.0 -->
				<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
				<!-- Ionicons 2.0.0 -->
				<link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />	
				<!-- Theme style -->
				<link href="dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
				<!-- AdminLTE Skins. Choose a skin from the css/skins 
					 folder instead of downloading all of them to reduce the load. -->
				<link href="dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
				<!-- iCheck -->
				<link href="plugins/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />
				<!-- Morris chart -->
				<link href="plugins/morris/morris.css" rel="stylesheet" type="text/css" />
				<!-- jvectormap -->
				<link href="plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
				<!-- Date Picker -->
				<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
				<!-- Daterange picker -->
				<link href="plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
				<!-- bootstrap wysihtml5 - text editor -->
				<link href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />

				<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
				<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
				<!--[if lt IE 9]>
					<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
					<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
				<![endif]-->
				<?php
					echo '<script>';
						echo '(function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){';
						echo '(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),';
						echo 'm=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)';
						echo '})(window,document,\'script\',\'//www.google-analytics.com/analytics.js\',\'ga\');';

						echo 'ga(\'create\', \'UA-54254513-3\', \'auto\');';
						echo 'ga(\'send\', \'pageview\');';
					echo '</script>';	
				?>
		  	</head>
		  	<body class="skin-blue sidebar-mini">
				<div class="wrapper">
			  		<header class="main-header">
						<!-- Logo -->
						<a href="." class="logo">
							<?php
						  		//<!-- mini logo for sidebar mini 50x50 pixels -->
						  		echo '<span class="logo-mini"><b>' . Settings::name[0] . '</b>C</span>';

						  		//<!-- logo for regular state and mobile devices -->
					  			echo '<span class="logo-lg"><b>' . Settings::name . '</b> Crew</span>';
					  		?>
						</a>
						<!-- Header Navbar: style can be found in header.less -->
						<nav class="navbar navbar-static-top" role="navigation">
					  		<!-- Sidebar toggle button-->
					  		<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
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
																<img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
														  	</div>
														  	<h4>
																Support Team
																<small><i class="fa fa-clock-o"></i> 5 mins</small>
														  	</h4>
														  	<p>Why not buy a new awesome theme?</p>
														</a>
							 						</li><!-- end message -->
							  						<li>
														<a href="#">
														  	<div class="pull-left">
																<img src="dist/img/user3-128x128.jpg" class="img-circle" alt="user image"/>
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
																<img src="dist/img/user4-128x128.jpg" class="img-circle" alt="user image"/>
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
																<img src="dist/img/user3-128x128.jpg" class="img-circle" alt="user image"/>
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
																<img src="dist/img/user4-128x128.jpg" class="img-circle" alt="user image"/>
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
													  		<i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the page and may cause design problems
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
																<div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
														  			<span class="sr-only">20% Complete</span>
																</div>
													  		</div>
														</a>
												  	</li><!-- end task item -->
												  	<li><!-- Task item -->
														<a href="#">
													  		<h3>
																Create a nice theme
																<small class="pull-right">40%</small>
													  		</h3>
													  		<div class="progress xs">
																<div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
														  			<span class="sr-only">40% Complete</span>
																</div>
													  		</div>
														</a>
												  	</li><!-- end task item -->
												  	<li><!-- Task item -->
														<a href="#">
													  		<h3>
																Some task I need to do
																<small class="pull-right">60%</small>
													  		</h3>
													  		<div class="progress xs">
																<div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
														  			<span class="sr-only">60% Complete</span>
																</div>
													  		</div>
														</a>
												  	</li><!-- end task item -->
												  	<li><!-- Task item -->
														<a href="#">
													  		<h3>
																Make beautiful transitions
																<small class="pull-right">80%</small>
													  		</h3>
													  		<div class="progress xs">
																<div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
														  			<span class="sr-only">80% Complete</span>
																</div>
													  		</div>
														</a>
												  	</li><!-- end task item -->
												</ul>
											</li>
											<li class="footer">
												<a href="#">View all tasks</a>
											</li>
										</ul>
									</li>
					  				<!-- User Account: style can be found in dropdown.less -->
					  				<?php
						  				echo '<li class="dropdown user user-menu">';
						  	
										  	if (Session::isAuthenticated()) {
												$user = Session::getCurrentUser();
											
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
											 			if ($user->hasValidAvatar()) {
															$avatarFile = $user->getAvatar()->getThumbnail();
														} else {
															$avatarFile = $user->getDefaultAvatar();
														}

														echo '<img src="' . $avatarFile . '" class="img-circle" alt="User Image" />';
														echo '<p>';
												  			echo $user->getFullName();
												  			echo '<small>' . $user->getRole() . '</small>';
												  			echo '<small>Registret den ' . date('d', $user->getRegisteredDate()) . ' ' . DateUtils::getMonthFromInt(date('m', $user->getRegisteredDate())) . ' ' . date('Y', $user->getRegisteredDate()) . '</small>';
														echo '</p>';
											  		echo '</li>';
											  		/*
											  		<!-- Menu Body -->
											  		<li class="user-body">
														<div class="col-xs-4 text-center">
														  	<a href="#">Followers</a>
														</div>
														<div class="col-xs-4 text-center">
														  	<a href="#">Sales</a>
														</div>
														<div class="col-xs-4 text-center">
														  	<a href="#">Friends</a>
														</div>
													</li>
													*/
												  	// <!-- Menu Footer -->
												  	echo '<li class="user-footer">';
														echo '<div class="pull-left">';
													  		echo '<a href="?page=my-profile" class="btn btn-default btn-flat">Profile</a>';
														echo '</div>';
														echo '<div class="pull-right">';
													  		echo '<a href="#" onClick="logout()" class="btn btn-default btn-flat">Sign out</a>';
														echo '</div>';
												  	echo '</li>';
												echo '</ul>';
										  	}
										echo '</li>';
									?>
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
					  				<img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image" />
								</div>
								<div class="pull-left info">
					  				<p>Alexander Pierce</p>

					  				<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
								</div>
				  			</div>
				  			<!-- search form -->
				  			<form action="#" method="get" class="sidebar-form">
				   				<div class="input-group">
					  				<input type="text" name="q" class="form-control" placeholder="Search..."/>
					  				<span class="input-group-btn">
										<button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
					  				</span>
								</div>
				  			</form>
							<!-- /.search form -->
							<!-- sidebar menu: : style can be found in sidebar.less -->
				  			<ul class="sidebar-menu">
				   				<li class="header">MAIN NAVIGATION</li>
								<li class="active treeview">
				  					<a href="#">
										<i class="fa fa-dashboard"></i> <span>Dashboard</span> <i class="fa fa-angle-left pull-right"></i>
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
										<span class="label label-primary pull-right">4</span>
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
										<i class="fa fa-th"></i> <span>Widgets</span> <small class="label pull-right bg-green">new</small>
									</a>
								</li>
								<li class="treeview">
									<a href="#">
										<i class="fa fa-pie-chart"></i>
										<span>Charts</span>
										<i class="fa fa-angle-left pull-right"></i>
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
										<i class="fa fa-angle-left pull-right"></i>
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
										<i class="fa fa-angle-left pull-right"></i>
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
								   		<i class="fa fa-angle-left pull-right"></i>
								  	</a>
								  	<ul class="treeview-menu">
										<li><a href="pages/tables/simple.html"><i class="fa fa-circle-o"></i> Simple tables</a></li>
										<li><a href="pages/tables/data.html"><i class="fa fa-circle-o"></i> Data tables</a></li>
								  	</ul>
								</li>
								<li>
								  	<a href="pages/calendar.html">
										<i class="fa fa-calendar"></i> <span>Calendar</span>
										<small class="label pull-right bg-red">3</small>
								  	</a>
								</li>
								<li>
								  	<a href="pages/mailbox/mailbox.html">
										<i class="fa fa-envelope"></i> <span>Mailbox</span>
										<small class="label pull-right bg-yellow">12</small>
								  	</a>
								</li>
								<li class="treeview">
								  	<a href="#">
										<i class="fa fa-folder"></i> <span>Examples</span>
										<i class="fa fa-angle-left pull-right"></i>
								  	</a>
								  	<ul class="treeview-menu">
										<li><a href="pages/examples/invoice.html"><i class="fa fa-circle-o"></i> Invoice</a></li>
										<li><a href="pages/examples/login.html"><i class="fa fa-circle-o"></i> Login</a></li>
										<li><a href="pages/examples/register.html"><i class="fa fa-circle-o"></i> Register</a></li>
										<li><a href="pages/examples/lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen</a></li>
										<li><a href="pages/examples/404.html"><i class="fa fa-circle-o"></i> 404 Error</a></li>
										<li><a href="pages/examples/500.html"><i class="fa fa-circle-o"></i> 500 Error</a></li>
										<li><a href="pages/examples/blank.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>
								  	</ul>
								</li>
								<li class="treeview">
								  	<a href="#">
										<i class="fa fa-share"></i> <span>Multilevel</span>
										<i class="fa fa-angle-left pull-right"></i>
								  	</a>
								  	<ul class="treeview-menu">
										<li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
										<li>
									  		<a href="#"><i class="fa fa-circle-o"></i> Level One <i class="fa fa-angle-left pull-right"></i></a>
									  		<ul class="treeview-menu">
												<li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
												<li>
													<a href="#"><i class="fa fa-circle-o"></i> Level Two <i class="fa fa-angle-left pull-right"></i></a>
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
			   					<li><a href="documentation/index.html"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
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
						  	<!-- Small boxes (Stat box) -->
						  	<div class="row">
								<div class="col-lg-3 col-xs-6">
							  		<!-- small box -->
							  		<div class="small-box bg-aqua">
										<div class="inner">
									  		<h3>150</h3>
									 		<p>New Orders</p>
										</div>
										<div class="icon">
									  		<i class="ion ion-bag"></i>
										</div>
										<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
								  	</div>
								</div><!-- ./col -->
								<div class="col-lg-3 col-xs-6">
								  	<!-- small box -->
								  	<div class="small-box bg-green">
										<div class="inner">
									 		<h3>53<sup style="font-size: 20px">%</sup></h3>
									  		<p>Bounce Rate</p>
										</div>
										<div class="icon">
									  		<i class="ion ion-stats-bars"></i>
										</div>
										<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
								  	</div>
								</div><!-- ./col -->
								<div class="col-lg-3 col-xs-6">
								  	<!-- small box -->
								  	<div class="small-box bg-yellow">
										<div class="inner">
										  	<h3>44</h3>
										  	<p>User Registrations</p>
										</div>
										<div class="icon">
										  	<i class="ion ion-person-add"></i>
										</div>
										<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div><!-- ./col -->
								<div class="col-lg-3 col-xs-6">
									<!-- small box -->
									<div class="small-box bg-red">
										<div class="inner">
											<h3>65</h3>
											<p>Unique Visitors</p>
										</div>
										<div class="icon">
											<i class="ion ion-pie-graph"></i>
										</div>
										<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div><!-- ./col -->
							</div><!-- /.row -->
						 	 <!-- Main row -->
						  	<div class="row">
								<!-- Left col -->
								<section class="col-lg-7 connectedSortable">
							  		<!-- Custom tabs (Charts with tabs)-->
							  		<div class="nav-tabs-custom">
										<!-- Tabs within a box -->
										<ul class="nav nav-tabs pull-right">
								  			<li class="active"><a href="#revenue-chart" data-toggle="tab">Area</a></li>
								  			<li><a href="#sales-chart" data-toggle="tab">Donut</a></li>
								  			<li class="pull-left header"><i class="fa fa-inbox"></i> Sales</li>
										</ul>
										<div class="tab-content no-padding">
								  			<!-- Morris chart - Sales -->
								  			<div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;"></div>
								  			<div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;"></div>
										</div>
							  		</div><!-- /.nav-tabs-custom -->

							  		<!-- Chat box -->
							  		<div class="box box-success">
										<div class="box-header">
								  			<i class="fa fa-comments-o"></i>
								  			<h3 class="box-title">Chat</h3>
								  			<div class="box-tools pull-right" data-toggle="tooltip" title="Status">
												<div class="btn-group" data-toggle="btn-toggle" >
												  	<button type="button" class="btn btn-default btn-sm active"><i class="fa fa-square text-green"></i></button>
												  	<button type="button" class="btn btn-default btn-sm"><i class="fa fa-square text-red"></i></button>
												</div>
								  			</div>
										</div>
										<div class="box-body chat" id="chat-box">
									  		<!-- chat item -->
									  		<div class="item">
												<img src="dist/img/user4-128x128.jpg" alt="user image" class="online"/>
												<p class="message">
											  		<a href="#" class="name">
														<small class="text-muted pull-right"><i class="fa fa-clock-o"></i> 2:15</small>
														Mike Doe
											  		</a>
											  		I would like to meet you to discuss the latest news about
											  		the arrival of the new theme. They say it is going to be one the
										  			best themes on the market
												</p>
												<div class="attachment">
										  			<h4>Attachments:</h4>
										  			<p class="filename">
														Theme-thumbnail-image.jpg
										  			</p>
										  			<div class="pull-right">
														<button class="btn btn-primary btn-sm btn-flat">Open</button>
										  			</div>
												</div><!-- /.attachment -->
									  		</div><!-- /.item -->
								  			<!-- chat item -->
										  	<div class="item">
												<img src="dist/img/user3-128x128.jpg" alt="user image" class="offline"/>
												<p class="message">
												 	<a href="#" class="name">
														<small class="text-muted pull-right"><i class="fa fa-clock-o"></i> 5:15</small>
														Alexander Pierce
												  	</a>
												  	I would like to meet you to discuss the latest news about
												  	the arrival of the new theme. They say it is going to be one the
												  	best themes on the market
												</p>
										  	</div><!-- /.item -->
								  			<!-- chat item -->
										  	<div class="item">
												<img src="dist/img/user2-160x160.jpg" alt="user image" class="offline"/>
												<p class="message">
											  		<a href="#" class="name">
														<small class="text-muted pull-right"><i class="fa fa-clock-o"></i> 5:30</small>
														Susan Doe
											  		</a>
													I would like to meet you to discuss the latest news about
													the arrival of the new theme. They say it is going to be one the
													best themes on the market
												</p>
										  	</div><!-- /.item -->
										</div><!-- /.chat -->
										<div class="box-footer">
								 			<div class="input-group">
												<input class="form-control" placeholder="Type message..."/>
												<div class="input-group-btn">
									 				<button class="btn btn-success"><i class="fa fa-plus"></i></button>
												</div>
								  			</div>
										</div>
							  		</div><!-- /.box (chat box) -->

								  	<!-- TO DO List -->
								  	<div class="box box-primary">
										<div class="box-header">
									  		<i class="ion ion-clipboard"></i>
									  		<h3 class="box-title">To Do List</h3>
									  		<div class="box-tools pull-right">
												<ul class="pagination pagination-sm inline">
										  			<li><a href="#">&laquo;</a></li>
										  			<li><a href="#">1</a></li>
										  			<li><a href="#">2</a></li>
										  			<li><a href="#">3</a></li>
										  			<li><a href="#">&raquo;</a></li>
												</ul>
									  		</div>
										</div><!-- /.box-header -->
										<div class="box-body">
								  			<ul class="todo-list">
												<li>
									  				<!-- drag handle -->
									  				<span class="handle">
														<i class="fa fa-ellipsis-v"></i>
														<i class="fa fa-ellipsis-v"></i>
									  				</span>
									  				<!-- checkbox -->
									  				<input type="checkbox" value="" name=""/>
												  	<!-- todo text -->
												  	<span class="text">Design a nice theme</span>
									  				<!-- Emphasis label -->
									  				<small class="label label-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
									  				<!-- General tools such as edit or delete-->
									  				<div class="tools">
														<i class="fa fa-edit"></i>
														<i class="fa fa-trash-o"></i>
									  				</div>
												</li>
												<li>
									  				<span class="handle">
														<i class="fa fa-ellipsis-v"></i>
														<i class="fa fa-ellipsis-v"></i>
									  				</span>
									  				<input type="checkbox" value="" name=""/>
									  				<span class="text">Make the theme responsive</span>
									  				<small class="label label-info"><i class="fa fa-clock-o"></i> 4 hours</small>
									  				<div class="tools">
														<i class="fa fa-edit"></i>
														<i class="fa fa-trash-o"></i>
									  				</div>
												</li>
												<li>
											  		<span class="handle">
														<i class="fa fa-ellipsis-v"></i>
														<i class="fa fa-ellipsis-v"></i>
											  		</span>
											  		<input type="checkbox" value="" name=""/>
											  		<span class="text">Let theme shine like a star</span>
											  		<small class="label label-warning"><i class="fa fa-clock-o"></i> 1 day</small>
											  		<div class="tools">
														<i class="fa fa-edit"></i>
														<i class="fa fa-trash-o"></i>
											  		</div>
												</li>
												<li>
											  		<span class="handle">
														<i class="fa fa-ellipsis-v"></i>
														<i class="fa fa-ellipsis-v"></i>
											 		</span>
											  		<input type="checkbox" value="" name=""/>
											  		<span class="text">Let theme shine like a star</span>
											  		<small class="label label-success"><i class="fa fa-clock-o"></i> 3 days</small>
											  		<div class="tools">
														<i class="fa fa-edit"></i>
														<i class="fa fa-trash-o"></i>
											  		</div>
												</li>
												<li>
											  		<span class="handle">
														<i class="fa fa-ellipsis-v"></i>
														<i class="fa fa-ellipsis-v"></i>
											  		</span>
											  		<input type="checkbox" value="" name=""/>
											  		<span class="text">Check your messages and notifications</span>
											  		<small class="label label-primary"><i class="fa fa-clock-o"></i> 1 week</small>
											 		<div class="tools">
														<i class="fa fa-edit"></i>
														<i class="fa fa-trash-o"></i>
											  		</div>
												</li>
												<li>
												  	<span class="handle">
														<i class="fa fa-ellipsis-v"></i>
														<i class="fa fa-ellipsis-v"></i>
												  	</span>
												  	<input type="checkbox" value="" name=""/>
												  	<span class="text">Let theme shine like a star</span>
												  	<small class="label label-default"><i class="fa fa-clock-o"></i> 1 month</small>
												  	<div class="tools">
														<i class="fa fa-edit"></i>
														<i class="fa fa-trash-o"></i>
												  	</div>
												</li>
											</ul>
										</div><!-- /.box-body -->
										<div class="box-footer clearfix no-border">
								  			<button class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add item</button>
										</div>
							  		</div><!-- /.box -->

							  		<!-- quick email widget -->
							  		<div class="box box-info">
										<div class="box-header">
											<i class="fa fa-envelope"></i>
										  	<h3 class="box-title">Quick Email</h3>
								 			 <!-- tools box -->
								  			<div class="pull-right box-tools">
												<button class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
								  			</div><!-- /. tools -->
										</div>
										<div class="box-body">
								  			<form action="#" method="post">
												<div class="form-group">
									  				<input type="email" class="form-control" name="emailto" placeholder="Email to:"/>
												</div>
												<div class="form-group">
									  				<input type="text" class="form-control" name="subject" placeholder="Subject"/>
												</div>
												<div>
									  				<textarea class="textarea" placeholder="Message" style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
												</div>
								  			</form>
										</div>
										<div class="box-footer clearfix">
								  			<button class="pull-right btn btn-default" id="sendEmail">Send <i class="fa fa-arrow-circle-right"></i></button>
										</div>
							  		</div>
								</section><!-- /.Left col -->

								<!-- right col (We are only adding the ID to make the widgets sortable)-->
								<section class="col-lg-5 connectedSortable">
								  	<!-- Map box -->
								  	<div class="box box-solid bg-light-blue-gradient">
										<div class="box-header">
									  		<!-- tools box -->
									  		<div class="pull-right box-tools">
												<button class="btn btn-primary btn-sm daterange pull-right" data-toggle="tooltip" title="Date range"><i class="fa fa-calendar"></i></button>
												<button class="btn btn-primary btn-sm pull-right" data-widget='collapse' data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>
									  		</div><!-- /. tools -->

									 		<i class="fa fa-map-marker"></i>
									  		<h3 class="box-title">
												Visitors
									  		</h3>
										</div>
										<div class="box-body">
									  		<div id="world-map" style="height: 250px; width: 100%;"></div>
										</div><!-- /.box-body-->
										<div class="box-footer no-border">
									  		<div class="row">
												<div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
										  			<div id="sparkline-1"></div>
										  			<div class="knob-label">Visitors</div>
												</div><!-- ./col -->
												<div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
										  			<div id="sparkline-2"></div>
										  			<div class="knob-label">Online</div>
												</div><!-- ./col -->
												<div class="col-xs-4 text-center">
										  			<div id="sparkline-3"></div>
										  			<div class="knob-label">Exists</div>
												</div><!-- ./col -->
									  		</div><!-- /.row -->
										</div>
								  	</div>
								  	<!-- /.box -->

								  	<!-- solid sales graph -->
								  	<div class="box box-solid bg-teal-gradient">
										<div class="box-header">
											<i class="fa fa-th"></i>
									  		<h3 class="box-title">Sales Graph</h3>
									  		<div class="box-tools pull-right">
												<button class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
												<button class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
									  		</div>
										</div>
										<div class="box-body border-radius-none">
									  		<div class="chart" id="line-chart" style="height: 250px;"></div>
										</div><!-- /.box-body -->
										<div class="box-footer no-border">
									  		<div class="row">
												<div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
										  			<input type="text" class="knob" data-readonly="true" value="20" data-width="60" data-height="60" data-fgColor="#39CCCC"/>
										  			<div class="knob-label">Mail-Orders</div>
												</div><!-- ./col -->
												<div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
										  			<input type="text" class="knob" data-readonly="true" value="50" data-width="60" data-height="60" data-fgColor="#39CCCC"/>
										  			<div class="knob-label">Online</div>
												</div><!-- ./col -->
												<div class="col-xs-4 text-center">
										  			<input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60" data-fgColor="#39CCCC"/>
										  			<div class="knob-label">In-Store</div>
												</div><!-- ./col -->
									  		</div><!-- /.row -->
										</div><!-- /.box-footer -->
								  	</div><!-- /.box -->

								  	<!-- Calendar -->
								  	<div class="box box-solid bg-green-gradient">
										<div class="box-header">
									  		<i class="fa fa-calendar"></i>
									  		<h3 class="box-title">Calendar</h3>
									  		<!-- tools box -->
									  		<div class="pull-right box-tools">
												<!-- button with a dropdown -->
												<div class="btn-group">
										  			<button class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i></button>
										  			<ul class="dropdown-menu pull-right" role="menu">
														<li><a href="#">Add new event</a></li>
														<li><a href="#">Clear events</a></li>
														<li class="divider"></li>
														<li><a href="#">View calendar</a></li>
										  			</ul>
												</div>
												<button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
												<button class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
									  		</div><!-- /. tools -->
										</div><!-- /.box-header -->
										<div class="box-body no-padding">
									  		<!--The calendar -->
									  		<div id="calendar" style="width: 100%"></div>
										</div><!-- /.box-body -->
										<div class="box-footer text-black">
									  		<div class="row">
												<div class="col-sm-6">
												  	<!-- Progress bars -->
												  	<div class="clearfix">
														<span class="pull-left">Task #1</span>
														<small class="pull-right">90%</small>
												 	</div>
												  	<div class="progress xs">
														<div class="progress-bar progress-bar-green" style="width: 90%;"></div>
												  	</div>
												  	<div class="clearfix">
														<span class="pull-left">Task #2</span>
														<small class="pull-right">70%</small>
													</div>
												  	<div class="progress xs">
														<div class="progress-bar progress-bar-green" style="width: 70%;"></div>
												  	</div>
												</div><!-- /.col -->
												<div class="col-sm-6">
												  	<div class="clearfix">
														<span class="pull-left">Task #3</span>
														<small class="pull-right">60%</small>
												  	</div>
												  	<div class="progress xs">
														<div class="progress-bar progress-bar-green" style="width: 60%;"></div>
												  	</div>

												  	<div class="clearfix">
														<span class="pull-left">Task #4</span>
														<small class="pull-right">40%</small>
												  	</div>
												  	<div class="progress xs">
														<div class="progress-bar progress-bar-green" style="width: 40%;"></div>
												  	</div>
												</div><!-- /.col -->
									  		</div><!-- /.row -->
										</div>
								  	</div><!-- /.box -->
								</section><!-- right col -->
							</div><!-- /.row (main row) -->
						</section><!-- /.content -->
				 	</div><!-- /.content-wrapper -->
				  	<footer class="main-footer">
						<div class="pull-right hidden-xs">
					  		<b>Version</b> 2.0
						</div>
						<strong>Copyright &copy; 2014-2015 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights reserved.
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
								<ul class='control-sidebar-menu'>
								  	<li>
										<a href='javascript::;'>
										  	<i class="menu-icon fa fa-birthday-cake bg-red"></i>
										  	<div class="menu-info">
												<h4 class="control-sidebar-subheading">Langdon's Birthday</h4>
												<p>Will be 23 on April 24th</p>
										  	</div>
										</a>
								  	</li>
								  	<li>
										<a href='javascript::;'>
										  	<i class="menu-icon fa fa-user bg-yellow"></i>
										  	<div class="menu-info">
												<h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>
												<p>New phone +1(800)555-1234</p>
										  	</div>
										</a>
								  	</li>
								  	<li>
										<a href='javascript::;'>
									  		<i class="menu-icon fa fa-envelope-o bg-light-blue"></i>
									  		<div class="menu-info">
												<h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>
												<p>nora@example.com</p>
									 		 </div>
										</a>
								  	</li>
								  	<li>
										<a href='javascript::;'>
									  		<i class="menu-icon fa fa-file-code-o bg-green"></i>
									  		<div class="menu-info">
												<h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>
												<p>Execution time 5 seconds</p>
									  		</div>
										</a>
								  	</li>
								</ul><!-- /.control-sidebar-menu -->

								<h3 class="control-sidebar-heading">Tasks Progress</h3> 
								<ul class='control-sidebar-menu'>
								  	<li>
										<a href='javascript::;'>			   
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
										<a href='javascript::;'>			   
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
										<a href='javascript::;'>			   
								  			<h4 class="control-sidebar-subheading">
												Laravel Integration
												<span class="label label-waring pull-right">50%</span>
								 			</h4>
								  			<div class="progress progress-xxs">
												<div class="progress-bar progress-bar-warning" style="width: 50%"></div>
								  			</div>									
										</a>
						 			 </li> 
						  			<li>
										<a href='javascript::;'>			   
											<h4 class="control-sidebar-subheading">
												Back End Framework
												<span class="label label-primary pull-right">68%</span>
											</h4>
											<div class="progress progress-xxs">
												<div class="progress-bar progress-bar-primary" style="width: 68%"></div>
											</div>									
										</a>
								  	</li>			   
								</ul><!-- /.control-sidebar-menu -->		 

					  		</div><!-- /.tab-pane -->
					  		<!-- Stats tab content -->
					 		<div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div><!-- /.tab-pane --> 
							<!-- Settings tab content -->
						  	<div class="tab-pane" id="control-sidebar-settings-tab">			
								<form method="post">
								  	<h3 class="control-sidebar-heading">General Settings</h3>
								  	<div class="form-group">
										<label class="control-sidebar-subheading">
									  		Report panel usage
									  		<input type="checkbox" class="pull-right" checked />
										</label>
										<p>
									  		Some information about this general settings option
										</p>
								  	</div><!-- /.form-group -->

								  	<div class="form-group">
										<label class="control-sidebar-subheading">
									  		Allow mail redirect
									  		<input type="checkbox" class="pull-right" checked />
										</label>
										<p>
									  		Other sets of options are available
										</p>
								  	</div><!-- /.form-group -->

							  		<div class="form-group">
										<label class="control-sidebar-subheading">
										  	Expose author name in posts
										  	<input type="checkbox" class="pull-right" checked />
										</label>
										<p>
										  	Allow the user to show his name in blog posts
										</p>
							  		</div><!-- /.form-group -->

							  		<h3 class="control-sidebar-heading">Chat Settings</h3>

								  	<div class="form-group">
										<label class="control-sidebar-subheading">
										  	Show me as online
										  	<input type="checkbox" class="pull-right" checked />
										</label>				
								  	</div><!-- /.form-group -->

								  	<div class="form-group">
										<label class="control-sidebar-subheading">
										  	Turn off notifications
										  	<input type="checkbox" class="pull-right" />
										</label>				
								  	</div><!-- /.form-group -->

								  	<div class="form-group">
										<label class="control-sidebar-subheading">
									  		Delete chat history
								  			<a href="javascript::;" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
										</label>				
							  		</div><!-- /.form-group -->
								</form>
							</div><!-- /.tab-pane -->
						</div>
					</aside><!-- /.control-sidebar -->
				  	<!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
				  	<div class='control-sidebar-bg'></div>
				</div><!-- ./wrapper -->

				<!-- jQuery 2.1.4 -->
				<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
				<!-- jQuery UI 1.11.2 -->
				<script src="http://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script>
				<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
				<script>
					$.widget.bridge('uibutton', $.ui.button);
				</script>
				<!-- Bootstrap 3.3.2 JS -->
				<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>	
				<!-- Morris.js charts -->
				<script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
				<script src="plugins/morris/morris.min.js" type="text/javascript"></script>
				<!-- Sparkline -->
				<script src="plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
				<!-- jvectormap -->
				<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
				<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
				<!-- jQuery Knob Chart -->
				<script src="plugins/knob/jquery.knob.js" type="text/javascript"></script>
				<!-- daterangepicker -->
				<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js" type="text/javascript"></script>
				<script src="plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
				<!-- datepicker -->
				<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
				<!-- Bootstrap WYSIHTML5 -->
				<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
				<!-- Slimscroll -->
				<script src="plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
				<!-- FastClick -->
				<script src='plugins/fastclick/fastclick.min.js'></script>
				<!-- AdminLTE App -->
				<script src="dist/js/app.min.js" type="text/javascript"></script>	
				
				<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
				<script src="dist/js/pages/dashboard.js" type="text/javascript"></script>	
				
				<!-- AdminLTE for demo purposes -->
				<script src="dist/js/demo.js" type="text/javascript"></script>
			</body>
		</html>
		<?php

		/*
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
										   $this->pageName == 'event-compos' ||
										   $this->pageName == 'event-memberlist') {
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('event.checkin')) {
										echo '<li><a' . ($this->pageName == 'event-checkin' ? ' class="active"' : null) . ' href="index.php?page=event-checkin">Innsjekk</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('event.seatmap')) {
										echo '<li><a' . ($this->pageName == 'event-seatmap' ? ' class="active"' : null) . ' href="index.php?page=event-seatmap">Seatmap</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('event.screen')) {
										echo '<li><a' . ($this->pageName == 'event-screen' ? ' class="active"' : null) . ' href="index.php?page=event-screen">Skjerm</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('event.agenda')) {
										echo '<li><a' . ($this->pageName == 'event-agenda' ? ' class="active"' : null) . ' href="index.php?page=event-agenda">Agenda</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('event.compos')) {
										echo '<li><a' . ($this->pageName == 'event-compos' ? ' class="active"' : null) . ' href="index.php?page=event-compos">Compo</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('event.memberlist')) {
										echo '<li><a' . ($this->pageName == 'event-memberlist' ? ' class="active"' : null) . ' href="index.php?page=event-memberlist">Medlemsliste</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('event.table-labels')) {
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
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('chief.groups')) {
										echo '<li><a' . ($this->pageName == 'chief-groups' ? ' class="active"' : null) . ' href="index.php?page=chief-groups">Crew</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('chief.teams')) {
										echo '<li><a' . ($this->pageName == 'chief-teams' ? ' class="active"' : null) . ' href="index.php?page=chief-teams">Lag</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('chief.avatars')) {
										echo '<li><a' . ($this->pageName == 'chief-avatars' ? ' class="active"' : null) . ' href="index.php?page=chief-avatars">Profilbilder</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('chief.applications')) {
										echo '<li><a' . ($this->pageName == 'chief-applications' || $this->pageName == 'application' ? ' class="active"' : null) . ' href="index.php?page=chief-applications">Søknader</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('chief.my-crew')) {
										echo '<li><a' . ($this->pageName == 'chief-my-crew' || $this->pageName == 'edit-restricted-page' ? ' class="active"' : null) . ' href="index.php?page=chief-my-crew">My Crew</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('chief.email')) {
										echo '<li><a' . ($this->pageName == 'chief-email' ? ' class="active"' : null) . ' href="index.php?page=chief-email">Send e-post</a></li>';
									}
								} else if ($this->pageName == 'admin' || 
									$this->pageName == 'admin-events' || 
									$this->pageName == 'admin-permissions' || 
									$this->pageName == 'admin-seatmap' ||
									$this->pageName == 'admin-website') {
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('admin.events')) {
										echo '<li><a' . ($this->pageName == 'admin-events' ? ' class="active"' : null) . ' href="index.php?page=admin-events">Arrangementer</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('admin.permissions')) {
										echo '<li><a' . ($this->pageName == 'admin-permissions' ? ' class="active"' : null) . ' href="index.php?page=admin-permissions">Tilganger</a></li>';
									}

									if ($user->hasPermission('*') ||
										$user->hasPermission('admin.seatmap')) {
										echo '<li><a' . ($this->pageName == 'admin-seatmap' ? ' class="active"' : null) . ' href="index.php?page=admin-seatmap">Endre seatmap</a></li>';
									}
									
									if ($user->hasPermission('*') ||
										$user->hasPermission('admin.website')) {
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
					echo '</article>';
					echo '<nav class="menu">';
						echo '<ul>';
							if (Session::isAuthenticated()) {
								$user = Session::getCurrentUser();
							
								if ($user->hasPermission('*') ||
									$user->hasPermission('search.users')) {
									
									echo '<li' . ($this->pageName == 'search-users' ? ' class="active"' : null) . '><a href="index.php?page=search-users"><img src="images/search.png"></a></li>';
								}
								
								if ($user->isGroupMember()) {
									echo '<li' . ($this->pageName == 'my-crew' || in_array(strtolower($this->pageName), $pageNameList) ? ' class="active"' : null) . '><a href="index.php?page=my-crew"><img src="images/my-crew.png"></a></li>';
								} else {
									echo '<li' . ($this->pageName == 'apply' ? ' class="active"' : null) . '><a href="index.php?page=apply"><img src="images/apply.png"></a></li>';
								}
								
								echo '<li' . ($this->pageName == 'all-crew' ? ' class="active"' : null) . '><a href="index.php?page=all-crew"><img src="images/all-crew.png"></a></li>';
								
								if ($user->hasPermission('*') ||
									$user->hasPermission('event')) {
									if ($this->pageName == 'event' || 
										$this->pageName == 'event-checkin' || 
										$this->pageName == 'event-seatmap' ||
										$this->pageName == 'event-screen' ||
										$this->pageName == 'event-agenda' ||
										$this->pageName == 'event-compos' ||
										$this->pageName == 'event-memberlist') {
										echo '<li class="active"><a href="index.php?page=event"><img src="images/event.png"></a></li>';
									} else {
										echo '<li><a href="index.php?page=event"><img src="images/event.png"></a></li>';
									}
								}
								
								if ($user->hasPermission('*') ||
									$user->hasPermission('chief')) {
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
								
								if ($user->hasPermission('*') ||
									$user->hasPermission('admin')) {
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
								
								if ($user->hasPermission('*') ||
									$user->hasPermission('developer')) {
									if ($this->pageName == 'developer' || 
										$this->pageName == 'developer-change-user') {
										echo '<li class="active"><a href="index.php?page=developer"><img src="images/developer.png"></a></li>';
									} else {
										echo '<li><a href="index.php?page=developer"><img src="images/developer.png"></a></li>';
									}
								}

								echo '<li' . ($this->pageName == 'my-profile' ? ' class="active"' : null) . '><a href="index.php?page=my-profile"><img src="images/my-profile.png"></a></li>';
							}
						echo '</ul>';
					echo '</nav>';
				echo '</section>';
			echo '</body>';
		echo '</html>';
		*/
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