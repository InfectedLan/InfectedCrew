<?php
require_once 'session.php';
require_once 'handlers/userhandler.php';

$username = isset($_POST['username']) ? $_POST['username'] : 0;

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('admin.utils.generateclass')) {
		echo '<h1>Generer php-klasse</h1>';
		echo '<p>Denne utilityen kan brukes dersom du er for lat til å skrive klasser og sql queries. Genererer handler, objekt, og SQL for deg!</p>';
		echo '<script src="scripts/generateClass.js"></script>';
		echo '<b>Navn: </b><input type="text" value="ticketType" id="objectName" /><br />';
		echo '<b>DB: </b>Settings::<input type="text" value="db_name_infected_main" id="dbName" /><br />';
		echo '<b>Table name: </b>Settings::<input type="text" value="db_table_infected_main_slides" id="tableName" /><br />';
		echo '<b>Felt/variabler: </b><br />';
		echo '<div id="fieldContainer">';
			echo '<div id="field1">';
				echo 'Navn: ';
					echo '<input type="text" id="name1" value="id" />';
				echo 'Sql type: ';
					echo '<input type="text" id="sql1" value="int" />';
				echo 'Sql length: ';
					echo '<input type="text" id="length1" value="11" />';
				echo 'Auto increment: ';
					echo '<input type="checkbox" id="autoIncrement1" checked="yes"/>';
			echo '</div>';
		echo '</div>';
		echo '<input type="button" onClick="generate()" value="Generer!" />';
		echo '<input type="button" onClick="addRow()" value="Legg til rad!" />';
		echo '<input type="button" onClick="removeRow()" value="Fjern rad!" /><br />';
		echo '<textarea rows="15" cols="100" id="sqlResult">Sql kommer her...</textarea><br />';
		echo '<textarea rows="30" cols="100" id="classResult">Klasse kommer her...</textarea><br />';
		echo '<textarea rows="20" cols="100" id="handlerResult">Handler kommer her...</textarea><br />';
		echo '<b>Husk å lage entry for db i Settings!</b>';
	} else {
		echo 'Du har ikke rettigheter til dette.';
	}
} else {
	echo 'Du er ikke logget inn.';
}
?>