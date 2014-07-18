<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils.php';

$type = isset($_GET['type']) ? $_GET['type'] : 0;

if (Utils::isAuthenticated()) {
	$user = Utils::getUser();
	
	if ($user->isGroupMember() && $user->isGroupChief()) {
		$group = $user->getGroup();
		
		echo '<h1>Søknader</h1>';
		
		$applicationList = $group->getPendingApplications();
		
		echo '<i>Det er for øyeblikket</i><b> ' . count($applicationList) . ' </b><i>søknader som trenger behandling.</i>';
		
		/*
		echo 'Vis: ';
		
		if (!isset($_GET["applicationType"])||$_GET["applicationType"]=="0") {
			echo '<b>Bare ubehandlede</b> <a href="index.php?page=chief-applications&applicationType=1">Bare godkjente</a> <a href="index.php?page=chief-applications&applicationType=2">Bare avslåtte</a> <a href="index.php?page=chief-applications&applicationType=3">Alle</a><br />';
		} else if ($_GET["applicationType"]=="1") {
			echo '<a href="index.php?page=chief-applications&applicationType=0">Bare ubehandlede</a> <b>Bare godkjente</b> <a href="index.php?page=chief-applications&applicationType=2">Bare avslåtte</a> <a href="index.php?page=chief-applications&applicationType=3">Alle</a><br />';
		} else if ($_GET["applicationType"]=="2") {
			echo '<a href="index.php?page=chief-applications&applicationType=0">Bare ubehandlede</a> <a href="index.php?page=chief-applications&applicationType=1">Bare godkjente</a> <b>Bare avslåtte</b> <a href="index.php?page=chief-applications&applicationType=3">Alle</a><br />';
		} else if ($_GET["applicationType"]=="3") {
			echo '<a href="index.php?page=chief-applications&applicationType=0">Bare ubehandlede</a> <a href="index.php?page=chief-applications&applicationType=1">Bare godkjente</a> <a href="index.php?page=chief-applications&applicationType=2">Bare avslåtte</a> <b>Alle</b> <br />'; //TODO
		}
		*/
		
		foreach ($applicationList as $application) {
			$state = $application->getState();
			
			echo '<table>';
				echo '<tr>';
					echo '<td>Søknad-id:</td>';
					echo '<td>' . $application->getId() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Status:</td>';
					echo '<td>';
						if ($state == 1) {
							echo '<b>Ubehandlet</b>';
						} else if ($state == 2) {
							echo '<b>Godkjent</b>';
						} else if ($state == 3) {
							echo '<b>Avslått</b>';
						}
					echo '</td>';
				echo '</tr>';
			
				$applicationUser = $application->getUser();
			
				echo '<tr>';
					echo '<td>Gruppe:</td>';
					echo '<td>' . $application->getGroup()->getTitle() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Dato søkt:</td>';
					echo '<td>' . date('d.m.Y', $application->getDatetime()) . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Søkers navn:</td>';
					echo '<td><b>' . $applicationUser->getFirstname() . ' "' . $applicationUser->getNickname() . '" ' . $applicationUser->getLastname() . '</b></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>E-post:</td>';
					echo '<td>' . $applicationUser->getEmail() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Telefon:</td>';
					echo '<td>' . $applicationUser->getPhone() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Alder:</td>';
					echo '<td>' . $applicationUser->getAge() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Søknad:</td>';
					echo '<td><i>' . $application->getContent() . '</i></td>';
				echo '</tr>';
			echo '</table>';
			
			if ($state == 1) {
				//echo '<a href="do/index.php?accept=' . mysql_result($result, $i, "id") . '&returnUrl=../index.php?page=chief-applications">Godkjenn søknad!</a> ...Eller:<br />';
				echo '<form action="do/index.php?decline=' . $application->getId() . '&returnUrl=chief-applications" method="post">';
					echo '<textarea id="editor1" name="content" rows="10" cols="80" placeholder="Skriv hvorfor du vil avslå her."></textarea>';
					echo '<script>';
						// Replace the <textarea id="editor1"> with a CKEditor
						// instance, using default configuration.
						echo 'CKEDITOR.replace(\'editor1\');';
					echo '</script>';
					echo '<input type="submit" value="Avslå">';
				echo '</form>';
				echo '<form action="do/index.php?accept=' . $application->getId() . '&returnUrl=chief-applications" method="post">';
					echo '<input type="submit" value="Godkjenn">';
				echo '</form>';
			} else if ($state == 3) {
				echo 'Begrunnelse for avslåelse: <i>' . $application->getReason() . '</i>';
			}
		}
	}
}

/* 		
			$mordi = mysql_query("SELECT * FROM `crews` WHERE `id` = '" . mysql_result($result, $i, "crew") . "';");
			echo 'Crew: ' . mysql_result($mordi, 0, "name") . '<br />';
			echo 'Dato søkt: ' . mysql_result($result, $i, "timeplaced") . '<br />';
			$user = mysql_query("SELECT * FROM `infecrjn_infected`.`users` WHERE `username` = '" . mysql_result($result, $i, "username") . "';");
			
			echo 'Søker: <b>' . mysql_result($user, 0, "firstname") . ' "' . mysql_result($user, 0, "nick") . '" ' . mysql_result($user, 0, "lastname") . '</b><br />';
			echo "E-post: " . mysql_result($user, 0, "email") . '<br />';
			echo "Telefonnummer: " . mysql_result($user, 0, "phonenumber") . '<br />';
			$bornTimestamp = time() - mktime(0, 0, 0, intval(mysql_result($user, 0, "birthmonth")), intval(mysql_result($user, 0, "birthday")), intval(mysql_result($user, 0, "birthyear")));
			echo 'Alder: ' . intval(($bornTimestamp/31536000)) . ' år<br />';
	
		echo 'Søknad:' . '<br /><br />';
		echo '<i>' . XssBegone(mysql_result($result, $i, "soknad")) . '</i><br /><br />';
		if(mysql_result($result, $i, "status")=="DECLINED")
		{
			echo 'Begrunnelse for avslåelse:<br /><i>' . XssBegone(mysql_result($result, $i, "reason")) . '</i><br />';
		}
		elseif(mysql_result($result, $i, "status")=="PROCESSING")
		{
			//echo '<a href="do/index.php?accept=' . mysql_result($result, $i, "id") . '&returnUrl=../index.php?page=chief-applications">Godkjenn søknad!</a> ...Eller:<br />';
			echo '<form name="yolo" action="do/index.php?decline=' . mysql_result($result, $i, "id") . '&returnUrl=../index.php?page=chief-applications" method="post">';
				echo '<textarea rows="10" cols="100" name="reason" />Skriv hvorfor du vil avslå her.</textarea><br />';
				echo '<table border="0"><tr><td><input type="submit" name="torstein" value="Avslå"></td>';
			echo '</form>';
			echo '<td><form name="yolowe" action="do/index.php?accept=' . mysql_result($result, $i, "id") . '&returnUrl=../index.php?page=chief-applications" method="post"><input type="submit" name="torstein" value="Godkjenn"></td></form></tr></table>';
		}

		$result = mysql_query("SELECT * FROM `soknader` WHERE `crew` = '" . User::getCrewChiefing($_SESSION["username"]) . "' AND `status` = 'PROCESSING';");
		echo '<i>Det er for øyeblikket</i><b> ' . mysql_num_rows($result) . ' </b><i>søknader som trenger behandling.</i><br />';

		$result = mysql_query("SELECT * FROM `soknader` WHERE `crew` = '" . User::getCrewChiefing($_SESSION["username"]) . "';");
		if(!isset($_GET["applicationType"])||$_GET["applicationType"]=="0")
		{
			$result = mysql_query("SELECT * FROM `soknader` WHERE `crew` = '" . User::getCrewChiefing($_SESSION["username"]) . "' AND `status` = 'PROCESSING';");
		}
		elseif($_GET["applicationType"]=="1")
		{
			$result = mysql_query("SELECT * FROM `soknader` WHERE `crew` = '" . User::getCrewChiefing($_SESSION["username"]) . "' AND `status` = 'ACCEPTED';");
		}
		elseif($_GET["applicationType"]=="2")
		{
			$result = mysql_query("SELECT * FROM `soknader` WHERE `crew` = '" . User::getCrewChiefing($_SESSION["username"]) . "' AND `status` = 'DECLINED';");
		}
		elseif($_GET["applicationType"]=="3")
		{
			$result = mysql_query("SELECT * FROM `soknader` WHERE `crew` = '" . User::getCrewChiefing($_SESSION["username"]) . "';");
		}
		else
		{

		}
		
	echo 'Vis: ';
	if(!isset($_GET["applicationType"])||$_GET["applicationType"]=="0")
	{
		echo '<b>Bare ubehandlede</b> <a href="index.php?page=chief-applications&applicationType=1">Bare godkjente</a> <a href="index.php?page=chief-applications&applicationType=2">Bare avslåtte</a> <a href="index.php?page=chief-applications&applicationType=3">Alle</a><br />';
	}
	elseif($_GET["applicationType"]=="1")
	{
		echo '<a href="index.php?page=chief-applications&applicationType=0">Bare ubehandlede</a> <b>Bare godkjente</b> <a href="index.php?page=chief-applications&applicationType=2">Bare avslåtte</a> <a href="index.php?page=chief-applications&applicationType=3">Alle</a><br />';
	}
	elseif($_GET["applicationType"]=="2")
	{
		echo '<a href="index.php?page=chief-applications&applicationType=0">Bare ubehandlede</a> <a href="index.php?page=chief-applications&applicationType=1">Bare godkjente</a> <b>Bare avslåtte</b> <a href="index.php?page=chief-applications&applicationType=3">Alle</a><br />';
	}
	elseif($_GET["applicationType"]=="3")
	{
		echo '<a href="index.php?page=chief-applications&applicationType=0">Bare ubehandlede</a> <a href="index.php?page=chief-applications&applicationType=1">Bare godkjente</a> <a href="index.php?page=chief-applications&applicationType=2">Bare avslåtte</a> <b>Alle</b> <br />'; //TODO
	}
	echo '<br />';
	$i = 0;
	while($i<mysql_num_rows($result))
	{
		echo '<h3>Søknad ' . $i . '</h3>';
		echo 'Søknad-id: ' . mysql_result($result, $i, "id") . '<br />';
		if($_GET["applicationType"]=="3")
		{
			if(mysql_result($result, $i, "status")=="ACCEPTED")
			{
				echo 'Status: <b>GODKJENT</b><br />';
			}
			elseif(mysql_result($result, $i, "status")=="DECLINED")
			{
				echo 'Status: <b>AVSLÅTT</b><br />';
			}
			elseif(mysql_result($result, $i, "status")=="PROCESSING")
			{
				echo 'Status: <b>UBEHANDLET</b><br />';
			}
			else
			{
				echo '<b>Status til søknaden er rar! Web-utvikleren er kontaktet, og jobber på saken.</b><br />';
				Error("Søknaden til " . mysql_result($result, $i, "username") . " er korrupt!");
			}
		}
		$mordi = mysql_query("SELECT * FROM `crews` WHERE `id` = '" . mysql_result($result, $i, "crew") . "';");
		echo 'Crew: ' . mysql_result($mordi, 0, "name") . '<br />';
		echo 'Dato søkt: ' . mysql_result($result, $i, "timeplaced") . '<br />';
		$user = mysql_query("SELECT * FROM `infecrjn_infected`.`users` WHERE `username` = '" . mysql_result($result, $i, "username") . "';");
		if(mysql_num_rows($user)==0)
		{
			echo "Søker: <i>brukeren eksisterer ikke</i><br />"; 
		}
		else
		{
			echo 'Søker: <b>' . mysql_result($user, 0, "firstname") . ' "' . mysql_result($user, 0, "nick") . '" ' . mysql_result($user, 0, "lastname") . '</b><br />';
			echo "E-post: " . mysql_result($user, 0, "email") . '<br />';
			echo "Telefonnummer: " . mysql_result($user, 0, "phonenumber") . '<br />';
			$bornTimestamp = time() - mktime(0, 0, 0, intval(mysql_result($user, 0, "birthmonth")), intval(mysql_result($user, 0, "birthday")), intval(mysql_result($user, 0, "birthyear")));
			echo 'Alder: ' . intval(($bornTimestamp/31536000)) . ' år<br />';
		}
		echo 'Søknad:' . '<br /><br />';
		echo '<i>' . XssBegone(mysql_result($result, $i, "soknad")) . '</i><br /><br />';
		if(mysql_result($result, $i, "status")=="DECLINED")
		{
			echo 'Begrunnelse for avslåelse:<br /><i>' . XssBegone(mysql_result($result, $i, "reason")) . '</i><br />';
		}
		elseif(mysql_result($result, $i, "status")=="PROCESSING")
		{
			//echo '<a href="do/index.php?accept=' . mysql_result($result, $i, "id") . '&returnUrl=../index.php?page=chief-applications">Godkjenn søknad!</a> ...Eller:<br />';
			echo '<form name="yolo" action="do/index.php?decline=' . mysql_result($result, $i, "id") . '&returnUrl=../index.php?page=chief-applications" method="post">';
				echo '<textarea rows="10" cols="100" name="reason" />Skriv hvorfor du vil avslå her.</textarea><br />';
				echo '<table border="0"><tr><td><input type="submit" name="torstein" value="Avslå"></td>';
			echo '</form>';
			echo '<td><form name="yolowe" action="do/index.php?accept=' . mysql_result($result, $i, "id") . '&returnUrl=../index.php?page=chief-applications" method="post"><input type="submit" name="torstein" value="Godkjenn"></td></form></tr></table>';
		}
		$i++;
	} */