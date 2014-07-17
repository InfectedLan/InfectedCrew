<?php
	//Login
	session_start();
	require_once '../api/mysql.php';
	require_once '../api/user.php';

	$result = User::loginUser($_POST['name'], $_POST['pass']);
	
	if ($result) {
		header('Location:../' . $_GET['returnUrl']);
	} else {
		header('Location:../' . $_GET['returnUrl'] . '?error=' . urlencode($result));
	}
?>