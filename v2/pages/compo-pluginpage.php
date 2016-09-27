<?php

require_once 'session.php';
require_once 'handlers/compohandler.php';
require_once 'handlers/clanhandler.php';
require_once 'handlers/matchhandler.php';
require_once 'handlers/compopluginhandler.php';

if(Session::isAuthenticated()) {
    $user = Session::getCurrentUser();
    if ($user->hasPermission('compo.management')) {
	if(isset($_GET["id"]) &&
	   !empty($_GET["id"]) &&
	   isset($_GET["pluginPage"]) &&
	   !empty($_GET["pluginPage"])) {
	    $compo = CompoHandler::getCompo($_GET["id"]);
	    if($compo != null) {
		$pluginMeta = CompoPluginHandler::getPluginMetadata($compo->getPluginName());
		echo '<hr>';
		echo '<a href="index.php?page=compo-view&id=' . $compo->getId() . '">Oversikt</a> ';
		echo '<a href="index.php?page=compo-clans&id=' . $compo->getId() . '">Påmeldte klaner</a> ';
		echo '<a href="index.php?page=compo-matches&id=' . $compo->getId() . '">Matcher(Liste)</a> ';
		if($user->hasPermission('compo.bracketmanagement')) {
		    echo '<a href="index.php?page=compo-brackets&id=' . $compo->getId() . '">Rediger brackets</a> ';
		}
		if($user->hasPermission('compo.chat')) {
		    echo '<a href="index.php?page=compo-chat&id=' . $compo->getId() . '">Chatter</a> ';
		}
		if($user->hasPermission('compo.edit') && $compo->getConnectionType() == Compo::CONNECTION_TYPE_SERVER) {
		    echo '<a href="index.php?page=compo-servers&id=' . $compo->getId() . '">Servere</a> ';		
		}
		foreach($pluginMeta["pages"] as $pageObj) {
		    echo '<a href="index.php?page=compo-pluginpage&id=' . $compo->getId() . '&pluginPage=' . $pageObj["urlName"] . '">' . $pageObj["title"] . '</a>';
		}
		echo '<hr>';
		$pageExists = false;
		foreach($pluginMeta["pages"] as $page) {
		    if($page["urlName"]==$_GET["pluginPage"]) {
			$pageExists = true;
			break;
		    }
		}
		if($pageExists) {
		    if(file_exists(Settings::api_path . "plugins/compo/" . $page["file"])) {
			require_once 'plugins/compo/' . $page["file"];
		    } else {
			echo "<h1>The plugin page is missing</h1><i>This is a fault in the plugin json declaration. Contect a developer.</i>";
		    }
		} else {
		    echo "<h1>The plugin page is not registered</h1>";
		}
	    } else {
		echo "<h1>The compo does not exist</h1>";
	    }
	} else {
	    echo "<h1>Missing arguments</h1>";
	}
    } else {
	echo "<h1>Du har ikke tillatelse til å være her</h1>";
    }
} else {
    echo "<h1>Du er ikke logget inn!</h1>";
}
?>