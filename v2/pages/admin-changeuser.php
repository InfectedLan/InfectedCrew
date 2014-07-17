<?php
	require_once 'api/security.php';
	require_once 'api/mysql.php';
	if(!isset($_SESSION["username"])||!User::hasPermission($_SESSION["username"], "admin"))
	{
		die('ACCESS DENIED<br /><img src="http://c22blog.files.wordpress.com/2012/10/skiidie.jpg" />');
	}
	if(!isset($_POST["username"]))
	{
		echo '<h1>Bytt bruker</h1>Dette er en admin-funksjon som lar deg bytte brukeren din. Husk at du ikke har lov til å misbruke dette.';
		echo '<b>Brukernavn:</b><form name="yolo" action="index.php?page=admin-changeuser" method="post"><br /><input type="text" name="username" /><input type="submit" value="Go!" name="submit" /></form>';
	}
	else
	{
		$result = mysql_query("SELECT * FROM `infecrjn_infected`.`users` WHERE `username`='" . mysql_real_escape_string(stripslashes($_POST["username"])) . "';");
		if($result==FALSE||mysql_num_rows($result)==0)
		{
			echo '<h1>Bytt bruker</h1>Dette er en admin-funksjon som lar deg bytte brukeren din. Husk at du ikke har lov til å misbruke dette.';
			echo '<b>Brukernavn:</b><form name="yolo" action="index.php?page=admin-changeuser" method="post"><br /><input type="text" name="username" /><input type="submit" value="Go!" name="submit" /></form>';
			echo '<br /><br /><b>Brukernavnet du skrev inn eksisterer ikke!</b>';
		}
		else
		{
			session_destroy();
			session_start();
			$_SESSION["username"] = mysql_real_escape_string(stripslashes($_POST["username"]));
			$_SESSION["realname"] = mysql_result($result, 0, "firstname") . " " . mysql_result($result, 0, "lastname");
			$crew = mysql_result($result, 0, "crew");
			if($crew!="NONE")
			{
				$_SESSION["crew"] = mysql_result($result, 0, "crew");
			}
			$_SESSION["isReallyAdmin"] = "Yeah, for sho' shiz my nizz";
			echo '<script type="text/javascript">window.location.href = "index.php?info=' . urlencode("Du har nå blitt logget inn som " . $_POST["username"]) . '";</script>';
		}
	}
?>