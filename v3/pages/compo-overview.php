<?php

require_once 'session.php';
require_once 'handlers/compohandler.php';
require_once 'handlers/clanhandler.php';
require_once 'handlers/matchhandler.php';

if(Session::isAuthenticated()) {
    $compos = CompoHandler::getCompos();
    $timerList = [];
    $uniqueTimerCounter = 0;

    foreach($compos as $compo) {
        echo '<h1>' . $compo->getTag() . ' - ' . $compo->getTitle() . '(<i>' . $compo->getName() . '</i>)</h1>';
        echo '<p>Registrering ender ' . date('Y-m-d H:m:s', $compo->getRegistrationEndTime()) . ' (<span class="compoTimer' . $uniqueTimerCounter . '"></span>)</p>';
        $timerList[] = ['id' => $uniqueTimerCounter, "when" => $compo->getRegistrationEndTime()];
        $uniqueTimerCounter++;

        echo '<p>Compoen starter ' . date('Y-m-d H:m:s', $compo->getStartTime()) . ' (<span class="compoTimer' . $uniqueTimerCounter . '"></span>)</p>';
        $timerList[] = ['id' => $uniqueTimerCounter, "when" => $compo->getStartTime()];
        $uniqueTimerCounter++;

        $participants = ClanHandler::getClansByCompo($compo);
        $qualified = 0;
        $notQualified = 0;
        $queued = 0;

        foreach($participants as $participant) {
            if($participant->isQualified($compo)) {
                $qualified++;
            } else {
                if(ClanHandler::isInQualificationQueue($participant)) {
                    $queued++;
                } else {
                    $notQualified++;
                }
            }
        }

        echo '<p>Deltagere: ' . $qualified . ($compo->getParticipantLimit() != 0 ? '/' . $compo->getParticipantLimit() : '') . ' (' . $qualified . ' kvalifiserte, ' . $queued . ' i kø, ' . $notQualified . ' uferdige)</p>';

        $pendingMatches = MatchHandler::getPendingMatchesByCompo($compo);
        $currentMatches = MatchHandler::getCurrentMatchesByCompo($compo);
        $finishedMatches = MatchHandler::getFinishedMatchesByCompo($compo);
        $totalCount = (count($pendingMatches) + count($currentMatches) + count($finishedMatches));

        echo '<p>Matcher: ' . $totalCount . ' (' . count($pendingMatches) . ' vendende matcher, ' . count($currentMatches) . ' nåværende matcher, ' . count($finishedMatches) . ' ferdige matcher)</p>';
    }

    //Register the timers
    echo "<script>$(document).ready(function(){";

    foreach ($timerList as $timer) {
      echo 'registerTimer(".compoTimer' . $timer["id"] . '", ' . $timer["when"] . ');';
    }

    echo "});</script>";
} else {
    echo "<h1>Du er ikke logget inn!</h1>";
}
?>