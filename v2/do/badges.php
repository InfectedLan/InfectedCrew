<?php
	require_once '../api/security.php';
	require_once '../api/error.php';
	require_once '../api/user.php';
	require_once '../api/mysql.php';
	require_once '../api/avatar.php';
	session_start();
	if(!isset($_SESSION["username"])||User::getCrewChiefing($_SESSION["username"])=="")
	{
		die('ACCESS DENIED<br /><img src="http://c22blog.files.wordpress.com/2012/10/skiidie.jpg" />');
	}
	function getColorStyle($crewid)
	{
		$col = "#000000";
		if($crewid=="16") $col = "#10CFFF"; //Core
		if($crewid=="15") $col = "#409900"; //Info
		if($crewid=="14") $col = "#155BDD"; //Tech
		if($crewid=="19") $col = "#00FF00"; //Kafe
		if($crewid=="21") $col = "#FF9600"; //Backstage
		if($crewid=="22") $col = "#FFEE00"; //Event
		if($crewid=="26") $col = "#F52727"; //Game
		if($crewid=="17") $col = "#FF3399"; //Security
		return "background-color: " . $col . ";";
	}
	//CONFIGURATION
	$maxcols = 3;
	$table = 1;
	$maxRowsPerTable = 2;

	//
	echo '<html><head><meta name="viewport" content="width=3005, initial-scale=1"><meta charset="UTF-8"><link rel="stylesheet" type="text/css" href="badge.css"><title>Crewbadges</title></head><body><script>window.print();</script>'; //Header info
	//Grab SQL
	$result = mysql_query("SELECT * FROM `infecrjn_infected`.`users` WHERE `crew`!='NONE'");
	if($table==1&&$maxRowsPerTable==-1) echo "<table>";
	$colsDone = 0;
	$tableIndex = 0;
	for($i = 0; $i < mysql_num_rows($result); $i++)
	{
		if($colsDone==0&&$table==1)
		{
			if($tableIndex==0&&$table=1&&$maxRowsPerTable>-1)
			{
				echo "<table>";
			}
			echo '<tr>';
		}
		if($table==1) {echo "<td>"; } else { echo "<div>"; }
		//Print badge
		echo '<div class="badge">';
		//Get avatar
		echo '<div class="avatar"><img src="../api/avatars/' . Avatar::getAvatarForUser(mysql_result($result, $i, "username")) . '" width="350" height="263"/></div>';
		//Get QR
		echo '<div class="qr"><img src="../api/getQR.php?data= ' . urlencode("https://crew.infected.no/v2/index.php?page=profile&id=" . mysql_result($result, $i, "id")) .  ' " height="250"/></div>';
		//Get crew
		$crew = mysql_query("SELECT * FROM `crews` WHERE `id`='" . mysql_result($result, $i, "crew") . "';");
		echo '<div class="crewname"><h1>' . mysql_result($crew, 0, "name") . '</h1></div>';
		//Get group
		echo '<div class="team">';
		if(mysql_result($result, $i, "team")=="0")
		{
			echo '<h3>Medlem</h3>';
		}
		else
		{
			$gruppe = mysql_query("SELECT * FROM `teams` WHERE `id`='" . mysql_result($result, $i, "team") . "';");
			if($gruppe==FALSE||mysql_num_rows($gruppe)==0)
			{
				echo '<h3>Medlem</h3><i>(Intern error)</i>';
			}
			else
			{
				echo '<h3>' . mysql_result($gruppe, 0, "name") . '</h3>';
			}
		}
		echo '</div>';
		//Get name
		echo '<div class="name"><center>' . mysql_result($result, $i, "firstname") . ' ' . mysql_result($result, $i, "lastname") . '<br />' . mysql_result($result, $i, "nick") . '</center></div>';
		//Logo
		echo '<div class="infectedlogo"><img src="../images/infected_logo_600x211.jpg"></div>'; //
		//Bottom thingie
		echo '<div class="bottomLabel" style="' . getColorStyle(mysql_result($result, $i, "crew")) . '"><center>Vinter 2014</center</div>';
		echo '</div>';
		if($table==1)
		{
			echo "</td>";
		}
		else
		{
			echo "</div>";
		}
		if($colsDone==$maxcols&&$table==1)
		{
			echo '</tr>';
			$colsDone = 0;
			if($tableIndex==$maxRowsPerTable-1)
			{
				echo '</table>';
				$tableIndex=0;
			}
			else
			{
				$tableIndex++;
			}
		}
		else
		{
			$colsDone++;
		}
	}
	if($table==1)
	{
		echo "</table>";
	}
	echo '</body></html>';
?>