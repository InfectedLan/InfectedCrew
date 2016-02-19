<?php
/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2015 Infected <http://infected.no/>.
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
require_once 'handlers/compohandler.php';
require_once 'handlers/eventhandler.php';
require_once 'handlers/clanhandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('compo.management')) {
        $id = $_GET["id"];
        $compo = CompoHandler::getCompo($id);

        if($compo != null) {
	    echo '<script src="scripts/compo.js"></script>';
            //echo '<h1>' . $compo->getTitle() . '</h1>';
            echo '<hr>';
            echo '<a href="index.php?page=compo-view&id=' . $compo->getId() . '">Oversikt</a> ';
            echo '<a href="index.php?page=compo-clans&id=' . $compo->getId() . '">Påmeldte klaner</a> ';
            echo '<a href="index.php?page=compo-matches&id=' . $compo->getId() . '">Matcher(Liste)</a> ';
            if($user->hasPermission('compo.bracketmanagement')) {
                echo '<a href="index.php?page=compo-brackets&id=' . $compo->getId() . '">Rediger brackets</a> ';
            }
            if($user->hasPermission('compo.chat')) {
                echo '<a href="index.php?page=compo-chat&id=' . $compo->getId() . '">Chatter</a> ';
            }
	    if($user->hasPermission('compo.edit') && $compo->getConnectionType() == Compo::CONNECTION_TYPE_SERVER) {
                echo '<a href="index.php?page=compo-servers&id=' . $compo->getId() . '">Servere</a> ';		
	    }
            echo '<hr>';
            $clans = ClanHandler::getClansByCompo($compo);

            echo '<h1>Kvalifiserte lag:</h1><br>';
            echo '<table>';
            foreach($clans as $clan) {
                if($clan->isQualified($compo)) {
                 
                    echo '<tr>';
                   		echo '<td>';
                    		echo '<a href="index.php?page=compo-clan&id=' . $clan->getId() . '">' . $clan->getTag() . ' ' . $clan->getName() . '</a> ';
                    	echo '</td>';
                        echo '<td>';

                        	$playing = ClanHandler::getPlayingClanMembers($clan);
                        	$stepin = ClanHandler::getStepInClanMembers($clan);

                        	echo count($playing) . ' spillende medlemmer, ';
                        	echo count($stepin) . ' step-ins';
                        
                        echo '</td>';
                        echo '<td>';
                        	echo '<input type="button" value="Diskvalifiser" onClick="disqualifyClan(' . $clan->getId() . ')" />';
                        echo '</td>';
                        echo '<td>';
                        	echo '<input type="button" value="Slett" onClick="deleteClan(' . $clan->getId() . ', ' . (ClanHandler::getClanMemberCount($clan) > 0 ? 'true' : 'false') . ')" />';
                        echo '</td>';
                    echo '</tr>';
                }
            }
            echo '</table>';

            echo '<h1>Lag i kø:</h1><br>';
            echo '<table>';
            foreach($clans as $clan) {
                if(ClanHandler::isInQualificationQueue($clan)) {
                    echo '<tr>';
                   		echo '<td>';
                    		echo '<a href="index.php?page=compo-clan&id=' . $clan->getId() . '">' . $clan->getTag() . ' ' . $clan->getName() . '</a> ';
                    	echo '</td>';
                        echo '<td>';

                        	$playing = ClanHandler::getPlayingClanMembers($clan);
                        	$stepin = ClanHandler::getStepInClanMembers($clan);

                        	echo count($playing) . ' spillende medlemmer, ';
                        	echo count($stepin) . ' step-ins';
                        
                        echo '</td>';
                        echo '<td>';
                        	echo '<input type="button" value="Kvalifiser" onClick="qualifyClan(' . $clan->getId() . ')" />';
                        echo '</td>';
                        echo '<td>';
                        	echo '<input type="button" value="Slett" onClick="deleteClan(' . $clan->getId() . ', ' . (ClanHandler::getClanMemberCount($clan) > 0 ? 'true' : 'false') . ')" />';
                        echo '</td>';
                    echo '</tr>';
                }
            }
            echo '</table>';

            echo '<h1>Ukvalifiserte lag:</h1><br>';
            echo '<table>';
            foreach($clans as $clan) {
                if(!$clan->isQualified($compo) && !ClanHandler::isInQualificationQueue($clan)) {
                    echo '<tr>';
                   		echo '<td>';
                    		echo '<a href="index.php?page=compo-clan&id=' . $clan->getId() . '">' . $clan->getTag() . ' ' . $clan->getName() . '</a> ';
                    	echo '</td>';
                        echo '<td>';

                        	$playing = ClanHandler::getPlayingClanMembers($clan);
                        	$stepin = ClanHandler::getStepInClanMembers($clan);

                        	echo count($playing) . ' spillende medlemmer, ';
                        	echo count($stepin) . ' step-ins';
                        
                        echo '</td>';
                        echo '<td>';
                        	echo '<input type="button" value="Kvalifiser" onClick="qualifyClan(' . $clan->getId() . ')" />';
                        echo '</td>';
                        echo '<td>';
                        	echo '<input type="button" value="Slett" onClick="deleteClan(' . $clan->getId() . ', ' . (ClanHandler::getClanMemberCount($clan) > 0 ? 'true' : 'false') . ')" />';
                        echo '</td>';
                    echo '</tr>';
                }
            }
            echo '</table>';

            echo '<b>Viser totalt ' . count($clans) . ' klaner</b>';
           
        } else {
            echo '<p>Compoen eksisterer ikke!</p>';
        }

	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>
