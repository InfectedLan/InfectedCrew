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

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('compo.management')) {
        $id = $_GET["id"];
        $compo = CompoHandler::getCompo($id);

        if($compo != null) {
            echo '<script src="../api/scripts/bracket.js"></script>';
            echo '<script src="scripts/compo-bracketeditor.js"></script>';
            echo '<link rel="stylesheet" type="text/css" href="../api/styles/bracket.css" />';
            echo "<script>var compoId = " . $compo->getId() . ";$(document).ready(function(){initBracketEditor();});</script>";
            echo '<script>';
            echo 'var participant_type_clan = ' . Settings::compo_match_participant_type_clan . ';';
            echo 'var participant_type_winner = ' . Settings::compo_match_participant_type_match_winner . ';';
            echo 'var participant_type_looser = ' . Settings::compo_match_participant_type_match_looser . ';';
            echo 'var participant_type_walkover = ' . Settings::compo_match_participant_type_match_walkover . ';';
            echo '</script>';
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
            echo '<div class="toolbar">';
	            echo '<input type="button" class="fa fa-2x" value="&#xf0c7;" onClick="save()" ></input>';
                echo '<input type="button" class="fa fa-2x" value="&#xf021;" onClick="refreshBrackets()" ></input>';
                echo '<input type="button" class="fa fa-2x" value="&#xf067;" onClick="addMatch()" ></input>';
                echo '<input type="button" class="fa fa-2x" value="&#xf085;" onClick="generateBrackets()" ></input>';
            echo '</div>';
            echo '<div id="time-prompt" class="editorPrompt">';
	            echo '<h1>Hvilket klokkeslett ønsker du at dette skal kjøre?</h1>';
                echo '<input type="text" id="date_days" value="' . date("o-m-j") . '" />';
                echo '<input type="text" id="date_hours" value="' . date("G:i:s") . '" />';
                echo '<br>';
                echo '<input type="button" value="Ok" onClick="timePromptFinished()" />';
            echo '</div>';
            echo '<div id="participantPrompt" class="editorPrompt">';
	            echo '<h1>Hva slags deltager vil du legge til?</h1>';
    	        echo '<input type="radio" id="participantTypeClan" name="participantType" class="participantType" value="' . Settings::compo_match_participant_type_clan . '" selected/> Clan<br />';
                echo '<input type="radio" id="participantTypeWinner" name="participantType" class="participantType" value="' . Settings::compo_match_participant_type_match_winner . '" /> Vinner<br />';
                echo '<input type="radio" id="participantTypeLooser" name="participantType" class="participantType" value="' . Settings::compo_match_participant_type_match_looser . '" /> Taper<br />';
                echo '<input type="radio" id="participantTypeWalkover" name="participantType" class="participantType" value="' . Settings::compo_match_participant_type_match_walkover . '" /> Walkover<br />';

                echo '<div id="participantMatchSelector">';
                echo '<h1>Velg clan</h1>';
                $participants = ClanHandler::getClansByCompo($compo);
                foreach($participants as $clan) {
                    echo '<input type="radio" name="participantClan" class="participantClan" value="' .$clan->getId()  . '" /> ' . $clan->getName() . '<br />';
                }
                echo '</div>';
                echo '<div id="participantIdSelector">';
                echo '<h1>Skriv inn match-id</h1>';
                echo '<input id="participantMatchId" type="text" />';
                echo '</div>';
                echo '<input type="button" value="Lag" onClick="addParticipantCallback()"/>';
                echo '<input type="button" value="Angre" onClick="$(\'#participantPrompt\').fadeOut();" />';
            echo '</div>';
            echo '<div id="editor-canvas" class="bracketContainer">';
            	echo '<center>';
            		echo '<i class="fa fa-4x fa-database"></i><br />';
                	echo '<h3>Laster inn data...</h3>';
                echo '</center>';
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
