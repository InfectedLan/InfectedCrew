<?php
/*
	AVATAR STATES:
	0 - uploaded
	1 - cropped
	2 - accepted 
	3 - rejected

	SIZES:

	Thumbnail: 150x113
	SD: 800x600
	Hq: 1200x900
*/
	require_once '../api/user.php';
	require_once '../api/utils.php';
	require_once '../api/mailing.php';
	session_start();
	//Avatar code
	if(!isset($_SESSION["username"]))
	{
		header("location:../index.php?error=" . urlencode("Du er ikke logget inn!"));
		die();
	}
	if(isset($_POST["x"])&&isset($_POST["y"])&&isset($_POST["w"])&&isset($_POST["h"]))
	{
		//ITS CROPPING TIME
		$result = mysql_query("SELECT * FROM `avatars` WHERE `owner` = '" . $_SESSION["username"] . "';");
		if($result==FALSE||mysql_num_rows($result)==0)
		{
			header("location:../index.php?page=edit-avatar&error=" . urlencode("Du har ingen avatar!"));
			die();
		}
		if(mysql_result($result, 0, "state")!="0")
		{
			header("location:../index.php?page=edit-avatar&error=" . urlencode("Din avatar er allerede satt beskjært!"));
			die();
		}
		//The avatar is like, err, a proper avatar.
		$relativeUrl = mysql_result($result, 0, "relativeUrl");
		$temp = explode(".", $relativeUrl);
		$extension = strtolower(end($temp));

		$image = 0;
		if($extension=="png")
		{
			$image = imagecreatefrompng("../api/avatars/" . $relativeUrl);
		}
		elseif($extension=="jpeg"||$extension=="jpg")
		{
			$image = imagecreatefromjpeg("../api/avatars/" . $relativeUrl);
		}
		else
		{
			header("location:../index.php?page=edit-avatar&error=" . urlencode("Din avatar er i et format vi ikke støtter. Denne meldingen skal ikke vises."));
			die();
		}
		$scalefactor = imagesx($image)/800; //Because the image is styled to 800 in width, and the crop tool works on that, we need to calculate scale.
		//Render to tumbnail
		$target = imagecreatetruecolor(150, 113);
		imagecopyresized($target, $image, 0, 0, $_POST["x"]*$scalefactor, $_POST["y"]*$scalefactor, 150, 113, $_POST["w"]*$scalefactor, $_POST["h"]*$scalefactor);
		imagejpeg($target, "../api/avatars/thumbnail/" . str_replace_last($extension, "jpg", $relativeUrl), 75);

		//Render to sd
		$target = imagecreatetruecolor(800, 600);
		imagecopyresized($target, $image, 0, 0, $_POST["x"]*$scalefactor, $_POST["y"]*$scalefactor, 800, 600, $_POST["w"]*$scalefactor, $_POST["h"]*$scalefactor);
		imagejpeg($target, "../api/avatars/sd/" . str_replace_last($extension, "jpg", $relativeUrl), 100);

		//Render to hq
		$target = imagecreatetruecolor(1200, 900);
		imagecopyresized($target, $image, 0, 0, $_POST["x"]*$scalefactor, $_POST["y"]*$scalefactor, 1200, 900, $_POST["w"]*$scalefactor, $_POST["h"]*$scalefactor);
		imagejpeg($target, "../api/avatars/hq/" . str_replace_last($extension, "jpg", $relativeUrl), 100);

		mysql_query("UPDATE `avatars` SET `state` = '1' WHERE `id` = '" . mysql_result($result, 0, "id") . "';");
		mysql_query("UPDATE `avatars` SET `relativeUrl` = '" . str_replace_last($extension, "jpg", $relativeUrl) . "' WHERE `id` = '" . mysql_result($result, 0, "id") . "';");

		unlink("../api/avatars/" . mysql_result($result, 0, "relativeUrl"));

		header("location:../index.php?page=edit-avatar&info=" . urlencode("Avataren din har blitt beskjært!"));
		die();
	}
	elseif(isset($_GET["accept"]))
	{
		$id = mysql_real_escape_string(stripslashes($_GET["accept"]));
		if(User::getCrewChiefing($_SESSION["username"])==""&&!User::hasPermission($_SESSION["username"], "admin"))
		{
			header("location:../index.php?error=" . urlencode("Du er ikke chief! Hvorfor prøver du dette?"));
			die();
		}
		mysql_query("UPDATE `avatars` SET `state`='2' WHERE `id` = '" . $id . "';");
		header("location:../index.php?page=chief-avatars&info=" . urlencode("Avataren har blitt godkjent."));
		die();
	}
	elseif(isset($_GET["reject"]))
	{
		$id = mysql_real_escape_string(stripslashes($_GET["reject"]));
		if(User::getCrewChiefing($_SESSION["username"])==""&&!User::hasPermission($_SESSION["username"], "admin"))
		{
			header("location:../index.php?error=" . urlencode("Du er ikke chief! Hvorfor prøver du dette?"));
			die();
		}
		$result = mysql_query("SELECT * FROM `avatars` WHERE `id` = '" . $id . "';");
		if($result==FALSE||mysql_num_rows($result)==0)
		{
			header("location:../index.php?page=chief-avatars&error=" . urlencode("Avateren finnes ikke!"));
			die();
		}
		unlink("../api/avatars/sd/" . mysql_result($result, 0, "relativeUrl"));
		unlink("../api/avatars/hq/" . mysql_result($result, 0, "relativeUrl"));
		unlink("../api/avatars/thumbnail/" . mysql_result($result, 0, "relativeUrl"));
		mysql_query("UPDATE `avatars` SET `state` = '3' WHERE `id` = '" . $id . "';");
		$owner = mysql_result($result, 0, "owner");
		$user = mysql_query("SELECT * FROM `infecrjn_infected`.`users` WHERE `username` = '" . $owner . "';");
		sendMail(mysql_result($user, 0, "email"), "Avataren din har blitt avslått!", "Hei<br />Din avatar har blitt avslått av en chief. Vennligst last opp et annet bilde.");
		header("location:../index.php?page=chief-avatars&info=" . urlencode("Avataren har blitt avslått."));
		die();
	}
	elseif(isset($_GET["delete"]))
	{
		$id = mysql_real_escape_string(stripslashes($_GET["delete"]));
		$result = mysql_query("SELECT * FROM `avatars` WHERE `owner` = '" . $_SESSION["username"] . "' AND `id` = '" . $id . "';");
		if($result==FALSE||mysql_num_rows($result)==0)
		{
			header("location:../index.php?page=edit-avatar&error=" . urlencode("Det er ingen avatar å slette, eller så er ikke avataren din. Sketchy oppførsel uansett. fy."));
			die();
		}
		if(mysql_result($result, 0, "state")=="0")
		{
			unlink("../api/avatars/" . mysql_result($result, 0, "relativeUrl"));
			mysql_query("DELETE FROM `avatars` WHERE `id`='" . $id . "';");
		}
		else
		{
			unlink("../api/avatars/sd/" . mysql_result($result, 0, "relativeUrl"));
			unlink("../api/avatars/hq/" . mysql_result($result, 0, "relativeUrl"));
			unlink("../api/avatars/thumbnail/" . mysql_result($result, 0, "relativeUrl"));
			mysql_query("DELETE FROM `avatars` WHERE `id`='" . $id . "';");
		}
		
		header("location:../index.php?page=edit-avatar&info=" . urlencode("Avataren har blitt slettet."));
		die();
	}
	else
	{
		$result = mysql_query("SELECT * FROM `avatars` WHERE `owner` = '" . $_SESSION["username"] . "' AND `state` = '0';");
		if($result!=FALSE&&mysql_num_rows($result)>0)
		{
			$a = 0;
			while($a < mysql_num_rows($result))
			{
				unlink("../api/avatars/" . mysql_result($result, $a, "relativeUrl"));
				mysql_query("DELETE FROM `avatars` WHERE `id`='" . mysql_result($result, $a, "id") . "';");
				$a++;
			}
			
		}
		$result = mysql_query("SELECT * FROM `avatars` WHERE `owner` = '" . $_SESSION["username"] . "' AND `state` != '0' AND `state` != '3');");
		if($result!=FALSE&&mysql_num_rows($result)>0)
		{
			$a = 0;
			while($a < mysql_num_rows($result))
			{
				unlink("../api/avatars/thumbnail/" . mysql_result($result, $a, "relativeUrl"));
				unlink("../api/avatars/sd/" . mysql_result($result, $a, "relativeUrl"));
				unlink("../api/avatars/hq/" . mysql_result($result, $a, "relativeUrl"));
				mysql_query("DELETE FROM `avatars` WHERE `id`='" . mysql_result($result, $a, "id") . "';");
				$a++;
			}
		}
		$result = mysql_query("SELECT * FROM `avatars` WHERE `owner` = '" . $_SESSION["username"] . "' AND `state` != '3';");
		if($result!=FALSE&&mysql_num_rows($result)>0)
		{
			$a = 0;
			while($a < mysql_num_rows($result))
			{
				unlink("../api/avatars/thumbnail/" . mysql_result($result, $a, "relativeUrl"));
				unlink("../api/avatars/sd/" . mysql_result($result, $a, "relativeUrl"));
				unlink("../api/avatars/hq/" . mysql_result($result, $a, "relativeUrl"));
				//unlink("../api/avatars/" . mysql_result($result, $a, "relativeUrl"));
				mysql_query("DELETE FROM `avatars` WHERE `id`='" . mysql_result($result, $a, "id") . "';");
				$a++;
			}
		}
		mysql_query("DELETE FROM `avatars` WHERE `owner` = '" . $_SESSION["username"] . "';");
		$allowedExts = array("jpeg", "jpg", "png");
		$temp = explode(".", $_FILES["file"]["name"]);
		$extension = strtolower(end($temp));
		if(($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/x-png") || ($_FILES["file"]["type"] == "image/png"))
		{
			if (($_FILES["file"]["size"] < 7000000))
			{
				if(in_array($extension, $allowedExts))
				{
					if ($_FILES["file"]["error"] > 0)
					{
						header("location:../index.php?page=edit-avatar&error=" . urlencode($_FILES["file"]["error"]));
						die();
					}
					else
					{
						/*
						echo "Upload: " . $_FILES["file"]["name"] . "<br>";
						echo "Type: " . $_FILES["file"]["type"] . "<br>";
						echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
						echo "Stored in: " . $_FILES["file"]["tmp_name"];
						*/
					}
				}
				else
				{
					header("location:../index.php?page=edit-avatar&error=" . urlencode("Ugyldig filetternavn! Du lastet opp en " . $extension . " fil! Vi støtter kun jpg og png."));
					die();
				}
			}
			else
			{
				header("location:../index.php?page=edit-avatar&error=" . urlencode("Filen er for stor!"));
				die();
			}
		}
		else
		{
			header("location:../index.php?page=edit-avatar&error=" . urlencode("Filtypen er ikke riktig MIME-format. Vi støtter kun jpg, jpeg, og png."));
			die();
		}
		//Save
		$name = md5(time() . "yoloswag") . $_SESSION["username"];
		move_uploaded_file($_FILES["file"]["tmp_name"], "../api/avatars/" . $name . "." . $extension);
		mysql_query("INSERT INTO `avatars` (`owner`, `relativeUrl`, `state`) VALUES ('" . $_SESSION["username"] . "', '" . $name . "." . $extension . "', '0');");
		
		header("location:../index.php?page=edit-avatar&info=" . urlencode("Avataren har blitt lastet opp."));
		die();
	}
?>