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
require_once 'handlers/matchhandler.php';
require_once 'handlers/compopluginhandler.php';

function renderMatch($match, $plugin) {
    $participants = MatchHandler::getParticipantsJsonByMatch($match);
	echo '<table>';
    echo '<script src="../api/scripts/compo-bracketeditor.js"></script>';
    $first = true;
    $isReady = true;
    echo '<tr>';
    foreach($participants as $participant) {
        if(!$first) {
            echo " </tr><tr> ";
        }
        if($participant["type"] != Settings::compo_match_participant_type_clan) {
            echo '<td><i>' . $participant["value"] . '</i></td>';
            $isReady = false;
        } else {
            echo '<td>' . $participant["value"] . '</td>';
            if($match->getWinner() == null && $match->getScheduledTime() < time()) {
                echo '<td><input type="button" value="Sett vinner" onClick="setWinner(' . $match->getId() . ', ' . $participant["id"] . ')" /></td>';
            }
        }
        $first = false;
    }
    echo '</tr>';
        echo '<tr>';
        	echo '<td>';
            	echo "Starttid: ";
            echo '</td>';
            echo '<td>';
               	echo date("Y-m-d H:i:s", $match->getScheduledTime());
            echo '</td>';
            echo '<td>';
               	echo 'Status: ';
            echo '</td>';
            echo '<td>';
               	if($isReady) {
               		if($match->getState() == Match::STATE_READYCHECK) {
                       	echo '<b>Venter på spillere</b>';
               		} elseif ($match->getState() == Match::STATE_CUSTOM_PREGAME) {
                        echo '<b>Pregame</b>';
                    } elseif ($match->getState() == Match::STATE_JOIN_GAME) {
                        echo '<b>Spiller</b>';
                    }
                } else {
                    echo '<b>Venter på tidligere match</b>';
                }
            echo '</td>';
        echo '</tr>';
        echo '<tr>';
           	echo '<td>';
               	echo 'Matchid: ';
           	echo '</td>';
           	echo '<td>';
           		echo $match->getId();
            echo '</td>';
        echo '</tr>';
	    $customData = $plugin->getCustomMatchInformation($match);
        if($customData != null) {
        	echo '<tr>';
        		echo '<td>';
            		echo $customData["key"];
        		echo '</td>';
    	    	echo '<td>';
            		echo $customData["value"];
                echo '</td>';
            echo '</tr>';
        }
    echo '</table>';
}

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('compo.management')) {
        $id = $_GET["id"];
        $compo = CompoHandler::getCompo($id);

        if($compo != null) {
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
            echo '<hr>';

            $plugin = CompoPluginHandler::getPluginObjectOrDefault($compo->getPluginName());
            $currentMatches = MatchHandler::getCurrentMatchesByCompo($compo);
            $pendingMatches = MatchHandler::getPendingMatchesByCompo($compo);
            $finishedMatches = MatchHandler::getFinishedMatchesByCompo($compo);

            //Load matches
            echo '<h1>Nåværende matcher</h1>';
            echo '<div id="currentMatches">';
            foreach($currentMatches as $match) {
                renderMatch($match, $plugin);
            }
            echo '</div>';
            echo '<h1>Matcher som venter</h1>';
            echo '<div id="pendingMatches">';
            foreach($pendingMatches as $match) {
                renderMatch($match, $plugin);
            }
            echo '</div>';
            echo '<h1>Ferdige matcher</h1>';
            echo '<div id="finishedMatches">';
            foreach($finishedMatches as $match) {
                renderMatch($match, $plugin);
            }
            echo '</div>';
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