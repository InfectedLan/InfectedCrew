<?php
require_once 'handlers/userhandler.php';

if (isset($_GET['code'])) {
	UserHandler::removeRegistrationCode($_GET['code']);
	
	echo 'Brukeren din er nå aktivert og klar for bruk.';
} else {
	echo 'Brukeren din er allerede aktivert.';
}
?>