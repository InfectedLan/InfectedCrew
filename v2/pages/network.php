<?php
/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2018 Infected <https://infected.no/>.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3.0 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'session.php';

if (Session::isAuthenticated()) {
    $user = Session::getCurrentUser();
	$platform = $_GET['platform'];

    echo '<h3>Trådløsnett under arrangementet</h3>';

	if ($platform == "android" || $platform == "windows") {
		echo '<a href="files/network/' . $platform . '.pdf"><img src="images/pdf.png" width="3%" height="3%">Last ned som PDF</a>';
	}
	
	switch ($platform) {
		// Android
		case 'android':
			echo '<table>';
			  echo '<tr>';
				echo '<td>';
					echo '<p><span><img width=213 height=378 src="images/network/android/image002.png"></span></p>';
					echo '<p>Velg nettverket «<i>Infected</i>».</p>';
				echo '</td>';
				echo '<td>';
					echo '<p><span><img width=213 height=378 src="images/network/android/image006.png"></span></p>';
					echo '<p>Trykk på «<i>EAP-metode</i>» og åpne menyen.</p>';
				echo '</td>';
			  echo '</tr>';
			  echo '<tr>';
				echo '<td>';
					echo '<p><span><img width=213 height=378 src="images/network/android/image004.png"></span></p>';
					echo '<p>Velg «<i>TTLS</i>» fra menyen.</p>';
				echo '</td>';
				echo '<td>';
					echo '<p><span><img width=213 height=378 src="images/network/android/image008.png"></span></p>';
					echo '<p>Trykk på «<i>Fase 2 godkjenning</i>» og åpne menyen.</p>';
				echo '</td>';
			  echo '</tr>';
			  echo '<tr>';
				echo '<td>';
					echo '<p><span><img width=213 height=378 src="images/network/android/image010.png"></span></p>';
					echo '<p>Velg «<i>PAP</i>» fra menyen.</p>';
				echo '</td>';
				echo '<td>';
					echo '<p><span><img width=213 height=378 src="images/network/android/image014.png"></span></p>';
					echo '<p>Fyll inn ditt brukernavn og passord. <i>(Din Infected-bruker som på nettsiden)</i></p>';
				echo '</td>';
			  echo '</tr>';
			  echo '<tr>';
				echo '<td>';
					echo '<p><span><img width=213 height=378 src="images/network/android/image012.png"></span></p>';
					echo '<p>Skjermbildet skal da se slik ut.</p>';
				echo '</td>';
				echo '<td>';
					echo '<p><span><img width=213 height=378 src="images/network/android/image016.png"></span></p>';
					echo '<p>Du er nå koblet til «Infected» sitt trådløse nettverk.</p>';
				echo '</td>';
			  echo '</tr>';
			echo '</table>';
			break;
		
		// IOS
		case "ios":
            echo 'For å koble til det trådløse nettverket på IOS enheter, last ned <a href="files/network/Infected.mobileconfig">denne</a> og installer. <br> Deretter logg inn med din Infected-bruker.';
			break;
		
		// Windows
		case "windows":
			echo '<p><span><img width=279 height=483 src="images/network/windows/image001.png"></span></p>';
			echo '<p>Åpne «<i>Kontrollpanel</i>».</p>';

			echo '<p><span><img width=604 height=340 src="images/network/windows/image002.png"></span></p>';
			echo '<p>Velg «<i>Nettverks- og delingssenter</i>».</p>';

			echo '<p><span><img width=604 height=340 src="images/network/windows/image003.png"></span></p>';
			echo '<p>Velg «<i>Konfigurer en ny tilkobling eller et nytt nettverk</i>».</p>';

			echo '<p><span><img width=605 height=448 src="images/network/windows/image004.png"></span></p>';
			echo '<p>Velg «<i>Koble til et trådløst nettverk manuelt</i>».</p>';

			echo '<p><span><img width=479 height=378 src="images/network/windows/image005.png"></span></p>';
			echo '<p>Fyll inn «<i>Infected</i>» for nettverksnavn og velg «<i>WPA2-Enterprise</i>».</p>';

			echo '<p><span><img width=479 height=378 src="images/network/windows/image006.png"></span></p>';
			echo '<p>Velg «<i>Endre tilkoblingsinnstillinger</i>».</p>';

			echo '<p><span><img width=298 height=397 src="images/network/windows/image007.png"></span></p>';
			echo '<p>Velg fanen «<i>Sikkerhet</i>».</p>';

			echo '<p><span><img width=298 height=397 src="images/network/windows/image008.png"></span></p>';
			echo '<p>Velg godkjenningsmetoden «<i>Microsoft: EAP-TTLS</i>» fra menyen.</p>';

			echo '<p><span><img width=369 height=491 src="images/network/windows/image009.png"></span></p>';
			echo '<p><span>Huk av for «<i>Angi godkjenningsmodus</i>» og trykk på «<i>Lagre legitimasjon</i>».</span></p>';

			echo '<p><span><img width=428 height=302 src="images/network/windows/image010.png"></span></p>';
			echo '<p>Fyll inn ditt brukernavn og passord. <i>(Din Infected-bruker som på nettsiden)</i></p>';

			echo '<p><span><img width=279 height=480 src="images/network/windows/image011.png"></span></p>';
			echo '<p><span>Trykk «<i>Ok</i>» og lukk alle vinduer åpne, og velg nettverket «<i>Infected</i>» fra tilkoblings-menyen.</span></p>';
			break;
			
		default:
			echo 'Vi har introdusert en ny trådløs-løsning som alle har mulighet til å benytte seg av.<br> Du logger på med din Infected-bruker med den enheten du måtte ønske, men løsningen er hovedsakelig tiltenkt mobile enheter.</p>';
			echo 'Nettverkstilgangen vil avhengige av din rolle hos Infected, men alle skal a tilgang til internett.<br> Løsningen kan senere bli tilgjengelig for deltakere også, men da med mer begrensninger.</p>';
            echo 'Trenger du veiledning til å koble til? Du finner dette i menyen over :-)</p>';

            echo '<table>';
                echo '<tr>';
                    echo '<th>SSID:</th>';
                    echo '<td>Infected</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<th>EAP-metode:</th>';
                    echo '<td>TTLS</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<th>Fase 2 godkjenning:</th>';
                    echo '<td>PAP</td>';
                echo '</tr>';


                echo '<tr>';
                    echo '<th>Brukernavn:</th>';
                    echo '<td>Din Infected-bruker</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<th>Passord:</th>';
                    echo '<td>Ditt Infected-passord</td>';
                echo '</tr>';
            echo '</table>';

			break;
	}	
} else {
    echo '<p>Du er ikke logget inn!</p>';
}