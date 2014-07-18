<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils.php';

if (Utils::isAuthenticated()) {
	$user = Utils::getUser();
	
	echo '<h1>Crew-søknad</h1>';
	
	if (!$user->isGroupMember()) {
		if ($user->hasAvatar()) {
			echo '<p>Velkommen! Som crew vil du oppleve ting du aldri ville opplevd som deltager, få erfaring du kan bruke på CV-en din, og møte mange nye og spennende folk. Dersom dette er første gangen du søker som crew på infected, annbefaler vi at du leser igjennom crewbeskrivelsene. Disse finer du <a href="index.php?page=crewene">her</a>. Klar til å søke? Fyll ut skjemaet under:</p>';
			
			echo '<form action="do/index.php?sokCrew=../index.php?page=sok" method="post">';
				echo '<b>Crew:</b>';
				echo '<select name="group">';
					$groupList = $this->database->getGroups();
					
					foreach ($groupList as $group) {
						echo '<option value="' . $group->getId() . '">' . $group->getTitle() . '</option>';
					}
				echo '</select>';

				echo '<b>Hvorfor vil du søke dette crewet?</b>';
				echo '<textarea rows="10" cols="100" name="content" placeholder="Skriv en kort opsummering av hvorfor du vil søke, på under 512 tegn, her."></textarea><br />';
				echo '<i>Maximum 512 tegn</i><br /><br />';
				echo '<input type="submit" value="Søk!" />';
			echo '</form>';
		} else {
			echo '<h3>Du er nødt til å laste opp et profilbilde for å søke. Dette gjør du <a href="index.php?page=edit-avatar">her.</a>';
		}
	} else {
		$group = $user->getGroup();
		
		echo 'Du er allerede med i <a href="index.php?page=crew&id=' . $group->getId() . '">' . $group->getTitle() . '</a> crew!<br>';
		echo '<a href="index.php">Tilbake</a>';
	}
} else {
	echo 'Du må være logget inn for å søke!<br>';
	echo '<a href="index.php">Tilbake</a>';
}

/*	
		$result = mysql_query("SELECT * FROM `soknader` WHERE `username` = '" . $_SESSION["username"] . "';");
	$i = 0;
	while($i<mysql_num_rows($result))
	{
		echo "<h1>Søknad ID " . mysql_result($result, $i, "id") . "</h1>";
		$status = mysql_result($result, $i, "status");
		if($status=="PROCESSING")
		{
			echo 'Søknaden din blir for øyeblikket behandlet. Kom tilbake senere for resultatet! Du får også mail.';
		}
		elseif($status=="DECLINED")
		{
			echo '<h3>Avslått!</h3>Søknaden din har desverre blitt avslått med følgende begrunnelse: <br /><br /><i>' . mysql_result($result, $i, "reason") . '</i><br /><br />Du har fremdeles muligheten til å søke deg inn i de andre crewene. ;)';
		}
		elseif($status=="ACCEPTED")
		{
			echo '<h3>Godkjent!</h3>Du har blitt tatt inn i crew! Velkommen!';
		}
		else
		{
			echo '<h3>Error</h3>Noe har gått galt, og søknaden din har havnet i gokk! Den er der fremdeles, men den har ikke noe ordentelig status. Feilen har blitt rapportert.';
			Error("Brukeren " . $_SESSION["username"] . " sin søknad's status har blitt korrupt!");
		}
		echo '<br /><br /><h4>Søknaden din:</h4><div class="soknadbox"><i>' . XssBegone(mysql_result($result, $i, "soknad")) . "</i></div>";
		$getCrewName = mysql_query("SELECT * FROM `crews` WHERE `id` = '" . mysql_result($result, $i, "crew") . "';");
		if(mysql_num_rows($getCrewName)==0)
		{
			echo '<h2>Crewet ditt er borte!</h2>';
			ErrorReporter::Error($_SESSION["username"] . " har en søknad i et crew som ikke eksisterer!");
		}
		echo '<br />Du søkte <b>' . mysql_result($getCrewName, 0, "name") . "</b>";
		echo '<br /><br />Søknaden ble plassert den <b>' . mysql_result($result, $i, "timeplaced") . "</b>.";
		if(mysql_result($result, $i, "timechanged")!="")
		{
			echo ' Den ble sett på den <b>' . mysql_result($result, $i, "timechanged") . '</b>.';
		}
		$i++;
	}
*/
?>