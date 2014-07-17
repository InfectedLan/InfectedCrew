<?php
	require_once 'api/security.php';
	if(!defined('check')){die('Direct access not premitted');}
?>
<form name="input" action="do/index.php?resetStage=1" method="post">
<?php echo '<input type="hidden" name="code" value="' . XssBegone($_GET["code"]) . '">'; ?>
	<h2>Glemt passord</h2>
	<table>
		<tr>
			<td>Nytt passord:</td>
			<td><input type="password" name="pass"></td>
		</tr>
		<tr>
			<td><input type="submit" value="Endre!"></td>
		</tr>
	</table>
</form>