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
		$this->pageName = $_GET['page'] ?? 'my-crew';
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

                    // Select2
                    echo '<link rel="stylesheet" href="bower_components/select2/dist/css/select2.min.css">';
                    /* AdminLTE end */

                    /* AdminLTE - Login begin */
                    // iCheck
                    echo '<link rel="stylesheet" href="plugins/iCheck/square/blue.css">';
                    /* AdminLTE - Login end */

                    // jQuery 3
                    echo '<script src="bower_components/jquery/dist/jquery.min.js"></script>';

                    // Bootstrap 3.3.7
                    echo '<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>';

                    // iCheck
                    echo '<script src="plugins/iCheck/icheck.min.js"></script>';

                    if (Session::isAuthenticated()) {
                        echo '<script src="../api/scripts/logout.js"></script>';
                    }

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
                    $user = Session::getCurrentUser();

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
EOD;

                                            echo $this->getNotifications($user);

                                            /*
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
                                            */

    echo <<< EOD
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

EOD;

                                            // User Account: style can be found in dropdown.less
                                            echo '<li class="dropdown user user-menu">';

                                                if ($user->hasValidAvatar()) {
                                                    $avatarFile = $user->getAvatar()->getThumbnail();
                                                } else {
                                                    $avatarFile = $user->getDefaultAvatar();
                                                }

                                                echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
                                                    echo '<img src="' . $avatarFile . '" class="user-image" alt="' . $user->getFullName() . '\'s profilbilde">';
                                                    echo '<span class="hidden-xs">' . $user->getFullName() . '</span>';
                                                echo '</a>';

                                                echo '<ul class="dropdown-menu">';
                                                    // User image
                                                    echo '<li class="user-header">';
                                                        echo '<img src="' . $avatarFile . '" class="img-circle" alt="' . $user->getFullName() . '\'s profilbilde">';
                                                        echo '<p>';
                                                            echo $user->getFullName();
                                                            echo '<small>' . $user->getRole() . '</small>';
                                                            echo '<small>Registret den ' . date('d', $user->getRegisteredDate()) . ' ' . DateUtils::getMonthFromInt(date('m', $user->getRegisteredDate())) . ' ' . date('Y', $user->getRegisteredDate()) . '</small>';
                                                        echo '</p>';
                                                    echo '</li>';

                                                    /*
                                                    // Menu Body
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
                                                    </li>
                                                    */

                                                    // Menu Footer
                                                    echo '<li class="user-footer">';
                                                        echo '<div class="pull-left">';
                                                            echo '<a href="?page=user-profile" class="btn btn-default btn-flat">Min profil</a>';
                                                        echo '</div>';
                                                        echo '<div class="pull-right">';
                                                            echo '<button class="btn btn-default btn-flat" onClick="logout()">Logg ut</button>';
                                                        echo '</div>';
                                                    echo '</li>';
                                                echo '</ul>';
                                            echo '</li>';

                                            // Control Sidebar Toggle Button
                                            echo '<li>';
                                                echo '<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>';
                                            echo '</li>';
                                        echo '</ul>';
                                    echo '</div>';
                                echo '</nav>';
                            echo '</header>';

    echo <<< EOD

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
EOD;

                                    echo $this->getMenu($user);

                                echo '</section>';
                            echo '</aside>';

                            // Content Wrapper. Contains page content
                            echo '<div class="content-wrapper">';

                                if ($user->hasPermission('*') ||
                                    $user->isGroupMember()) {
                                    // View the page specified by "pageName" variable.
                                    echo $this->getPage($this->pageName);
                                } else {
                                    $publicPages = ['apply',
                                                    'all-crew',
                                                    'my-profile',
                                                    'edit-profile',
                                                    'edit-password',
                                                    'edit-avatar'];

                                    if (in_array($this->pageName, $publicPages)) {
                                        echo $this->getPage($this->pageName);
                                    } else {
                                        echo $this->getPage('all-crew');
                                    }
                                }

                            echo '</div>';
                            echo '<footer class="main-footer">';
                                echo '<div class="pull-right hidden-xs">';
                                    echo '<b>Version</b> 2.4.0';
                                echo '</div>';
                                echo '<strong>Copyright &copy; 2017' . (date('Y') > 2017 ? '-' . date('Y') : null) . ' <a href="//' . Settings::domain . '/">' . Settings::name . '</a>.</strong> All rights reserved.';
                            echo '</footer>';

    echo <<< EOD

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
                                        </form>
                                    </div>
                                </div>
                            </aside>
                            <!-- Add the sidebar's background. This div must be placed
                                     immediately after the control sidebar -->
                            <div class="control-sidebar-bg"></div>
                            </div>

EOD;
                } else {
                    $publicPages = ['login',
                    				'register',
                        			'activation',
                                    'reset-password'];

                    // Show page if whitelisted.
                    if (in_array($this->pageName, $publicPages)) {
                        echo $this->getPage($this->pageName);
                    } else {
                        echo $this->getPage('login');
                    }
                }

                // jQuery UI 1.11.4
                echo '<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>';

                // Resolve conflict in jQuery UI tooltip with Bootstrap tooltip
                echo '<script>';
                    echo '$.widget.bridge(\'uibutton\', $.ui.button);';
                echo '</script>';

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

                // Select2
                echo '<script src="bower_components/select2/dist/js/select2.full.min.js"></script>';

                // ChartJS
                echo '<script src="bower_components/chart.js/Chart.js"></script>';

				// AdminLTE App
                echo '<script src="dist/js/adminlte.min.js"></script>';
			echo '</body>';
		echo '</html>';
	}

	// Generates title based on current page / article.
	private function getTitle(): string {
		return Settings::name . ' Crew';
	}

	private function getMessages() {

	}

	private function getNotifications(User $user): string {
		$content = null;
		$applications = null;
		$avatars = null;

		// Check for group applications.
		if ($user->hasPermission('*')) {
			$applications = ApplicationHandler::getPendingApplications();
		} else if ($user->isGroupMember() && $user->hasPermission('chief.application')) {
			$applications = ApplicationHandler::getPendingApplicationsByGroup($user->getGroup());
		}

		// Check for avatars.
		if ($user->hasPermission('*') || $user->hasPermission('chief.application') && $user->isGroupMember()) {
			$avatars = AvatarHandler::getPendingAvatars();
		}

		$notificationsCount = count($applications) . count($avatars);

		// Notifications: style can be found in dropdown.less
		$content .= '<li class="dropdown notifications-menu">';
			$content .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
				$content .= '<i class="fa fa-bell-o"></i>';

				if ($notificationsCount > 0) {
					$content .= '<span class="label label-warning">' . $notificationsCount . '</span>';
				}

			$content .= '</a>';
			$content .= '<ul class="dropdown-menu">';

				if ($notificationsCount > 0) {
					$content .= '<li class="header">Du har ' . $notificationsCount . ' varsler.</li>';
					$content .= '<li>';
						// Inner menu: contains the actual data
						$content .= '<ul class="menu">';

							if (!empty($applications)) {
								foreach ($applications as $application) {
									$content .= '<li><a href="?page=application&id=' . $application->getId() . '"><i class="fa fa-paperclip text-aqua"></i>Søknaden til ' . $application->getUser()->getFullName() . ' venter på godkjenning.</a></li>';
								}
							}

							if (!empty($avatars)) {
								foreach ($avatars as $avatar) {
									$content .= '<li><a href="?page=chief-avatars"><i class="fa fa-image-fila text-aqua"></i>Profilbilde til ' . $avatar->getUser()->getFullName() . ' venter på godkjenning.</a></li>';
								}
							}

						$content .= '</ul>';
					$content .= '</li>';
					/*
					echo '<li class="footer"><a href="#">View all</a></li>';
					*/
				} else {
					$content .= '<li class="header">Du har for øyeblikket ingen varsler.</li>';
				}

			$content .= '</ul>';
		$content .= '</li>';

		return $content;
	}

	private function getTasks() {

	}

	private function getMenu(User $user): string {
		$content = null;

		$content .= '<ul class="sidebar-menu" data-widget="tree">';
			$content .= '<li class="header">Hovedmeny</li>';

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
				if (empty($pageList) && empty($teamList)) {
					$content .= '<li><a href="?page=my-crew"><i class="fa fa-user"></i><span>Mitt crew</span></a></li>';
				} else {
					$content .= '<li class="treeview' . ($this->pageName == 'my-crew' ? ' active' : null) . '">';
						$content .= '<a href="?page=my-crew"><i class="fa fa-user"></i><span>Mitt crew</span><i class="fa fa-angle-left pull-right"></i></a>';
						$content .= '<ul class="treeview-menu">';
							$content .= '<li><a href="?page=my-crew"><i class="fa fa-circle-o"></i>' . $group->getTitle() . '</a></li>';

							// Only create link for groups that actually contain teams.
							if (!empty($teamList)) {
								foreach ($teamList as $team) {
									$content .= '<li' . (isset($_GET['teamId']) && $team->getId() == $_GET['teamId'] ? ' class="active"' : null) .'><a href="?page=my-crew&teamId=' . $team->getId() . '"><i class="fa fa-circle-o"></i>' . $team->getTitle() . '</a></li>';
								}
							}

							if (!empty($pageList)) {
								foreach ($pageList as $page) {
									if (strtolower($page->getName()) != strtolower($group->getName())) {
										if (!in_array(strtolower($page->getName()), $teamNameList)) {
											$content .= '<li><a href="?page=' . $page->getName() . '"><i class="fa fa-circle-o"></i>' . $page->getTitle() . '</a></li>';
										}
									}
								}
							}

						$content .= '</ul>';
					$content .= '</li>';
				}
			}

			$groupList = GroupHandler::getGroups();

			if (!empty($groupList)) {
				$content .= '<li class="treeview' . ($this->pageName == 'all-crew' ? ' active' : null) . '">';
					$content .= '<a href="?page=all-crew"><i class="fa fa-users"></i><span>Crew</span><i class="fa fa-angle-left pull-right"></i></a>';
					$content .= '<ul class="treeview-menu">';

						foreach ($groupList as $group) {
							$content .= '<li' . (isset($_GET['id']) && $group->getId() == $_GET['id'] ? ' class="active"' : null) .'><a href="?page=all-crew&id=' . $group->getId() . '"><i class="fa fa-circle-o"></i> ' . $group->getTitle() . '</a></li>';
						}

					$content .= '</ul>';
				$content .= '</li>';
			}

			if ($user->hasPermission('chief')) {
				$content .= '<li class="treeview' . (StringUtils::startsWith($this->pageName, 'chief') ? ' active' : null) . '">';
					$content .= '<a href="?page=chief"><i class="fa fa-gavel"></i><span>Chief</span><i class="fa fa-angle-left pull-right"></i></a>';
					$content .= '<ul class="treeview-menu">';

						if ($user->hasPermission('chief.group')) {
							$content .= '<li' . ($this->pageName == 'chief-group' ? ' class="active"' : null) . '><a href="?page=chief-group"><i class="fa fa-circle-o"></i>Crew</a></li>';
						}

						if ($user->hasPermission('chief.team')) {
							$content .= '<li' . ($this->pageName == 'chief-team' ? ' class="active"' : null) . '><a href="?page=chief-team"><i class="fa fa-circle-o"></i>Lag</a></li>';
						}

						if ($user->hasPermission('chief.avatar')) {
							$content .= '<li' . ($this->pageName == 'chief-avatar' ? ' class="active"' : null) . '><a href="?page=chief-avatar"><i class="fa fa-circle-o"></i>Profilbilder</a></li>';
						}

						if ($user->hasPermission('chief.application')) {
							$content .= '<li' . ($this->pageName == 'chief-application' || $this->pageName == 'application' ? ' class="active"' : null) . '><a href="?page=chief-application"><i class="fa fa-circle-o"></i>Søknader</a></li>';
						}

						if ($user->hasPermission('chief.my-crew')) {
							$content .= '<li' . ($this->pageName == 'chief-my-crew' || $this->pageName == 'edit-restricted-page' ? ' class="active"' : null) . '><a href="?page=chief-my-crew"><i class="fa fa-circle-o"></i>My crew</a></li>';
						}

						if ($user->hasPermission('chief.email')) {
							$content .= '<li' . ($this->pageName == 'chief-email' ? ' class="active"' : null) . '><a href="?page=chief-email"><i class="fa fa-send"></i>Send e-post</a></li>';
						}

					$content .= '</ul>';
				$content .= '</li>';
			}

			if ($user->hasPermission('event')) {
				$content .= '<li class="treeview' . (StringUtils::startsWith($this->pageName, 'event') ? ' active' : null) . '">';
					$content .= '<a href="?page=event"><i class="fa fa-calendar"></i><span>Arrangement</span><i class="fa fa-angle-left pull-right"></i></a>';
					$content .= '<ul class="treeview-menu">';

						if ($user->hasPermission('event.checkin')) {
							$content .= '<li' . ($this->pageName == 'event-checkin' ? ' class="active"' : null) . '><a href="?page=event-checkin"><i class="fa fa-check"></i>Innsjekk</a></li>';
						}

						if ($user->hasPermission('event.checklist')) {
							$content .= '<li' . ($this->pageName == 'event-checklist' || $this->pageName == 'edit-note' ? ' class="active"' : null) . '><a href="index.php?page=event-checklist"><i class="fa fa-check"></i>Sjekkliste</a></li>';
						}

						if ($user->hasPermission('event.seatmap')) {
							$content .= '<li' . ($this->pageName == 'event-seatmap' ? ' class="active"' : null) . '><a href="?page=event-seatmap"><i class="fa fa-map-marker"></i>Setekart</a></li>';
						}

						if ($user->hasPermission('event.screen')) {
							$content .= '<li' . ($this->pageName == 'event-screen' ? ' class="active"' : null) . '><a href="?page=event-screen"><i class="fa fa-desktop"></i>Skjerm</a></li>';
						}

						if ($user->hasPermission('event.agenda')) {
							$content .= '<li' . ($this->pageName == 'event-agenda' ? ' class="active"' : null) . '><a href="?page=event-agenda"><i class="fa fa-clock-o"></i>Agenda</a></li>';
						}

						if ($user->hasPermission('event.table-labels')) {
							$content .= '<li><a href="../api/pages/utils/printTableLabels.php"><i class="fa fa-external-link"></i>Print bordlapper</a></li>';
						}

					$content .= '</ul>';
				$content .= '</li>';
			}

            if ($user->hasPermission('stats')) {
                $content .= '<li class="treeview' . (StringUtils::startsWith($this->pageName, 'stats') ? ' active' : null) . '">';
                $content .= '<a href="?page=stats"><i class="fa fa-line-chart"></i><span>Statistikk</span><i class="fa fa-angle-left pull-right"></i></a>';
                $content .= '<ul class="treeview-menu">';

                if ($user->hasPermission('stats.age')) {
                    $content .= '<li' . ($this->pageName == 'stats-age' ? ' class="active"' : null) . '><a href="?page=stats-age"><i class="fa fa-birthday-cake"></i>Alder</a></li>';
                }

                if ($user->hasPermission('stats.gender')) {
                    $content .= '<li' . ($this->pageName == 'stats-gender' ? ' class="active"' : null) . '><a href="?page=stats-gender"><i class="fa fa-venus-mars"></i>Kjønn</a></li>';
                }

                if ($user->hasPermission('stats.ticketsale')) {
                    $content .= '<li' . ($this->pageName == 'stats-ticketsale' ? ' class="active"' : null) . '><a href="?page=stats-ticketsale"><i class="fa fa-ticket"></i>Billetsalg</a></li>';
                }

                $content .= '</ul>';
                $content .= '</li>';
            }

			if ($user->hasPermission('admin')) {
				$content .= '<li class="treeview' . (StringUtils::startsWith($this->pageName, 'admin') ? ' active' : null) . '">';
					$content .= '<a href="?page=admin"><i class="fa fa-wrench"></i><span>Administrator</span><i class="fa fa-angle-left pull-right"></i></a>';
					$content .= '<ul class="treeview-menu">';

						if ($user->hasPermission('admin.event')) {
							$content .= '<li' . ($this->pageName == 'admin-event' ? ' class="active"' : null) . '><a href="?page=admin-event"><i class="fa fa-calendar"></i>Arrangementer</a></li>';
						}

						if ($user->hasPermission('admin.permission')) {
							$content .= '<li' . ($this->pageName == 'admin-permission' ? ' class="active"' : null) . '><a href="?page=admin-permission"><i class="fa fa-check-square-o"></i>Tilganger</a></li>';
						}

						if ($user->hasPermission('admin.memberlist')) {
							$content .= '<li' . ($this->pageName == 'admin-memberlist' ? ' class="active"' : null) . '><a href="index.php?page=admin-memberlist"><i class="fa fa-check-square-o"></i>Medlemsliste</a></li>';
						}

						if ($user->hasPermission('admin.seatmap')) {
							$content .= '<li' . ($this->pageName == 'admin-seatmap' ? ' class="active"' : null) . '><a href="?page=admin-seatmap"><i class="fa fa-map-marker"></i>Endre setekart</a></li>';
						}

						if ($user->hasPermission('admin.websocket')) {
							$content .= '<li' . ($this->pageName == 'admin-wsconsole' ? ' class="active"' : null) . '><a href="?page=admin-wsconsole"><i class="fa fa-terminal"></i>Websocket-konsoll</a></li>';
						}

					$content .= '</ul>';
				$content .= '</li>';
			}

			if ($user->hasPermission('developer')) {
				$content .= '<li class="treeview' . (StringUtils::startsWith($this->pageName, 'developer') ? ' active' : null) . '">';
					$content .= '<a href="?page=developer"><i class="fa fa-rocket"></i><span>Utvikler</span><i class="fa fa-angle-left pull-right"></i></a>';
					$content .= '<ul class="treeview-menu">';

						if ($user->hasPermission('developer.switch-user')) {
							$content .= '<li' . ($this->pageName == 'developer-switch-user' ? ' class="active"' : null) . '><a href="?page=developer-switch-user"><i class="fa fa-users"></i>Bytt bruker</a></li>';
						}

						if ($user->hasPermission('developer.syslog')) {
							$content .= '<li' . ($this->pageName == 'developer-syslog' ? ' class="active"' : null) . '><a href="?page=developer-syslog"><i class="fa fa-tv"></i>Systemlogg</a></li>';
						}

					$content .= '</ul>';
				$content .= '</li>';
			}

			/* TODO: Implement compo stuff.
			if ($this->pageName=='compo-overview' ||
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

                if ($user->hasPermission('compo.casting')) {
                    echo '<li><a ' . ($this->pageName == 'compo-casting' ? ' class="active"' : null) . ' href="index.php?page=compo-casting">Casting</a></li>';
                }
			}
			*/

			$content .= '</ul>';

			return $content;
	}

	private function getPage(string $pageName): string {
		$content = null;

		// Fetch the page object from the database and display it.
		$page = RestrictedPageHandler::getPageByName($pageName);

		if ($page != null) {
			if (Session::isAuthenticated()) {
				$user = Session::getCurrentUser();

				// Content Header (Page header)
				$content .= '<section class="content-header">';
						$content .= '<h1>';
							$content .= $page->getTitle();
							$content .= '<small>' . $page->getTitle() . '</small>';
						$content .= '</h1>';
						/*
						$content .= '<ol class="breadcrumb">';
						$content .= '<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>';
						$content .= '<li class="active">Dashboard</li>';
						$content .= '</ol>';
						*/
				$content .= '</section>';

				// Main content
				$content .= '<section class="content">';
					$content .= $page->getContent();
				$content .= '</section>';
			} else {
				$content .= 'Du har ikke tilgang til dette.'; // TODO: Improve this with a nice error box. 404?
			}
		} else {
			$directoryList = ['pages',
                        Settings::api_path . 'pages'];
			$found = false;

			foreach ($directoryList as $directory) {
				$filePath = $directory . '/' . $pageName . '.php';

				if (in_array($filePath, glob($directory . '/*.php'))) {
					// Make sure we don't include pages with same name twice,
					// and set the found variable so that we don't have to display the not found message.
					require_once $filePath;

					// Get the last declared class.
					$declaredClass = get_declared_classes();
					$class = end($declaredClass);

					if (class_exists($class)) {
						// Create a new instance of this class.
						$page = new $class();

						if (Session::isAuthenticated()) {
							// Content Header (Page header)
							$content .= '<section class="content-header">';
									$content .= '<h1>';

										// Check if this page as a parent or not, and decide what to show.
										if ($page->hasParent()) {
											$content .= $page->getParent()->getTitle();
											$content .= '<small>' . $page->getTitle() . '</small>';
										} else {
											$content .= $page->getTitle();
										}

									$content .= '</h1>';

									/*
									$content .= '<ol class="breadcrumb">';
									$content .= '<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>';
									$content .= '<li class="active">Dashboard</li>';
									$content .= '</ol>';
									*/
							$content .= '</section>';

							// Main content
							$content .= '<section class="content">';
								$content .= $page->getContent();
							$content .= '</section>';
						} else {
							$content .= $page->getContent();
						}

						// The page is valid and should not be included twice.
						$found = true;
						break;
					}
				}
			}

			if (!$found) {
                // Content Header (Page header)
                $content .= '<section class="content-header">';
                    $content .= '<h1>Page not found</h1>';
                $content .= '</section>';

				// Main content
				$content .= '<section class="content">';
					$content .= '<div class="error-page">';
						$content .= '<h2 class="headline text-yellow">404</h2>';
						$content .= '<div class="error-content">';
							$content .= '<h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>';
							$content .= '<p>We could not find the page you were looking for.<br>';
								$content .= 'Meanwhile, you may <a onClick="history.back()">return to last page</a> or try using the search form.</p>';
						$content .= '</div>';
					$content .= '</div>';
				$content .= '</section>';
			}
		}

		return $content;
	}
}

Database::cleanup();
