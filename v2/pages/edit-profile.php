<?php
require_once 'session.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	echo '<h3>Endre bruker</h3>';
	
	echo '<form action="scripts/process_user.php?action=5&returnPage=profile" method="post">';
		echo '<table>';
			echo '<tr>';
				echo '<td>Fornavn:</td>';
				echo '<td><input type="text" name="firstname" value="' . $user->getFirstname() . '"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Etternavn:</td>';
				echo '<td><input type="text" name="lastname" value="' . $user->getLastname() . '"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Kjønn</td>';
				echo '<td>';
					echo '<select name="gender">';
						$gender = $user->getGender();
						
						if ($gender == 0) {
							echo '<option value="0" selected>Mann</option>';
							echo '<option value="1">Kvinne</option>';
						} else if ($gender == 1) {
							echo '<option value="0">Mann</option>';
							echo '<option value="1" selected>Kvinne</option>';
						}
					echo '</select>';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Nick:</td>';
				echo '<td><input type="text" name="nickname" value="' . $user->getNickname() . '"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>E-post:</td>';
				echo '<td><input type="email" name="email" value="' . $user->getEmail() . '"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Telefon:</td>';
				echo '<td><input type="tel" name="phone" value="' .  str_replace(' ', '', $user->getPhone()) . '"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Fødselsdato:</td>';
				echo '<td>';
					$birthdate = $user->getBirthdate();
				
					echo '<select name="birthday">';
						for ($day = 1; $day < 32; $day++) {
							if ($day == date('d', $birthdate)) {
								echo '<option value="' . $day . '" selected>' . $day . '</option>';
							} else {
								echo '<option value="' . $day . '">' . $day . '</option>';
							}
						}
					echo '</select>';
					echo '<select name="birthmonth">';
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
							if ($month == date('m', $birthdate)) {
								echo '<option value="' . $month . '" selected>' . $monthList[$month - 1] . '</option>';
							} else {
								echo '<option value="' . $month . '">' . $monthList[$month - 1] . '</option>';
							}
						}
					echo '</select>';
					echo '<select name="birthyear">';
						for ($year = date('Y') - 100; $year < date('Y'); $year++) {
							if ($year == date('Y', $birthdate)) {
								echo '<option value="' . $year . '" selected>' . $year . '</option>';
							} else {
								echo '<option value="' . $year . '">' . $year . '</option>';
							}
						}
					echo '</select>';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Adresse:</td>';
				echo '<td><input type="text" name="address" value="' . $user->getAddress() . '"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td></td>';
				echo '<td><td><input type="number" name="postalCode" min="1" max="10000" value="' . $user->getPostalCode() . '"> ' . $user->getCity() . '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td><input type="submit" value="Lagre"></td>';
			echo '</tr>';
		echo '</table>';
	echo '</form>';
	echo '<a href="index.php?page=edit-password">Endre passord</a> <a href="index.php?page=edit-avatar">Endre/Last opp profilbilde</a>';
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>