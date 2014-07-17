<?php
	require_once '../api/mysql.php';
	require_once '../api/metadata.php';
	require_once '../api/maintenace_mode.php';
	require_once '../api/user.php';
	//ini_set('display_errors',1);
	//error_reporting(E_ALL);
	//Registering
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$password2 = $_POST['password2'];
	$email = $_POST['email'];
	$gender = $_POST['gender'];
	$birthdate =  $_POST['yyyy'] . '-' . $_POST['mm'] . '-' . $_POST['dd'];
	$phone = $_POST['phone'];
	$address = $_POST['address'];
	$postalCode = $_POST['postalCode'];
	$nick = $_POST['nick'];
	$parent = $_POST['parent'];
	
	if ($password != $password2) {
		echo 'Passord og gjenta passord er ikke like!';
		die();
	}
	
	$result = User::registerUser($firstname, $lastname, $username, $password, $email, $gender, $birthdate, $phone,  $address, $postalCode, $nick, $parent);
	
	if ($result == 'true') {
		header('Location:../' . $_GET['returnUrl'] . "?info=" . urlencode("Din bruker har blitt registrert! Sjekk E-Posten din for aktiveringslinken."));
		//echo mysql_error();
	} else {
		header('Location:../' . $_GET['returnUrl'] . '?error=' . urlencode($result));
	}
?>