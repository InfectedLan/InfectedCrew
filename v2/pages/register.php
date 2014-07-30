<?php
require_once 'session.php';
require_once 'utils.php';

echo '<script src="scripts/register.js"></script>';
echo '<script src="scripts/lookupCity.js"></script>';
echo '<form class="register" name="input" method="post">';
    echo '<h2>Registrer</h2>';
	echo '<table>';
		echo '<tr>';
			echo '<td>Fornavn:</td>';
			echo '<td><input type="text" name="firstname"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Etternavn:</td>';
			echo '<td><input type="text" name="lastname"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Brukernavn:</td>';
			echo '<td><input type="text" name="username"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Passord:</td>';
			echo '<td><input type="password" name="password"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Gjenta passord:</td>';
			echo '<td><input type="password" name="confirmpassword"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>E-post:</td>';
			echo '<td><input type="email" name="email"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Kjønn:</td>';
			echo '<td>';
				echo '<select name="gender">';
					echo '<option value="0">Mann</option>';
					echo '<option value="1">Kvinne</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Fødselsdato:</td>';
			echo '<td>';
				echo '<select name="birthday">';
					for ($day = 1; $day < 32; $day++) {
						echo '<option value="' . $day . '">' . $day . '</option>';
					}
				echo '</select>';
				echo '<select name="birthmonth">';
					for ($month = 1; $month < 13; $month++) {
						echo '<option value="' . $month . '">' . Utils::getMonthFromInt($month) . '</option>';
					}
				echo '</select>';
				echo '<select name="birthyear">';
					for ($year = date('Y') - 100; $year < date('Y'); $year++) {
						if ($year == date('Y') - 18) {
							echo '<option value="' . $year . '" selected>' . $year . '</option>';
						} else {
							echo '<option value="' . $year . '">' . $year . '</option>';
						}
					}
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Telefon:</td>';
			echo '<td><input type="tel" name="phone"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Gateadresse:</td>';
			echo '<td><input type="text" name="address"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Postnummer:</td>';
			echo '<td><input class="postalcode" type="number" name="postalcode" min="1" max="10000"></td>';
			echo '<td><span class="city"></span></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Kallenavn:</td>';
			echo '<td><input type="text" name="nickname"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Foresatte\'s telefon:</td>';
			echo '<td><input type="text" name="parent"></td>';
			echo '<td>(Påkrevd hvis du er under 18)</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><input type="submit" value="Registrer deg"></td>';
		echo '</tr>';
	echo '</table>';
echo '</form>';