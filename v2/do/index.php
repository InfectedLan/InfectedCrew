<?php
	//error_reporting(E_ALL);
 	//ini_set("display_errors", 1);
	session_start();
	require_once "../api/mysql.php";
	require_once "../api/user.php";
	require_once "../api/error.php";
	require_once "../api/security.php";
	require_once "../api/mailing.php";
	require_once "../api/team.php";
	require_once "../api/crew.php";
	
	if (isset($_GET["register"])) {
		$result = mysql_query('SELECT * FROM infecrjn_infected.registrationcodes WHERE code=\'' . mysql_real_escape_string(stripslashes($_GET["register"])) . '\';');
		
		if (mysql_num_rows($result) > 0) {
			// La brukeren logge inn
			mysql_query('DELETE FROM infecrjn_infected.registrationcodes WHERE code=\'' . mysql_real_escape_string(stripslashes($_GET["register"])) . "'");
			header('Location: ' . $_GET['returnUrl'] . '?info=' . urlencode('Din brukerkonto har blitt aktivert!'));
			die();
		}
		
		header('Location: ' . $_GET['returnUrl'] . '?error=' . urlencode('Din brukerkonto har allerede blitt aktivert!'));
	} else if (isset($_GET["logout"])) {
		session_destroy();
		header("location:" . $_GET["logout"]);
	} else if (isset($_GET["addCrew"])) {
		$name = mysql_real_escape_string(stripslashes($_POST["name"]));
		$description = mysql_real_escape_string(stripslashes($_POST["description"]));

		if(!isset($_SESSION["username"])||User::getCrewChiefing($_SESSION["username"])=="") {
			die('ACCESS DENIED<br /><img src="http://c22blog.files.wordpress.com/2012/10/skiidie.jpg" />');
		}

		$result = mysql_query("INSERT INTO `crews` (`name`, `description`) VALUES ('" . $name . "', '" . $description . "');");
		header("location:" . $_GET["addCrew"]);
	} else if (isset($_GET["editCrew"])) {
		$id = mysql_real_escape_string(stripslashes($_GET["editCrew"]));
		if(!isset($_SESSION["username"])||User::getCrewChiefing($_SESSION["username"])=="")
		{
			die('ACCESS DENIED<br /><img src="http://c22blog.files.wordpress.com/2012/10/skiidie.jpg" />');
		}

		$result = mysql_query("SELECT * FROM `crews` WHERE `id` = '" . $id . "';");
		if(mysql_num_rows($result)==0)
		{
			header("location:" . $_GET["returnUrl"]);
			die();
		}

		$name = mysql_real_escape_string(stripslashes($_POST["name"]));
		$desc = mysql_real_escape_string(stripslashes($_POST["description"]));
		$chief = mysql_real_escape_string(stripslashes($_POST["chief"]));

		$result = mysql_query("UPDATE `crews` SET `chief` = '" . $chief . "' WHERE `id` = '" . $id . "';");
		$result = mysql_query("UPDATE `crews` SET `name` = '" . $name . "' WHERE `id` = '" . $id . "';");
		$result = mysql_query("UPDATE `crews` SET `description` = '" . $desc . "' WHERE `id` = '" . $id . "';");

		header("location:" . $_GET["returnUrl"]);
	} else if (isset($_GET["deletecrew"])) {
		$id = mysql_real_escape_string(stripslashes($_GET["deletecrew"]));
		if(!isset($_SESSION["username"])||User::getCrewChiefing($_SESSION["username"])=="")
		{
			die('ACCESS DENIED<br /><img src="http://c22blog.files.wordpress.com/2012/10/skiidie.jpg" />');
		}
		//Get crew name, and check if it exists
		$result = mysql_query("SELECT * FROM `crews` WHERE `id` = '" . $id . "';");
		if(mysql_num_rows($result)==0)
		{
			header("location:" . $_GET["returnUrl"]);
			die();
		}

		$crewname = mysql_result($result, 0, "name");

		//Delete crew from database
		$result = mysql_query("DELETE FROM `crews` WHERE `id` = '" . $id . "';");

		//Notify users that their crew has dissapeared. Gotta tell them when they are facing a eviction!
		$result = mysql_query("SELECT * FROM `infecrjn_infected`.`users` WHERE `crew` = '" . $id . "';");
		$i = 0;
		while($i < mysql_num_rows($result))
		{
			$email = mysql_result($result, $i, "email");
			mail($email, "Crewet ditt har blitt fjernet!", "Hei!<br />Denne e-posten er sendt til deg fordi crewet du var med i har blitt fjernet av administratorene. Dette kan være av flere grunner. Dersom du faktisk får denne e-posten, noe webutvikleren som skriver dette tror aldri kommer til å skje, annbefaler jeg deg å ta kontakt med infected via <a" . ' href="mailto:kontakt@infected.no">kontakt@infected.no</a><br /><br />Mhv. Infected.no' . "'s administratorer");
			$i++;
		}

		//Remove crew status from users
		$result = mysql_query("UPDATE `infecrjn_infected`.`users` SET `crew` = 'NONE' WHERE `crew` = '" . $crewname . "';");

		header("location:" . $_GET["returnUrl"]);
	} else if (isset($_GET["sokCrew"])) {
		if(!isset($_POST["iAmTrappedInAWebsiteFactory"])||!isset($_POST["soknad"]))
		{
			header("location:" . $_GET["sokCrew"] . "?error=" . urlencode("Du må fylle inn alle feltene!"));
			die();
		}
		if(!isset($_SESSION["username"]))
		{
			header("location:" . $_GET["sokCrew"] . "?error=Du+er+ikke+logget+inn" . urlencode("!"));
			die();
		}
		if(isset($_SESSION["crew"]))
		{
			header("location:" . $_GET["sokCrew"] . "?error=" . urlencode("Du er allerede i et crew!"));
			die();
		}
		//Process
		$crewid = mysql_real_escape_string(stripslashes($_POST["iAmTrappedInAWebsiteFactory"]));
		//Check for duplicate applications
		$checker = mysql_query("SELECT * FROM `soknader` WHERE `crew`='" . $crewid . "' AND `username`='" . $_SESSION["username"] . "' AND `status`='PROCESSING';");
		if(mysql_num_rows($checker)!=0)
		{
			header("location:" . $_GET["sokCrew"] . "&error=" . urlencode("Du har allerede en søknad på dette crewet."));
			die();
		}
		//yolo
		$soknad = XssBegone(mysql_real_escape_string(stripslashes($_POST["soknad"])));
		$getUser = mysql_query("SELECT * FROM `infecrjn_infected`.`users` WHERE `username` = '" . $_SESSION["username"] . "';");
		sendMail(mysql_result($getUser, 0, "email"), "Søknad plassert!", "Takk for din søknad!<br /><br />Søknaden din vil bli behandlet av crew cheif fortløpende.<br />" . '<a href="http://crew.infected.no/v2/index.php?page=sok">Trykk her</a> for å se behandlingen av søknaden.');
		$result = mysql_query("INSERT INTO `soknader` (`username`, `crew`, `soknad`, `timeplaced`) VALUES ('" . $_SESSION["username"] . "', '" . $crewid . "', '" . $soknad . "', '" . date('l jS \of F Y h:i:s A') . "');");

		header("location:" . $_GET["sokCrew"] . "&info=" . urlencode("Søknaden har blitt plassert!"));
	} else if (isset($_GET["gtfo"])) {
		if(!isset($_SESSION["username"]))
		{
			header("location:" . $_GET["sokCrew"] . "?error=Du+er+ikke+logget+inn" . urlencode("!"));
			die();
		}
		$me = mysql_query("SELECT * FROM `infecrjn_infected`.`users` WHERE `username` = '" . $_SESSION["username"] . "';");
		$cake = mysql_query("SELECT * FROM `crews` WHERE `chief` = '" . mysql_result($me, 0, "username") . "';");
		if($me==FALSE||mysql_num_rows($me)==0)
		{
			header("location:../index.php?error=Du+er+ikke+chief" . urlencode("!"));
			die();
		}
		$theVictim = mysql_query("SELECT * FROM `infecrjn_infected`.`users` WHERE `username` = '" . mysql_real_escape_string(stripslashes($_GET["gtfo"])) . "';");
		if(mysql_result($theVictim, 0, "crew")==mysql_result($cake, 0, "id"))
		{
			mysql_query("UPDATE `infecrjn_infected`.`users` SET `crew` = 'NONE' WHERE `username` = '" . mysql_real_escape_string(stripslashes($_GET["gtfo"])) . "';");
			sendMail(mysql_result($theVictim, 0, "email"), "Du er fjernet fra crew på infected!", "Hei, " . mysql_result($theVictim, 0, "firstname") . ".<br />Du har fått denne meldingen fordi du har blitt fjernet fra " . mysql_result($cake, 0, "name") . ".");
		}
		else
		{
			echo "wat.";
		}
		header("location:../index.php");
	}
	elseif(isset($_GET["accept"]))
	{
		$id = mysql_real_escape_string(stripslashes($_GET["accept"]));
		if(!isset($_SESSION["username"])||User::getCrewChiefing($_SESSION["username"])=="")
		{
			die('ACCESS DENIED<br /><img src="http://c22blog.files.wordpress.com/2012/10/skiidie.jpg" />');
		}
		mysql_query("UPDATE `soknader` SET `status` = 'ACCEPTED' WHERE `id` = '" . $id . "';");
		$getName = mysql_query("SELECT * FROM `soknader` WHERE `id` = '" . $id . "';");
		$result = mysql_query("SELECT * FROM `infecrjn_infected`.`users` WHERE `username` = '" . mysql_result($getName, 0, "username") . "';");
		sendMail(mysql_result($result, 0, "email"), "[Infected.no]Søknaden din har blitt oppdatert!", "Hei, " . mysql_result($result, 0, "firstname") . " " . mysql_result($result, 0, "lastname") . '.<br /><br />Vi har i dag gleden av å fortelle deg at din søknad for crew på infected har blitt godkjent!');
		mysql_query("UPDATE `infecrjn_infected`.`users` SET `crew` = '" . mysql_result($getName, 0, "crew") . "' WHERE `username` = '" . mysql_result($getName, 0, "username") . "';");
		header("location:" . $_GET["returnUrl"]);
	}
	elseif(isset($_GET["decline"]))
	{
		$id = mysql_real_escape_string(stripslashes($_GET["decline"]));
		if(!isset($_SESSION["username"])||User::getCrewChiefing($_SESSION["username"])=="")
		{
			die('ACCESS DENIED<br /><img src="http://c22blog.files.wordpress.com/2012/10/skiidie.jpg" />');
		}
		mysql_query("UPDATE `soknader` SET `status` = 'DECLINED' WHERE `id` = '" . $id . "';");
		mysql_query("UPDATE `soknader` SET `reason` = '" . mysql_real_escape_string(stripslashes($_POST["reason"])) . "' WHERE `id` = '" . $id . "';");
		$getName = mysql_query("SELECT * FROM `soknader` WHERE `id` = '" . $id . "';");
		$result = mysql_query("SELECT * FROM `infecrjn_infected`.`users` WHERE `username` = '" . mysql_result($getName, 0, "username") . "';");
		sendMail(mysql_result($result, 0, "email"), "[Infected.no]Søknaden din har blitt oppdatert!", "Hei, " . mysql_result($result, 0, "firstname") . " " . mysql_result($result, 0, "lastname") . '.<br /><br />Din søknad har blitt behandlet av crew-chiefen, og har blitt avslått.<br />Besøk <a href="http://infected.no/crew/v2/index.php?page=sok">crewsiden</a> for å finne ut mer om dette. Lykke til neste år');
		header("location:" . $_GET["returnUrl"]);
	}
	else if(isset($_GET["editprofile"]))
	{
		if(!isset($_SESSION["username"]))
		{
			header("location:" . $_GET["editprofile"] . "?error=Du+er+ikke+logget+inn" . urlencode("!"));
			die();
		}

		$firstname = $_POST["firstname"];
		$lastname = $_POST["lastname"];
		$gender = $_POST["gender"];
		$nick = $_POST["nick"];
		$email = $_POST["email"];
		$parentinfo = $_POST["parentphone"];
		$phone = $_POST['phone'];
		$address = $_POST["address"];
		$postalCode = $_POST["postalCode"];
		$birthdate = strtotime($_POST['yyyy'] . '-' . $_POST['mm'] . '-' . $_POST['dd']);

		//Validate
		if ($nick == null || $nick == "" || strlen($nick) < 3) {
			echo "Nicket er for kort!";
		}
		
		if (strlen($nick) > 16) {
			echo "Nicket ditt er for langt!";
		}

		if($firstname==null||$firstname==""||strlen($firstname)<4) echo "Ditt fornavn er for kort, eller er ikke troverdig!";
		if(strlen($firstname)>40) echo "Ditt fornavn er veldig langt! Send oss en e-post hvis du vikelig heter dette!";

		if($lastname==null||$lastname==""||strlen($lastname)<4) echo "Ditt etternavn er for kort, eller er ikke troverdig!";
		if(strlen($lastname)>40) echo "Ditt etternavn er veldig langt! Send oss en e-post hvis du vikelig heter dette!";

		if (!is_numeric(date('Y', $birthdate))) {
			return 'Fødselsåret ditt må være et år!';
		}
		
		if (intval(date('Y', $birthdate)) < 1900) {
			return 'Det virker som om fødselsåret ditt ikke er realistisk';
		}
		
		if (intval(date('Y', $birthdate)) > 2012) {
			return 'Det virker som om fødselsåret ditt ikke er realistisk';
		}
		
		if (!is_numeric(date('m', $birthdate))) {
			return 'Fødselsmåneden ditt må være en månede!';
		}
		
		if (intval(date('m', $birthdate)) < 1 || intval(date('m', $birthdate)) > 12) {
			return 'Fødselsmåneden din er ikke en månede!';
		}
		
		if (!is_numeric(date('d', $birthdate))) {
			return 'Fødselsdagen din må være en ordentelig dag!';
		}
			
		if (intval(date('d', $birthdate)) < 1) {
			return 'Det virker som om fødselsdagen ditt ikke er realistisk';
		}	
			
		if (intval(date('d', $birthdate)) > 31) {
			return 'Det virker som om fødselsdagen ditt ikke er realistisk';
		}
		
		if (strlen($phone) != 8 && $phone != null && $phone != "") {
			echo "Det virker som om du ikke har skrevet inn et ordentelig telefonnummer";
		}
		
		if ($parentinfo == null || $parentinfo == "") {
			if ((date('Y') - date('Y', strtotime($birthdate))) < 18) {
				echo "Siden du er under 18, må du skrive inn kontaktinformasjon til en forelder. Dette er for din, og andres sikkerhet.";
			}
		} elseif (strlen($parentinfo) != 8) {
			echo "Det virker som om du ikke har skrevet inn et ordentelig forelder-telefonnummer.";
		}
		
		if($email==null||$email==""||!strpos($email, "@")||!strpos($email, ".")) echo "Du må skrive inn en ordentelig e-post addresse!";
		if(strlen($email)>30) echo "Din e-post addresse er veldig lang. Send os en e-post om dette er et hinder";

		if(strlen($address)>64) echo "Addressen din er for lang! Hvis den virkelig er så lang, registrer med en annen addresse og send oss en mail.";

		if ($postalCode == null || strlen($postalCode) != 4) {
			return 'Det virker som om du ikke har skrevet inn et ordentelig telefonnummer';
		}
		
		if ($gender != 0 && $gender != 1) {
			echo "Du har oppgitt et ugyldig kjønn.";
		}

		//Step 3 - No MYSQLi no cry
		$firstname = XssBegone(mysql_real_escape_string(stripslashes($firstname)));
		$lastname = XssBegone(mysql_real_escape_string(stripslashes($lastname)));
		$birthdate = XssBegone(mysql_real_escape_string(stripslashes($birthdate)));
		$phone = XssBegone(mysql_real_escape_string(stripslashes($phone)));
		$email = mysql_real_escape_string(stripslashes($email));
		$parentinfo = XssBegone(mysql_real_escape_string(stripslashes($parentinfo)));
		$nick = XssBegone(mysql_real_escape_string(stripslashes($nick)));
		$address = XssBegone(mysql_real_escape_string(stripslashes($address)));
		$gender = XssBegone(mysql_real_escape_string(stripslashes($gender)));

		//Step 4 - do stuff
		$result = mysql_query("SELECT * FROM `infecrjn_infected`.`users` WHERE `username` = '" . $_SESSION["username"] . "';");
		if($firstname!=mysql_result($result, 0, "firstname"))
		{
			mysql_query("UPDATE `infecrjn_infected`.`users` SET `firstname` = '" . $firstname . "' WHERE `username` = '" . $_SESSION["username"] . "';");
		}
		if($lastname!=mysql_result($result, 0, "lastname"))
		{
			mysql_query("UPDATE `infecrjn_infected`.`users` SET `lastname` = '" . $lastname . "' WHERE `username` = '" . $_SESSION["username"] . "';");
		}
		if($gender!=mysql_result($result, 0, "gender"))
		{
			mysql_query("UPDATE `infecrjn_infected`.`users` SET `gender` = '" . $gender . "' WHERE `username` = '" . $_SESSION["username"] . "';");
		}
		if($birthdate!=mysql_result($result, 0, "birthdate"))
		{
			mysql_query("UPDATE `infecrjn_infected`.`users` SET `birthdate` = '" . $birthdate . "' WHERE `username` = '" . $_SESSION["username"] . "';");
		}
		if($phone != mysql_result($result, 0, "phone"))
		{
			mysql_query("UPDATE `infecrjn_infected`.`users` SET `phone` = '" . $phone . "' WHERE `username` = '" . $_SESSION["username"] . "';");
		}
		if($parentinfo!=mysql_result($result, 0, "parentNumber"))
		{
			mysql_query("UPDATE `infecrjn_infected`.`users` SET `parentinfo` = '" . $parentinfo . "' WHERE `username` = '" . $_SESSION["username"] . "';");
		}
		if($nick!=mysql_result($result, 0, "nick"))
		{
			mysql_query("UPDATE `infecrjn_infected`.`users` SET `nick` = '" . $nick . "' WHERE `username` = '" . $_SESSION["username"] . "';");
		}
		
		if ($address != mysql_result($result, 0, "address") && !($address==null || $address == "" || strlen($address) < 3)) {
			mysql_query("UPDATE `infecrjn_infected`.`users` SET `address` = '" . $address . "' WHERE `username` = '" . $_SESSION["username"] . "';");
		}
		
		if ($postalCode != mysql_result($result, 0, "postalCode") && !($postalCode == null || $postalCode == "") && strlen($postalCode) == 4) {
			mysql_query("UPDATE `infecrjn_infected`.`users` SET `postalCode` = '" . $postalCode . "' WHERE `username` = '" . $_SESSION["username"] . "';");
		}
		
		if($email!=mysql_result($result, 0, "email"))
		{
			$regHash = md5(time() . " Dette er et salt 123123123jajaja flygotbusstogflyetER!23123");
			sendMail(mysql_result($result, 0, "email"), "E-post addressen er endret!", "Hei!<br />Hvis du ser dette, betyr det at din E-post addresse har blitt endret til " . XssBegone($email) . ". Dersom du mener at dette er et uhell, ta snarest kontakt med " . '<a href="mailto:kontakt@infected.no">kontakt@infected.no</a>');
			sendMail($email, "E-posten er endret!", "Hei!<br />Noen har endret e-post addressen for brukeren " . $_SESSION["username"] .  "til denne e-post addressen. Vennligst klikk " . '<a href="http://crew.infected.no/v2/do?register=' . $regHash . '&returnUrl=http://crew.infected.no/">her</a> for å verifisere det.');

			mysql_query("UPDATE `infecrjn_infected`.`users` SET `email` = '" . $email . "' WHERE `username` = '" . $_SESSION["username"] . "';");
			mysql_query("UPDATE `infecrjn_infected`.`users` SET `registrationCode` = '" . $regHash . "' WHERE `username` = '" . $_SESSION["username"] . "';");

			echo "E-posten din er blitt endret. Du er logget ut, og du må verifisere brukeren på nytt med den nye addressen for å fortsette.";
			session_destroy();
			die();
		}
		header("location:" . $_GET["editprofile"]);
	}
	elseif(isset($_GET["editpass"]))
	{
		if(!isset($_SESSION["username"]))
		{
			header("location:" . $_GET["editpass"] . "?error=Du+er+ikke+logget+inn" . urlencode("!"));
			die();
		}
		$newpass = $_POST["newpass"];
		$newrepeat = $_POST["newpass2"];
		$oldpass = $_POST["oldpass"];
		if($newpass!=$newrepeat)
		{
			header("location:" . $_GET["editpass"] . "&error=" . urlencode("Nytt passord, og Gjenta nytt passord er ikke like!"));
			die();
		}
		$result = mysql_query("SELECT * FROM `infecrjn_infected`.`users` WHERE `password` = '" . hash("sha256", $oldpass, FALSE) . "' AND `username` = '" . $_SESSION["username"] . "';");
		if($result==FALSE)
		{
			header("location:" . $_GET["editpass"] . "&error=" . urlencode("Det skjedde en feil da vi skulle endre passordet ditt."));
			die();
		}
		if(mysql_num_rows($result)<1)
		{
			header("location:" . $_GET["editpass"] . "&error=" . urlencode("Det gamle passordet er feil!"));
			die();
		}
		else
		{
			$pass = hash("sha256", $newpass, FALSE);
			mysql_query("UPDATE `infecrjn_infected`.`users` SET `password` = '" . $pass . "' WHERE `username` = '" . $_SESSION["username"] . "';");
			sendMail(mysql_result($result, 0, "email"), "Passordet har blitt endret!", "Hei;<br /><br />Du har fått denne e-posten fordi noen(Forhåpentligvis deg) har endret passordet ditt.<br /><br />Dersom dette ikke skulle skje, send en mail til " . '<a href="mailto:kontakt@infected.no">kontakt@infected.no</a> snarest, så skal vi se på det.<br /><br />Mhv. webutvikleren');
			header("location:" . $_GET["editpass"]);
		}
		die();
	}
	elseif(isset($_POST["hash"]))
	{
		echo hash("sha256", $_POST["hash"], FALSE);
	}
	elseif(isset($_GET["forgot"]))
	{
		$email = mysql_real_escape_string(stripslashes($_POST["email"]));

		$resetcode = md5("yolo as#@.,.,sa" . time() . time() . ".." . time() . "<3");

		$result = mysql_query("SELECT * FROM `infecrjn_infected`.`users` WHERE `email` = '" . $email . "';");
		if($result!=FALSE&&mysql_num_rows($result)>0)
		{
			mysql_query("INSERT INTO `passresets` (`username`, `code`) VALUES ('" . mysql_result($result, 0, "username") . "', '" . $resetcode . "');");
			sendMail($email, "[Infected.no]Tilbakestill passord", "Hei;<br /><br />Noen (Forhåpentligvis deg!) har forsøkt å tilbakestille passordet ditt. Dersom dette var meningen, trykk " . '<a href="http://infected.no/crew/v2/index.php?page=reset2&code=' . $resetcode . '">her.</a>');
		}
		header("location:../index.php");
	}
	elseif(isset($_GET["resetStage"]))
	{
		//echo $_POST["code"];
		$code = mysql_real_escape_string(stripslashes($_POST["code"]));
		$result = mysql_query("SELECT * FROM `passresets` WHERE `code` = '" . $code . "';");
		
		if($result!=FALSE&&mysql_num_rows($result)>0)
		{
			$user = mysql_result($result, 0, "username");
			//echo $user;
			mysql_query("UPDATE `infecrjn_infected`.`users` SET `password` = '" . hash("sha256", mysql_real_escape_string(stripslashes($_POST["pass"])), FALSE) . "' WHERE `username` = '" . $user . "';");
			mysql_query("DELETE FROM `passresets` WHERE `username` = '" . $user . "';");
		}
		header("location:../index.php");
	}
	elseif(isset($_GET["newTeam"]))
	{
		if(!isset($_SESSION["username"])||User::getCrewChiefing($_SESSION["username"])=="")
		{
			die('ACCESS DENIED<br /><img src="http://c22blog.files.wordpress.com/2012/10/skiidie.jpg" />');
		}
		if(!isset($_POST["name"])||!isset($_POST["desc"])||!isset($_POST["chief"]))
		{
			header("location:" . urldecode($_GET["newTeam"]) . "&error=" . urlencode("Data mangler! Noe gikk galt."));
			die();
		}
		if(User::getCrewChiefing($_SESSION["username"])!=Crew::getDudesCrew($_POST["chief"]))
		{
			header("location:" . urldecode($_GET["newTeam"]) . "&error=" . urlencode("Chiefen er ikke med i crewet!"));
			die();
		}
		$crew = User::getCrewChiefing($_SESSION["username"]);
		$thing = Team::createTeam($_POST["name"], $crew, $_POST["desc"]);

		$thing->setChief($_POST["chief"]);
		header("location:" . urldecode($_GET["newTeam"]) . "&info=" . urlencode("Gruppen har blitt laget"));
		die();
	}
	elseif(isset($_GET["editTeam"]))
	{
		if(!isset($_SESSION["username"])||User::getCrewChiefing($_SESSION["username"])=="")
		{
			die('ACCESS DENIED<br /><img src="http://c22blog.files.wordpress.com/2012/10/skiidie.jpg" />');
		}
		if(!isset($_POST["name"])||!isset($_POST["desc"]))
		{
			header("location:../index.php?page=chief-teams&error=" . urlencode("Data mangler! Noe gikk galt."));
			die();
		}
		$team = Team::getTeamFromId($_GET["editTeam"]);
		if($team==NULL)
		{
			header("location:../index.php?page=chief-teams&error=" . urlencode("Det er ikke et lag med den id-koden!"));
			die();
		}
		if($team->getCrew()!=User::getCrewChiefing($_SESSION["username"]))
		{
			header("location:../index.php?page=chief-teams&error=" . urlencode("Du har ikke tillatelse til å endre dette crewet!"));
			die();
		}
		if($team->getName()!=$_POST["name"])
		{
			$team->setName($_POST["name"]);
		}
		if($team->getDescription()!=$_POST["desc"])
		{
			$team->setDescription($_POST["desc"]);
		}
		if($team->getChief()!=$_POST["chief"])
		{
			if(User::getCrewChiefing($_SESSION["username"])!=Crew::getDudesCrew($_POST["chief"]))
			{
				header("location:" . urldecode($_GET["newTeam"]) . "&error=" . urlencode("Chiefen er ikke med i crewet!"));
				die();
			}
			$team->setChief($_POST["chief"]);
		}
		header("location:../index.php?page=chief-teams&info=" . urlencode("Instillingene har blitt oppdatert!"));
		die();
	}
	elseif(isset($_GET["deleteTeam"]))
	{
		if(!isset($_SESSION["username"])||User::getCrewChiefing($_SESSION["username"])=="")
		{
			die('ACCESS DENIED<br /><img src="http://c22blog.files.wordpress.com/2012/10/skiidie.jpg" />');
		}
		$team = Team::getTeamFromId($_GET["deleteTeam"]);
		if($team==NULL)
		{
			header("location:../index.php?page=chief-teams&error=" . urlencode("Det er ikke et lag med den id-koden!"));
			die();
		}
		if($team->getCrew()!=User::getCrewChiefing($_SESSION["username"]))
		{
			header("location:../index.php?page=chief-teams&error=" . urlencode("Du har ikke tillatelse til å endre dette crewet!"));
			die();
		}
		$team->deleteTeam();
		header("location:../index.php?page=chief-teams&info=" . urlencode("Teamet har blitt slettet"));
		die();
	}
	elseif (isset($_GET["setTeam"])) 
	{
		$user = mysql_real_escape_string(stripslashes($_GET["setTeam"]));
		if(!isset($_SESSION["username"])||User::getCrewChiefing($_SESSION["username"])=="")
		{
			die('ACCESS DENIED<br /><img src="http://c22blog.files.wordpress.com/2012/10/skiidie.jpg" />');
		}
		$crewid = Crew::getDudesCrew($user);
		if($crewid!=User::getCrewChiefing($_SESSION["username"]))
		{
			header("location:../index.php?error=" . urlencode("Brukeren er ikke medlem i crewet du er chief i!"));
			die();
		}
		if(!isset($_POST["team"]))
		{
			header("location:../index.php?error=" . urlencode("Data mangler!"));
			die();
		}
		Team::addDudeToTeamId($user, $_POST["team"]);
		header("location:../index.php?info=" . urlencode("Brukeren har blitt satt på en ny gruppe!"));
		die();
	}
	else
	{
		echo "Invalid request<br />";
		ErrorReporter::Error("Scriptkiddie-forsøk! Session-data: " . 'TODO ;(');
		die('ACCESS DENIED<br /><img src="http://c22blog.files.wordpress.com/2012/10/skiidie.jpg" />');
	}
?>