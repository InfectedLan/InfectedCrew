<form name="input" action="scripts/process_user.php?action=3" method="post">
    <h2>Registrer</h2>
	<table>
		<tr>
			<td>Fornavn:</td>
			<td><input type="text" name="firstname"></td>
		</tr>
		<tr>
			<td>Etternavn:</td>
			<td><input type="text" name="lastname"></td>
		</tr>
		<tr>
			<td>Brukernavn:</td>
			<td><input type="text" name="username"></td>
		</tr>
		<tr>
			<td>Passord:</td>
			<td><input type="password" name="password"></td>
		</tr>
		<tr>
			<td>Gjenta passord:</td>
			<td><input type="password" name="password2"></td>
		</tr>
		<tr>
			<td>E-post:</td>
			<td><input type="email" name="email"></td>
		</tr>
		<tr>
			<td>Kjønn</td>
			<td>
				<select name="gender">
					<option value="0">Mann</option>
					<option value="1">Kvinne</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Fødselsdato:</td>
			<td>
				<select name="birthday">
					<?php
					for ($day = 1; $day < 32; $day++) {
						echo '<option value="' . $day . '">' . $day . '</option>';
					}
					?>
				</select>
				<select name="birthmonth">
					<?php
					$monthList = array('Januar',
									'Februar',
									'Mars',
									'April',
									'Mai',
									'Juni',
									'Juli',
									'August',
									'September', 
									'Oktober',
									'November',
									'Desember');
					
					for ($month = 1; $month < 13; $month++) {
						echo '<option value="' . $month . '">' . $monthList[$month - 1] . '</option>';
					}
					?>
				</select>
				<select name="birthyear">
					<?php
					for ($year = date('Y') - 100; $year < date('Y'); $year++) {
						if ($year == date('Y') - 18) {
							echo '<option value="' . $year . '" selected>' . $year . '</option>';
						} else {
							echo '<option value="' . $year . '">' . $year . '</option>';
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Telefon:</td>
			<td><input type="tel" name="phone"></td>
		</tr>
		<tr>
			<td>Gateadresse:</td>
			<td><input type="text" name="address"></td>
		</tr>
		<tr>
			<td>Postnummer:</td>
			<td><input type="number" name="postalCode" min="1" max="10000"></td>
		</tr>
		<tr>
			<td>Kallenavn:</td>
			<td><input type="text" name="nickname"></td>
		</tr>
		<tr>
			<td>Foresatte's telefon:</td>
			<td><input type="text" name="parent"></td>
			<td>(Påkrevd hvis du er under 18)</td>
		</tr>
		<tr>
			<td><input type="submit" value="Registrer"></td>
		</tr>
	</table>
</form>